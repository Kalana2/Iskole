<?php
class MarkEntryModel
{
	protected $pdo;

	public function __construct()
	{
		$this->pdo = Database::getInstance();
	}

	public function getGrades()
	{
		$sql = "SELECT DISTINCT grade FROM `class` ORDER BY grade";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		$rows = $stmt->fetchAll();
		$out = [];
		foreach ($rows as $r) {
			$v = (string)$r['grade'];
			$out[] = ['value' => $v, 'label' => $v];
		}
		return $out;
	}

	public function getClasses($grade = null)
	{
		if ($grade !== null && $grade !== '') {
			$sql = "SELECT class FROM `class` WHERE grade = :grade ORDER BY class";
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute(['grade' => $grade]);
		} else {
			$sql = "SELECT DISTINCT class FROM `class` ORDER BY class";
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute();
		}
		return array_map(function ($r) {
			return $r['class'];
		}, $stmt->fetchAll());
	}

	public function getTerms()
	{
		return [
			['value' => '1', 'label' => 'Term 1'],
			['value' => '2', 'label' => 'Term 2'],
			['value' => '3', 'label' => 'Term 3']
		];
	}

	/*public function getExamTypes()
	{
		return [
			['value' => 'midterm', 'label' => 'Mid-Term Examination'],
			['value' => 'final', 'label' => 'Final Examination'],
			['value' => 'monthly', 'label' => 'Monthly Test'],
			['value' => 'class', 'label' => 'Class Test']
		];
	}*/

	public function getTeacherInfo($userId = null)
	{
		if (!$userId) {
			return ['name' => '', 'subject' => '', 'teacher_id' => null, 'subjectID' => null];
		}

		$sql = "SELECT t.teacherID, t.subjectID, un.firstName, un.lastName, s.subjectName
				FROM teachers t
				LEFT JOIN userName un ON un.userID = t.userID
				LEFT JOIN subject s ON s.subjectID = t.subjectID
				WHERE t.userID = :userId LIMIT 1";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['userId' => $userId]);
		$row = $stmt->fetch();
		if (!$row) {
			return ['name' => '', 'subject' => '', 'teacher_id' => null, 'subjectID' => null];
		}
		return [
			'name' => trim(($row['firstName'] ?? '') . ' ' . ($row['lastName'] ?? '')),
			'subject' => $row['subjectName'] ?? '',
			'teacher_id' => $row['teacherID'] ?? null,
			'subjectID' => $row['subjectID'] ?? null
		];
	}

	public function getStudents($grade = null, $class = null, $subjectID = null, $term = null)
	{
		// Fetch students for given grade & class
		$sql = "SELECT st.studentID, st.userID, un.firstName, un.lastName, st.classID
				FROM students st
				LEFT JOIN userName un ON un.userID = st.userID
				LEFT JOIN `class` c ON c.classID = st.classID
				WHERE c.grade = :grade AND c.class = :class
				ORDER BY un.firstName, un.lastName";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['grade' => $grade, 'class' => $class]);
		$students = $stmt->fetchAll();

		// Prepare mark queries if subjectID/term provided
		$getCurrent = null;
		$getPrevious = null;
		if ($subjectID !== null && $term !== null && $term !== '') {
			$getCurrent = $this->pdo->prepare("SELECT marks FROM marks WHERE studentID = :sid AND subjectID = :subjectID AND term = :term ORDER BY enteredDate DESC LIMIT 1");
			$getPrevious = $this->pdo->prepare("SELECT marks FROM marks WHERE studentID = :sid AND subjectID = :subjectID AND term < :term ORDER BY term DESC LIMIT 1");
		}

		$out = [];
		foreach ($students as $s) {
			$sid = $s['studentID'];
			$current = null;
			$previous = null;
			if ($getCurrent) {
				$getCurrent->execute(['sid' => $sid, 'subjectID' => $subjectID, 'term' => $term]);
				$r = $getCurrent->fetch();
				$current = $r ? $r['marks'] : null;

				$getPrevious->execute(['sid' => $sid, 'subjectID' => $subjectID, 'term' => $term]);
				$pr = $getPrevious->fetch();
				$previous = $pr ? $pr['marks'] : null;
			}

			$out[] = [
				'id' => $sid,
				'reg_number' => $s['userID'],
				'name' => trim(($s['firstName'] ?? '') . ' ' . ($s['lastName'] ?? '')),
				'current_marks' => $current,
				'previous_marks' => $previous,
				'attendance' => null
			];
		}

		return $out;
	}

