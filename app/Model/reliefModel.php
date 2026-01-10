<?php
require_once __DIR__ . '/../Core/Database.php';

class reliefModel
{
	private PDO $pdo;

	public function __construct()
	{
		$this->pdo = Database::getInstance();
	}

	public function getPresentTeacherCount(string $date): int
	{
		$sql = "SELECT COUNT(DISTINCT ta.teacherID) AS cnt
				FROM teacherAttendance ta
				WHERE ta.attendance_date = ? AND ta.status = 'present'";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$date]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return (int)($row['cnt'] ?? 0);
	}

	/**
	 * Returns timetable slots for teachers marked absent/leave on the given date,
	 * along with a list of present+free teachers for that period.
	 */
	public function getPendingReliefSlots(string $date): array
	{
		$dayId = $this->dateToDayId($date);

		$sql = "SELECT
					ct.id AS timetableID,
					ct.teacherID AS absentTeacherID,
					ct.dayID,
					ct.periodID,
					ct.classID,
					ct.subjectID,
					c.grade,
					c.class AS section,
					s.subjectName,
					CONCAT(un.firstName, ' ', un.lastName) AS absentTeacherName
				FROM classTimetable ct
				JOIN teacherAttendance ta
					ON ta.teacherID = ct.teacherID
					AND ta.attendance_date = ?
					AND ta.status IN ('absent', 'leave')
				LEFT JOIN class c ON c.classID = ct.classID
				LEFT JOIN subject s ON s.subjectID = ct.subjectID
				LEFT JOIN teachers t ON t.teacherID = ct.teacherID
				LEFT JOIN user u ON u.userID = t.userID
				LEFT JOIN userName un ON un.userID = u.userID
				WHERE ct.dayID = ?
				  AND NOT EXISTS (
					  SELECT 1
					  FROM reliefAssignments ra
					  WHERE ra.timetableID = ct.id
						AND ra.reliefDate = ?
						AND (ra.status IS NULL OR ra.status <> 'cancelled')
				  )
				ORDER BY ct.periodID ASC, c.grade ASC, c.class ASC";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$date, $dayId, $date]);
		$slots = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

		foreach ($slots as &$slot) {
			$slot['availableTeachers'] = $this->getAvailableTeachersForPeriod(
				$date,
				(int)$slot['dayID'],
				(int)$slot['periodID'],
				(int)$slot['absentTeacherID']
			);
		}

		return $slots;
	}

	public function getAvailableTeachersForPeriod(string $date, int $dayId, int $periodId, int $excludeTeacherId): array
	{
		$sql = "SELECT
					t.teacherID,
					CONCAT(un.firstName, ' ', un.lastName) AS name,
					s.subjectName
				FROM teachers t
				JOIN user u ON u.userID = t.userID
				LEFT JOIN userName un ON un.userID = u.userID
				LEFT JOIN subject s ON s.subjectID = t.subjectID
				JOIN teacherAttendance ta
					ON ta.teacherID = t.teacherID
					AND ta.attendance_date = ?
					AND ta.status = 'present'
				WHERE u.active = 1
				  AND t.teacherID <> ?
				  AND NOT EXISTS (
					  SELECT 1
					  FROM classTimetable ct2
					  WHERE ct2.teacherID = t.teacherID
						AND ct2.dayID = ?
						AND ct2.periodID = ?
				  )
				  AND NOT EXISTS (
					  SELECT 1
					  FROM reliefAssignments ra2
					  WHERE ra2.reliefTeacherID = t.teacherID
						AND ra2.reliefDate = ?
						AND ra2.dayID = ?
						AND ra2.periodID = ?
						AND (ra2.status IS NULL OR ra2.status <> 'cancelled')
				  )
				ORDER BY un.firstName, un.lastName";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$date, $excludeTeacherId, $dayId, $periodId, $date, $dayId, $periodId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
	}

	public function isTeacherFree(string $date, int $teacherId, int $dayId, int $periodId): bool
	{
		// Must be present
		$stmt = $this->pdo->prepare("SELECT 1 FROM teacherAttendance WHERE teacherID = ? AND attendance_date = ? AND status = 'present' LIMIT 1");
		$stmt->execute([$teacherId, $date]);
		if (!$stmt->fetchColumn()) {
			return false;
		}

		// Not teaching a class that period
		$stmt = $this->pdo->prepare("SELECT 1 FROM classTimetable WHERE teacherID = ? AND dayID = ? AND periodID = ? LIMIT 1");
		$stmt->execute([$teacherId, $dayId, $periodId]);
		if ($stmt->fetchColumn()) {
			return false;
		}

		// Not already assigned a relief that period
		$stmt = $this->pdo->prepare("SELECT 1 FROM reliefAssignments WHERE reliefTeacherID = ? AND reliefDate = ? AND dayID = ? AND periodID = ? AND (status IS NULL OR status <> 'cancelled') LIMIT 1");
		$stmt->execute([$teacherId, $date, $dayId, $periodId]);
		if ($stmt->fetchColumn()) {
			return false;
		}

		return true;
	}

	public function createReliefAssignment(int $timetableId, int $reliefTeacherId, string $reliefDate, int $dayId, int $periodId, int $createdBy): bool
	{
		$sql = "INSERT INTO reliefAssignments
					(timetableID, reliefTeacherID, reliefDate, dayID, periodID, status, createdBy, createdAt, updatedAt)
				VALUES
					(?, ?, ?, ?, ?, 'assigned', ?, NOW(), NOW())";
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute([$timetableId, $reliefTeacherId, $reliefDate, $dayId, $periodId, $createdBy]);
	}

	private function dateToDayId(string $date): int
	{
		$ts = strtotime($date);
		if ($ts === false) {
			return (int)date('N');
		}
		return (int)date('N', $ts); // 1=Mon ... 7=Sun
	}
}

