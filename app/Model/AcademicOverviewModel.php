<?php

class AcademicOverviewModel
{
	private $pdo;

	public function __construct()
	{
		$this->pdo = Database::getInstance();
	}

	/**
	 * Returns grades for the grade buttons.
	 * Source table: class(classID, grade, class)
	 */
	public function getGrades(): array
	{
		$sql = "SELECT DISTINCT grade AS value, grade AS label FROM class ORDER BY grade";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getClassesByGrade(int $grade): array
	{
		$sql = "SELECT classID, grade, class FROM class WHERE grade = :grade ORDER BY class";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['grade' => $grade]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getSubjects(): array
	{
		$sql = "SELECT subjectID, subjectName FROM subject ORDER BY subjectName";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getSubjectAveragesByClassId(int $classID, ?string $term = null): array
	{
		$params = ['classID' => $classID];
		$termSql = '';
		if ($term !== null && $term !== '') {
			$termSql = ' AND m.term = :term';
			$params['term'] = $term;
		}

		$sql = "
			SELECT
				s.subjectID,
				s.subjectName,
				COALESCE(ROUND(AVG(m.marks), 1), 0) AS averageMarks
			FROM subject s
			LEFT JOIN teachers t
				ON t.subjectID = s.subjectID
				AND t.classID = :classID
			LEFT JOIN marks m
				ON m.teacherID = t.teacherID
				AND m.subjectID = s.subjectID
				$termSql
			GROUP BY s.subjectID, s.subjectName
			ORDER BY s.subjectName
		";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}

