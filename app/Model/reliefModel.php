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
				WHERE ta.attendance_date = ? AND LOWER(ta.status) = 'present'";
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
		$dayIds = $this->getDayIdsForDate($date);
		if (empty($dayIds)) {
			return [];
		}

		$dayPlaceholders = implode(',', array_fill(0, count($dayIds), '?'));

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
					AND LOWER(ta.status) IN ('absent', 'leave')
				LEFT JOIN class c ON c.classID = ct.classID
				LEFT JOIN subject s ON s.subjectID = ct.subjectID
				LEFT JOIN teachers t ON t.teacherID = ct.teacherID
				LEFT JOIN user u ON u.userID = t.userID
				LEFT JOIN userName un ON un.userID = u.userID
				WHERE ct.dayID IN ($dayPlaceholders)
				  AND NOT EXISTS (
					  SELECT 1
					  FROM reliefAssignments ra
					  WHERE ra.timetableID = ct.id
						AND ra.reliefDate = ?
						AND (ra.status IS NULL OR ra.status <> 'cancelled')
				  )
				ORDER BY ct.periodID ASC, c.grade ASC, c.class ASC";

		$stmt = $this->pdo->prepare($sql);
		$params = array_merge([$date], $dayIds, [$date]);
		$stmt->execute($params);
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
					t.subjectID AS teacherSubjectID,
					CONCAT(un.firstName, ' ', un.lastName) AS name,
					s.subjectName
				FROM teachers t
				JOIN user u ON u.userID = t.userID
				LEFT JOIN userName un ON un.userID = u.userID
				LEFT JOIN subject s ON s.subjectID = t.subjectID
				JOIN teacherAttendance ta
					ON ta.teacherID = t.teacherID
					AND ta.attendance_date = ?
					AND LOWER(ta.status) = 'present'
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
		$stmt = $this->pdo->prepare("SELECT 1 FROM teacherAttendance WHERE teacherID = ? AND attendance_date = ? AND LOWER(status) = 'present' LIMIT 1");
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

	/**
	 * Relief assignments for a specific teacher and date.
	 * Used by /teacher/relief.
	 */
	public function getReliefAssignmentsForTeacher(int $teacherId, string $date): array
	{
		$sql = "SELECT
				ra.timetableID,
				ra.reliefTeacherID,
				ra.reliefDate,
				ra.dayID,
				ra.periodID,
				ra.status,
				ct.classID,
				ct.subjectID,
				ct.teacherID AS absentTeacherID,
				c.grade,
				c.class AS section,
				s.subjectName,
				CONCAT(un.firstName, ' ', un.lastName) AS absentTeacherName,
				(
					SELECT COUNT(*)
					FROM students st
					JOIN user u2 ON u2.userID = st.userID
					WHERE st.classID = ct.classID AND u2.active = 1
				) AS studentCount
			FROM reliefAssignments ra
			JOIN classTimetable ct ON ct.id = ra.timetableID
			LEFT JOIN class c ON c.classID = ct.classID
			LEFT JOIN subject s ON s.subjectID = ct.subjectID
			LEFT JOIN teachers tAbs ON tAbs.teacherID = ct.teacherID
			LEFT JOIN user uAbs ON uAbs.userID = tAbs.userID
			LEFT JOIN userName un ON un.userID = uAbs.userID
			WHERE ra.reliefTeacherID = ?
			  AND ra.reliefDate = ?
			  AND (ra.status IS NULL OR ra.status <> 'cancelled')
			ORDER BY ra.periodID ASC";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$teacherId, $date]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
	}

	private function getDayIdsForDate(string $date): array
	{
		$ts = strtotime($date);
		if ($ts === false) {
			$ts = time();
		}

		$isoDayId = (int)date('N', $ts); // 1=Mon ... 7=Sun
		$dayName = date('l', $ts);
		$shortDayName = date('D', $ts);

		if ($this->tableExists('schoolDays')) {
			$stmt = $this->pdo->prepare("SELECT id FROM schoolDays WHERE LOWER(day) IN (?, ?) ORDER BY id");
			$stmt->execute([strtolower($dayName), strtolower($shortDayName)]);
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

			$dayIds = array_map(static fn($r) => (int)($r['id'] ?? 0), $rows);
			$dayIds = array_values(array_filter($dayIds, static fn($v) => $v > 0));

			if (!empty($dayIds)) {
				return $dayIds;
			}
		}

		return [$isoDayId];
	}

	private function tableExists(string $table): bool
	{
		$stmt = $this->pdo->prepare("SHOW TABLES LIKE ?");
		$stmt->execute([$table]);
		return (bool)$stmt->fetchColumn();
	}
}
