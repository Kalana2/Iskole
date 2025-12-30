<?php

require_once __DIR__ . '/../Core/Database.php';

class ReliefModel
{
	protected PDO $pdo;

	public function __construct()
	{
		$this->pdo = Database::getInstance();
	}

	private function tableExists(string $tableName): bool
	{
		$stmt = $this->pdo->prepare("SHOW TABLES LIKE ?");
		$stmt->execute([$tableName]);
		return (bool) $stmt->fetchColumn();
	}

	private function normalizeDate(string $date): string
	{
		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
			throw new InvalidArgumentException('Invalid date format. Expected YYYY-MM-DD.');
		}
		return $date;
	}

	private function dayOfWeekFromDate(string $date): string
	{
		$ts = strtotime($date);
		if ($ts === false) {
			throw new InvalidArgumentException('Invalid date value.');
		}
		return date('l', $ts); // Monday..Sunday
	}

	private function dayIdFromDayOfWeek(string $dayOfWeek): int
	{
		$map = [
			'Monday' => 1,
			'Tuesday' => 2,
			'Wednesday' => 3,
			'Thursday' => 4,
			'Friday' => 5,
			'Saturday' => 6,
			'Sunday' => 7,
		];
		return $map[$dayOfWeek] ?? 0;
	}

	/**
	 * Returns teacherIDs marked absent/leave for the date.
	 * Uses the existing codebase table/columns: teacherAttendance(attendance_date, status).
	 */
	public function getAbsentTeacherIds(string $date): array
	{
		$date = $this->normalizeDate($date);

		if (!$this->tableExists('teacherAttendance')) {
			return [];
		}

		$sql = "SELECT DISTINCT teacherID
				FROM teacherAttendance
				WHERE attendance_date = ?
				  AND LOWER(status) IN ('absent','leave')";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$date]);
		$rows = $stmt->fetchAll(PDO::FETCH_COLUMN);

		return array_values(array_map('intval', $rows ?: []));
	}

	/**
	 * Teachers eligible to be assigned as relief on a given date.
	 * - Active users only
	 * - Excludes teachers marked absent/leave on that date
	 */
	public function getAvailableReliefTeachers(string $date): array
	{
		$date = $this->normalizeDate($date);

		$attendanceJoin = $this->tableExists('teacherAttendance')
			? "LEFT JOIN teacherAttendance ta ON ta.teacherID = t.teacherID AND ta.attendance_date = :date"
			: "LEFT JOIN (SELECT NULL) ta ON 1=0";

		$sql = "SELECT
					t.teacherID as teacherID,
					CONCAT(COALESCE(un.firstName,''), ' ', COALESCE(un.lastName,'')) as teacherName
				FROM teachers t
				JOIN user u ON u.userID = t.userID
				LEFT JOIN userName un ON un.userID = u.userID
				$attendanceJoin
				WHERE u.active = 1
				  AND COALESCE(LOWER(ta.status), 'present') NOT IN ('absent','leave')
				ORDER BY un.firstName, un.lastName";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([':date' => $date]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
	}

	/**
	 * Computes timetable slots that are uncovered because the assigned teacher is absent/leave.
	 * Supports either:
	 *  - classTimetable(id, teacherID, dayID, periodID, classID, subjectID)
	 *  - timetable(timetableID, classID, subjectID, teacherID, dayOfWeek, period)
	 */
	public function getPendingReliefSlots(string $date): array
	{
		$date = $this->normalizeDate($date);
		$absentTeacherIds = $this->getAbsentTeacherIds($date);
		if (empty($absentTeacherIds)) {
			return [];
		}

		$hasAssignments = $this->tableExists('reliefAssignments');
		$hasClassTimetable = $this->tableExists('classTimetable');
		$hasTimetable = $this->tableExists('timetable');

		if (!$hasClassTimetable && !$hasTimetable) {
			return [];
		}

		$placeholders = implode(',', array_fill(0, count($absentTeacherIds), '?'));
		$dayOfWeek = $this->dayOfWeekFromDate($date);
		$dayId = $this->dayIdFromDayOfWeek($dayOfWeek);

		if ($hasClassTimetable) {
			$assignmentJoin = $hasAssignments
				? "LEFT JOIN reliefAssignments ra ON ra.timetableID = ct.id AND ra.reliefDate = ?"
				: "";

			$sql = "SELECT
						ct.id as timetableID,
						ct.classID as classID,
						CONCAT(c.grade, ' - ', COALESCE(c.section, c.className)) as classLabel,
						ct.dayID as dayID,
						ct.periodID as periodID,
						ct.subjectID as subjectID,
						s.subjectName as subjectName,
						ct.teacherID as absentTeacherID,
						CONCAT(COALESCE(atu.firstName,''), ' ', COALESCE(atu.lastName,'')) as absentTeacherName";

			if ($hasAssignments) {
				$sql .= ",
						ra.reliefTeacherID as reliefTeacherID,
						ra.status as assignmentStatus";
			} else {
				$sql .= ",
						NULL as reliefTeacherID,
						NULL as assignmentStatus";
			}

			$sql .= "
					FROM classTimetable ct
					JOIN classes c ON c.classID = ct.classID
					LEFT JOIN subjects s ON s.subjectID = ct.subjectID
					JOIN teachers at ON at.teacherID = ct.teacherID
					JOIN user au ON au.userID = at.userID
					LEFT JOIN userName atu ON atu.userID = au.userID
					$assignmentJoin
					WHERE ct.dayID = ?
					  AND ct.teacherID IN ($placeholders)
					ORDER BY ct.periodID, c.grade, c.section";

			$params = [];
			if ($hasAssignments) {
				$params[] = $date;
			}
			$params[] = $dayId;
			foreach ($absentTeacherIds as $id) {
				$params[] = $id;
			}

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute($params);
			return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
		}

		// Fallback to `timetable` schema
		$assignmentJoin = $hasAssignments
			? "LEFT JOIN reliefAssignments ra ON ra.timetableID = tt.timetableID AND ra.reliefDate = ?"
			: "";

		$sql = "SELECT
					tt.timetableID as timetableID,
					tt.classID as classID,
					CONCAT(c.grade, ' - ', COALESCE(c.section, c.className)) as classLabel,
					tt.dayOfWeek as dayOfWeek,
					tt.period as period,
					tt.subjectID as subjectID,
					s.subjectName as subjectName,
					tt.teacherID as absentTeacherID,
					CONCAT(COALESCE(atu.firstName,''), ' ', COALESCE(atu.lastName,'')) as absentTeacherName";

		if ($hasAssignments) {
			$sql .= ",
					ra.reliefTeacherID as reliefTeacherID,
					ra.status as assignmentStatus";
		} else {
			$sql .= ",
					NULL as reliefTeacherID,
					NULL as assignmentStatus";
		}

		$sql .= "
				FROM timetable tt
				JOIN classes c ON c.classID = tt.classID
				LEFT JOIN subjects s ON s.subjectID = tt.subjectID
				JOIN teachers at ON at.teacherID = tt.teacherID
				JOIN user au ON au.userID = at.userID
				LEFT JOIN userName atu ON atu.userID = au.userID
				$assignmentJoin
				WHERE tt.dayOfWeek = ?
				  AND tt.teacherID IN ($placeholders)
				ORDER BY tt.period, c.grade, c.section";

		$params = [];
		if ($hasAssignments) {
			$params[] = $date;
		}
		$params[] = $dayOfWeek;
		foreach ($absentTeacherIds as $id) {
			$params[] = $id;
		}

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);
		return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
	}

	private function getTimetableSlotById(int $timetableId): ?array
	{
		$hasClassTimetable = $this->tableExists('classTimetable');
		$hasTimetable = $this->tableExists('timetable');

		if ($hasClassTimetable) {
			$stmt = $this->pdo->prepare("SELECT id as timetableID, dayID as dayID, periodID as periodID FROM classTimetable WHERE id = ?");
			$stmt->execute([$timetableId]);
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $row ?: null;
		}

		if ($hasTimetable) {
			$stmt = $this->pdo->prepare("SELECT timetableID as timetableID, dayOfWeek as dayOfWeek, period as periodID FROM timetable WHERE timetableID = ?");
			$stmt->execute([$timetableId]);
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if (!$row) {
				return null;
			}

			$row['dayID'] = $this->dayIdFromDayOfWeek($row['dayOfWeek']);
			return $row;
		}

		return null;
	}

	/**
	 * Upsert relief assignments.
	 * Expected $assignments entries:
	 *  - timetableID (int)
	 *  - reliefTeacherID (int)
	 *  - reliefDate (YYYY-MM-DD)
	 *  - optional reliefID
	 *  - optional status
	 */
	public function saveReliefAssignments(array $assignments, int $createdByUserId): array
	{
		if (!$this->tableExists('reliefAssignments')) {
			throw new RuntimeException('reliefAssignments table not found. Create it before assigning relief sessions.');
		}

		$results = [];
		foreach ($assignments as $assignment) {
			$timetableId = isset($assignment['timetableID']) ? (int) $assignment['timetableID'] : 0;
			$reliefTeacherId = isset($assignment['reliefTeacherID']) ? (int) $assignment['reliefTeacherID'] : 0;
			$reliefDate = isset($assignment['reliefDate']) ? $this->normalizeDate((string) $assignment['reliefDate']) : null;
			$reliefId = isset($assignment['reliefID']) ? (int) $assignment['reliefID'] : null;
			$status = isset($assignment['status']) ? (string) $assignment['status'] : 'assigned';

			if ($timetableId <= 0 || $reliefTeacherId <= 0 || !$reliefDate) {
				$results[] = ['timetableID' => $timetableId, 'ok' => false, 'message' => 'Invalid assignment payload'];
				continue;
			}

			$slot = $this->getTimetableSlotById($timetableId);
			if (!$slot) {
				$results[] = ['timetableID' => $timetableId, 'ok' => false, 'message' => 'Timetable slot not found'];
				continue;
			}

			$dayId = isset($slot['dayID']) ? (int) $slot['dayID'] : 0;
			$periodId = isset($slot['periodID']) ? (int) $slot['periodID'] : 0;

			// Check existing assignment
			$check = $this->pdo->prepare("SELECT reliefAssignmentID FROM reliefAssignments WHERE timetableID = ? AND reliefDate = ? LIMIT 1");
			$check->execute([$timetableId, $reliefDate]);
			$existingId = $check->fetchColumn();

			if ($existingId) {
				$upd = $this->pdo->prepare(
					"UPDATE reliefAssignments
					 SET reliefID = :reliefID,
						 reliefTeacherID = :reliefTeacherID,
						 dayID = :dayID,
						 periodID = :periodID,
						 status = :status,
						 updatedAt = NOW(),
						 createdBy = :createdBy
					 WHERE reliefAssignmentID = :id"
				);
				$ok = $upd->execute([
					':reliefID' => $reliefId,
					':reliefTeacherID' => $reliefTeacherId,
					':dayID' => $dayId,
					':periodID' => $periodId,
					':status' => $status,
					':createdBy' => $createdByUserId,
					':id' => (int) $existingId,
				]);
			} else {
				$ins = $this->pdo->prepare(
					"INSERT INTO reliefAssignments
						(reliefID, timetableID, reliefTeacherID, reliefDate, dayID, periodID, status, createdBy, createdAt, updatedAt)
					 VALUES
						(:reliefID, :timetableID, :reliefTeacherID, :reliefDate, :dayID, :periodID, :status, :createdBy, NOW(), NOW())"
				);
				$ok = $ins->execute([
					':reliefID' => $reliefId,
					':timetableID' => $timetableId,
					':reliefTeacherID' => $reliefTeacherId,
					':reliefDate' => $reliefDate,
					':dayID' => $dayId,
					':periodID' => $periodId,
					':status' => $status,
					':createdBy' => $createdByUserId,
				]);
			}

			$results[] = ['timetableID' => $timetableId, 'ok' => (bool) $ok];
		}

		return $results;
	}
}