	public function calculateStatistics(array $students)
	{
		$totalStudents = count($students);
		$marksEntered = count(array_filter($students, function ($s) {
			return $s['current_marks'] !== null && $s['current_marks'] !== '';
		}));
		$marksPending = $totalStudents - $marksEntered;
		$completionPercentage = $totalStudents > 0 ? round(($marksEntered / $totalStudents) * 100) : 0;

		$enteredMarks = array_filter(array_column($students, 'current_marks'), function ($m) {
			return $m !== null && $m !== '';
		});
		$classAverage = !empty($enteredMarks) ? round(array_sum($enteredMarks) / count($enteredMarks), 2) : 0;

		return [
			'totalStudents' => $totalStudents,
			'marksEntered' => $marksEntered,
			'marksPending' => $marksPending,
			'completionPercentage' => $completionPercentage,
			'classAverage' => $classAverage
		];
	}

	private function computeGradeLetter($marks)
	{
		if ($marks === null || $marks === '') return null;
		$m = floatval($marks);
		if ($m >= 75) return 'A';
		if ($m >= 65) return 'B';
		if ($m >= 55) return 'C';
		if ($m >= 35) return 'S';
		return 'W';
	}

	public function saveMarks(array $marks, array $meta = [])
	{
		// Expect meta to contain: subjectID, term, teacherUserId (or teacherID)
		$subjectID = $meta['subjectID'] ?? null;
		$term = $meta['term'] ?? null;
		$teacherUserId = $meta['teacherUserId'] ?? null;

		if (!$subjectID || !$term) {
			throw new Exception('Missing subject or term');
		}

		// Resolve teacherID if we were given a user id
		$teacherID = $meta['teacherID'] ?? null;
		if (!$teacherID && $teacherUserId) {
			$stmt = $this->pdo->prepare("SELECT teacherID FROM teachers WHERE userID = :uid LIMIT 1");
			$stmt->execute(['uid' => $teacherUserId]);
			$r = $stmt->fetch();
			$teacherID = $r ? $r['teacherID'] : null;
		}

		try {
			$this->pdo->beginTransaction();

			$select = $this->pdo->prepare("SELECT markID FROM marks WHERE studentID = :sid AND subjectID = :subjectID AND term = :term LIMIT 1");
			$update = $this->pdo->prepare("UPDATE marks SET marks = :marks, gradeLetter = :gradeLetter, updatedDate = NOW(), teacherID = :teacherID WHERE markID = :markID");
			$insert = $this->pdo->prepare("INSERT INTO marks (studentID, teacherID, subjectID, term, marks, gradeLetter, remarks, enteredDate, updatedDate) VALUES (:studentID, :teacherID, :subjectID, :term, :marks, :gradeLetter, NOW(), NOW())");

			foreach ($marks as $studentId => $m) {
				// Normalize studentId (may be string index)
				$sid = intval($studentId);
				if ($m === '' || $m === null) {
					// skip empty marks (do not delete existing ones)
					continue;
				}
				$marksVal = floatval($m);
				$gradeLetter = $this->computeGradeLetter($marksVal);
				$remarks = '';

				$select->execute(['sid' => $sid, 'subjectID' => $subjectID, 'term' => $term]);
				$existing = $select->fetch();
				if ($existing) {
					$update->execute([
						'marks' => $marksVal,
						'gradeLetter' => $gradeLetter,
						'teacherID' => $teacherID,
						'markID' => $existing['markID']
					]);
				} else {
					$insert->execute([
						'studentID' => $sid,
						'teacherID' => $teacherID,
						'subjectID' => $subjectID,
						'term' => $term,
						'marks' => $marksVal,
						'gradeLetter' => $gradeLetter,
					]);
				}
			}

			$this->pdo->commit();
			return true;
		} catch (Exception $e) {
			if ($this->pdo->inTransaction()) $this->pdo->rollBack();
			throw $e;
		}
	}
}

