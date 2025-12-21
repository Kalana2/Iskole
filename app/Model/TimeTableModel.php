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

    public function getTeachersBySubject($subjectId)
    {
        $sql = "SELECT t.teacherID, t.userID, COALESCE(CONCAT(un.firstName, ' ', un.lastName), t.nic, CAST(t.userID AS CHAR)) AS teacherName
            FROM teachers t
            LEFT JOIN userName un ON un.userID = t.userID
            WHERE t.subjectID = :subjectId AND t.subjectID IS NOT NULL
            ORDER BY un.firstName, un.lastName";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['subjectId' => $subjectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertSlot($teacherId, $dayId, $periodId, $classId, $subjectId)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO classTimetable (teacherID, dayID, periodID, classID, subjectID) VALUES (?, ?, ?, ?, ?)"
        );

        return $stmt->execute([$teacherId, $dayId, $periodId, $classId, $subjectId]);
    }

    public function updateSlot($id, $subjectId, $teacherId)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE classTimetable SET subjectID = ?, teacherID = ? WHERE id = ?"
        );

        return $stmt->execute([$subjectId, $teacherId, $id]);
    }

    public function deleteSlot($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM classTimetable WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getTimetableByClass($classId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT ct.id, ct.teacherID, ct.dayID, ct.periodID, ct.classID, ct.subjectID,
                s.subjectName, t.nic as teacherNic, COALESCE(CONCAT(un.firstName, ' ', un.lastName), t.nic) AS teacherName
             FROM classTimetable ct
             LEFT JOIN subject s ON s.subjectID = ct.subjectID
             LEFT JOIN teachers t ON t.teacherID = ct.teacherID
             LEFT JOIN userName un ON un.userID = t.userID
             WHERE ct.classID = ?"
        );
        $stmt->execute([$classId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSubjects()
    {
        $stmt = $this->pdo->prepare("SELECT subjectID, subjectName FROM subject ORDER BY subjectName");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTeachersMapping()
    {
        $stmt = $this->pdo->prepare("SELECT t.teacherID, t.userID, t.subjectID, COALESCE(CONCAT(un.firstName, ' ', un.lastName), t.nic, CAST(t.userID AS CHAR)) AS teacherName FROM teachers t LEFT JOIN userName un ON un.userID = t.userID WHERE t.subjectID IS NOT NULL ORDER BY un.firstName, un.lastName");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $map = [];
        foreach ($rows as $r) {
            $sub = (int)($r['subjectID'] ?? 0);
            if (!$sub) continue; // Skip if no subject assigned
            if (!isset($map[$sub])) $map[$sub] = [];
            $map[$sub][] = ['teacherID' => (int)$r['teacherID'], 'name' => (string)$r['teacherName']];
        }
        return $map;
    }
}
