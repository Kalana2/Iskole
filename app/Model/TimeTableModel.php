<?php
class TimeTableModel
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

	public function getClassesByGrade($grade)
	{
		$sql = "SELECT id, class FROM `class` WHERE grade = :grade ORDER BY class";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['grade' => $grade]);
		return $stmt->fetchAll();
	}
}