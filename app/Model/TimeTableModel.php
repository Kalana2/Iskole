<?php
class TimeTableModel
{
	protected $pdo;
	private $nameTable;
	private $firstNameCol;
	private $lastNameCol;

	public function __construct()
	{
		$this->pdo = Database::getInstance();
		$this->nameTable = $this->detectNameTable();
		[$this->firstNameCol, $this->lastNameCol] = $this->detectNameColumns($this->nameTable);
	}

	private function detectNameTable()
	{
		// Some environments use `userName`, others `username`
		$tables = $this->pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_NUM);
		$flat = array_map(fn($r) => (string)$r[0], $tables);
		if (in_array('userName', $flat, true)) {
			return 'userName';
		}
		if (in_array('username', $flat, true)) {
			return 'username';
		}
		// Fallback to what the codebase mostly uses
		return 'userName';
	}

	private function detectNameColumns($table)
	{
		// Common variants: (firstName,lastName) vs (fName,lName)
		$cols = $this->pdo->query("DESCRIBE `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
		$names = array_map(fn($c) => (string)$c['Field'], $cols);
		$first = in_array('firstName', $names, true) ? 'firstName' : (in_array('fName', $names, true) ? 'fName' : 'firstName');
		$last = in_array('lastName', $names, true) ? 'lastName' : (in_array('lName', $names, true) ? 'lName' : 'lastName');
		return [$first, $last];
	}

	public function getGrades()
{
    $sql = "SELECT DISTINCT grade AS value, grade AS label FROM class ORDER BY grade";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


	public function getClasses($grade = null)
	{
		if ($grade !== null && $grade !== '') {
			$sql = "SELECT classID AS value, class AS label FROM `class` WHERE grade = :grade ORDER BY class";
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute(['grade' => $grade]);
		} else {
			$sql = "SELECT classID AS value, class AS label FROM `class` ORDER BY grade, class";
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute();
		}
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getClassesByGrade($grade)
{
    $sql = "SELECT classID AS value, class AS label 
            FROM class 
            WHERE grade = :grade 
            ORDER BY class";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['grade' => $grade]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

	public function getSubjects()
	{
		$sql = "SELECT subjectID AS value, subjectName AS label FROM subject ORDER BY subjectName";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getTeachersBySubject($subjectID, $grade = null, $classID = null)
	{
		$subjectID = (int)$subjectID;
		if ($subjectID <= 0) {
			return [];
		}

		$where = ['t.subjectID = :subjectID'];
		$params = ['subjectID' => $subjectID];
		if ($grade !== null && $grade !== '') {
			$where[] = 't.grade = :grade';
			$params['grade'] = (int)$grade;
		}
		if ($classID !== null && $classID !== '') {
			$where[] = 't.classID = :classID';
			$params['classID'] = (int)$classID;
		}

		$nameTable = $this->nameTable;
		$first = $this->firstNameCol;
		$last = $this->lastNameCol;

		$sql = "
			SELECT
				t.teacherID AS value,
				TRIM(CONCAT(COALESCE(n.`{$first}`,''), ' ', COALESCE(n.`{$last}`,''))) AS label
			FROM teachers t
			LEFT JOIN `{$nameTable}` n ON n.userID = t.userID
			WHERE " . implode(' AND ', $where) . "
			ORDER BY label
		";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		// If names are missing, fall back to teacherID
		foreach ($rows as &$r) {
			if (!isset($r['label']) || trim((string)$r['label']) === '') {
				$r['label'] = 'Teacher ' . $r['value'];
			}
		}
		unset($r);
		return $rows;
	}

	public function getSchoolDayNameToIdMap()
	{
		$sql = "SELECT id, day FROM schoolDays";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$out = [];
		foreach ($rows as $r) {
			$out[(string)$r['day']] = (int)$r['id'];
		}
		return $out;
	}

	public function getPeriodNumberToIdMap()
	{
		// Map 1..N -> periodID by ordering periodID.
		// Assumption: teaching periods are ordered by periodID.
		$sql = "SELECT periodID FROM periods ORDER BY periodID";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$out = [];
		$idx = 1;
		foreach ($rows as $r) {
			$out[$idx] = (int)$r['periodID'];
			$idx++;
		}
		return $out;
	}

	public function saveClassTimetable($classID, array $entries)
	{
		$classID = (int)$classID;
		if ($classID <= 0) {
			throw new InvalidArgumentException('Invalid classID');
		}

		$this->pdo->beginTransaction();
		try {
			$del = $this->pdo->prepare('DELETE FROM classTimetable WHERE classID = :classID');
			$del->execute(['classID' => $classID]);

			$ins = $this->pdo->prepare('INSERT INTO classTimetable (teacherID, dayID, periodID, classID, subjectID) VALUES (:teacherID, :dayID, :periodID, :classID, :subjectID)');
			foreach ($entries as $e) {
				$teacherID = (int)($e['teacherID'] ?? 0);
				$dayID = (int)($e['dayID'] ?? 0);
				$periodID = (int)($e['periodID'] ?? 0);
				$subjectID = (int)($e['subjectID'] ?? 0);
				if ($teacherID <= 0 || $dayID <= 0 || $periodID <= 0 || $subjectID <= 0) {
					continue;
				}
				$ins->execute([
					'teacherID' => $teacherID,
					'dayID' => $dayID,
					'periodID' => $periodID,
					'classID' => $classID,
					'subjectID' => $subjectID,
				]);
			}
			$this->pdo->commit();
			return true;
		} catch (Throwable $e) {
			$this->pdo->rollBack();
			throw $e;
		}
	}

	public function getClassTimetableEntries($classID)
	{
		$classID = (int)$classID;
		if ($classID <= 0) {
			return [];
		}
		$sql = 'SELECT dayID, periodID, subjectID, teacherID FROM classTimetable WHERE classID = :classID';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['classID' => $classID]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

}