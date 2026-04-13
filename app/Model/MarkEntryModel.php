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

	private function ensureDraftTable(): void
	{
		$sql = "CREATE TABLE IF NOT EXISTS markDrafts (
			draftID INT NOT NULL AUTO_INCREMENT,
			teacherID INT NOT NULL,
			subjectID INT NOT NULL,
			classID INT NOT NULL,
			term VARCHAR(10) NOT NULL,
			studentID INT NOT NULL,
			marks DECIMAL(5,2) NOT NULL,
			updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (draftID),
			UNIQUE KEY uq_mark_draft_scope (teacherID, subjectID, classID, term, studentID),
			KEY idx_mark_draft_lookup (teacherID, subjectID, classID, term)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

		$this->pdo->exec($sql);
	}

	private function resolveClassId($grade, $class)
	{
		if ($grade === null || $class === null || $grade === '' || $class === '') {
			return null;
		}

		$stmt = $this->pdo->prepare("SELECT classID FROM `class` WHERE grade = :grade AND class = :class LIMIT 1");
		$stmt->execute([
			'grade' => $grade,
			'class' => $class
		]);

		$row = $stmt->fetch();
		return $row ? intval($row['classID']) : null;
	}

	public function getDraftMarks($grade = null, $class = null, $subjectID = null, $term = null, $teacherID = null): array
	{
		$teacherID = intval($teacherID);
		$subjectID = intval($subjectID);
		$term = $term === null ? null : strval($term);

		if ($teacherID <= 0 || $subjectID <= 0 || !$term) {
			return [];
		}

		$classID = $this->resolveClassId($grade, $class);
		if (!$classID) {
			return [];
		}

		$this->ensureDraftTable();

		$stmt = $this->pdo->prepare("SELECT studentID, marks
			FROM markDrafts
			WHERE teacherID = :teacherID
			  AND subjectID = :subjectID
			  AND classID = :classID
			  AND term = :term");

		$stmt->execute([
			'teacherID' => $teacherID,
			'subjectID' => $subjectID,
			'classID' => $classID,
			'term' => $term
		]);

		$rows = $stmt->fetchAll();
		$out = [];
		foreach ($rows as $r) {
			$sid = intval($r['studentID'] ?? 0);
			if ($sid <= 0) {
				continue;
			}
			$out[$sid] = $r['marks'];
		}

		return $out;
	}

	public function saveDraftMarks(array $marks, array $meta = []): int
	{
		$subjectID = intval($meta['subjectID'] ?? 0);
		$teacherID = intval($meta['teacherID'] ?? 0);
		$term = isset($meta['term']) ? strval($meta['term']) : null;
		$grade = $meta['grade'] ?? null;
		$class = $meta['class'] ?? null;

		if ($subjectID <= 0 || $teacherID <= 0 || !$term) {
			throw new Exception('Missing subject, teacher, or term for draft save');
		}

		$classID = $this->resolveClassId($grade, $class);
		if (!$classID) {
			throw new Exception('Invalid grade/class for draft save');
		}

		$this->ensureDraftTable();

		$upsert = $this->pdo->prepare("INSERT INTO markDrafts
			(teacherID, subjectID, classID, term, studentID, marks)
			VALUES
			(:teacherID, :subjectID, :classID, :term, :studentID, :marks)
			ON DUPLICATE KEY UPDATE
			marks = VALUES(marks),
			updatedAt = NOW()");

		$delete = $this->pdo->prepare("DELETE FROM markDrafts
			WHERE teacherID = :teacherID
			  AND subjectID = :subjectID
			  AND classID = :classID
			  AND term = :term
			  AND studentID = :studentID");

		$savedCount = 0;

		try {
			$this->pdo->beginTransaction();

			foreach ($marks as $studentId => $m) {
				$sid = intval($studentId);
				if ($sid <= 0) {
					continue;
				}

				if ($m === '' || $m === null) {
					$delete->execute([
						'teacherID' => $teacherID,
						'subjectID' => $subjectID,
						'classID' => $classID,
						'term' => $term,
						'studentID' => $sid,
					]);
					continue;
				}

				if (!is_numeric($m)) {
					continue;
				}

				$marksVal = floatval($m);
				if ($marksVal < 0 || $marksVal > 100) {
					continue;
				}

				$upsert->execute([
					'teacherID' => $teacherID,
					'subjectID' => $subjectID,
					'classID' => $classID,
					'term' => $term,
					'studentID' => $sid,
					'marks' => $marksVal,
				]);

				$savedCount++;
			}

			$this->pdo->commit();
			return $savedCount;
		} catch (Exception $e) {
			if ($this->pdo->inTransaction()) {
				$this->pdo->rollBack();
			}
			throw $e;
		}
	}

	public function clearDraftMarks(array $meta = []): bool
	{
		$subjectID = intval($meta['subjectID'] ?? 0);
		$teacherID = intval($meta['teacherID'] ?? 0);
		$term = isset($meta['term']) ? strval($meta['term']) : null;
		$grade = $meta['grade'] ?? null;
		$class = $meta['class'] ?? null;

		if ($subjectID <= 0 || $teacherID <= 0 || !$term) {
			return false;
		}

		$classID = $this->resolveClassId($grade, $class);
		if (!$classID) {
			return false;
		}

		$this->ensureDraftTable();

		$stmt = $this->pdo->prepare("DELETE FROM markDrafts
			WHERE teacherID = :teacherID
			  AND subjectID = :subjectID
			  AND classID = :classID
			  AND term = :term");

		return $stmt->execute([
			'teacherID' => $teacherID,
			'subjectID' => $subjectID,
			'classID' => $classID,
			'term' => $term
		]);
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

	public function getStudents($grade = null, $class = null, $subjectID = null, $term = null, array $draftMarks = [])
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
		$getPreviousMarks = null;
		if ($subjectID !== null && $term !== null && $term !== '') {
			// Get marks from the current term (show in previous column as reference)
			$getPreviousMarks = $this->pdo->prepare("SELECT marks FROM marks WHERE studentID = :sid AND subjectID = :subjectID AND term = :term ORDER BY enteredDate DESC LIMIT 1");
		}

		$out = [];
		foreach ($students as $s) {
			$sid = $s['studentID'];
			$current = null;
			if (array_key_exists(intval($sid), $draftMarks)) {
				$current = $draftMarks[intval($sid)];
			} elseif (array_key_exists(strval($sid), $draftMarks)) {
				$current = $draftMarks[strval($sid)];
			}
			$previous = null;
			if ($getPreviousMarks) {
				$getPreviousMarks->execute(['sid' => $sid, 'subjectID' => $subjectID, 'term' => $term]);
				$pr = $getPreviousMarks->fetch();
				$previous = $pr ? $pr['marks'] : null;
			}

			$out[] = [
				'id' => $sid,
				'reg_number' => $s['userID'],
				'name' => trim(($s['firstName'] ?? '') . ' ' . ($s['lastName'] ?? '')),
				'current_marks' => $current,  // Always null - empty input for new marks
				'previous_marks' => $previous,  // Previously stored marks for current term
				'attendance' => null
			];
		}

		return $out;
	}

	public function calculateStatistics(array $students)
	{
		$totalStudents = count($students);
		$marksEntered = count(array_filter($students, function ($s) {
			return $s['previous_marks'] !== null && $s['previous_marks'] !== '';
		}));
		$marksPending = $totalStudents - $marksEntered;
		$completionPercentage = $totalStudents > 0 ? round(($marksEntered / $totalStudents) * 100) : 0;

		$enteredMarks = array_filter(array_column($students, 'previous_marks'), function ($m) {
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
			$insert = $this->pdo->prepare("INSERT INTO marks (studentID, teacherID, subjectID, term, marks, gradeLetter, enteredDate, updatedDate) VALUES (:studentID, :teacherID, :subjectID, :term, :marks, :gradeLetter, NOW(), NOW())");

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

	public function deleteMarks($studentId, $subjectID = null, $term = null)
	{
		// Delete marks for a specific student, subject, and term
		if ($studentId === null || $subjectID === null || $term === null) {
			throw new Exception('Missing studentId, subjectID, or term');
		}

		try {
			$sql = "DELETE FROM marks WHERE studentID = :studentID AND subjectID = :subjectID AND term = :term";
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute([
				'studentID' => intval($studentId),
				'subjectID' => $subjectID,
				'term' => $term
			]);
			return $stmt->rowCount() > 0;
		} catch (Exception $e) {
			throw $e;
		}
	}
}

