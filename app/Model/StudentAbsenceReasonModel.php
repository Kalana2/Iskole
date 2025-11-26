<?php

class StudentAbsenceReasonModel
{
    protected $pdo;
    private $table = "absentReasons";

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }
    public function submitAbsenceReason($data)
    {
        try {
            $sql = "INSERT INTO " . $this->table . " (parentID, reason, fromDate, toDate)
                    VALUES (:parentId, :reason, :fromDate, :toDate)";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                'parentId' => $data['parentId'],
                'reason' => $data['reason'],
                'fromDate' => $data['fromDate'],
                'toDate' => $data['toDate'],
            ]);
            return $result;
        } catch (PDOException $e) {
            var_dump($e->errorInfo);
            error_log('Failed to submit absence reason: ' . $e->getMessage());
            return false;
        }
    }
    public function deleteAbsenceReason($reasonId)
    {
        try {
            $stmt = $this->pdo->prepare(
                "DELETE FROM " . $this->table . " WHERE reasonID = :reasonId"
            );
            $result = $stmt->execute([
                'reasonId' => $reasonId,
            ]);
            return $result;
        } catch (PDOException $e) {
            error_log('Failed to delete absence reason: ' . $e->getMessage());
            return false;
        }
    }
    public function updateAbsenceReason($data)
    {
        try {
            $stmt = $this->pdo->prepare(
                "UPDATE " . $this->table . " 
                 SET reason = :reason, fromDate = :fromDate, toDate = :toDate
                 WHERE reasonID = :reasonId AND status = 'pending'"
            );
            $result = $stmt->execute([
                'reasonId' => $data['reasonId'],
                'reason' => $data['reason'],
                'fromDate' => $data['fromDate'],
                'toDate' => $data['toDate'],
            ]);
            return $result;
        } catch (PDOException $e) {
            error_log('Failed to update absence reason: ' . $e->getMessage());
            return false;
        }
    }
    public function getAbsenceReasonsByParentId($parentId)
    {
        try {
            $sql = "SELECT ar.*, 
                        ar.reasonID,
                        ar.fromDate,
                        ar.toDate,
                        ar.reason,
                        ar.submittedAt,
                        ar.acknowledgedBy,
                        ar.acknowledgedDate
                 FROM " . $this->table . " ar
                 WHERE ar.parentID = :parentId
                 ORDER BY ar.submittedAt DESC, ar.reasonID DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['parentId' => $parentId]);


            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($data as &$row) {
                $today = (new DateTime())->setTime(0, 0, 0);
                $fromDate = !empty($row['fromDate']) ? (new DateTime($row['fromDate']))->setTime(0, 0, 0) : null;

                if (!empty($row['acknowledgedBy']) || !empty($row['acknowledgedDate'])) {
                    $row['Status'] = 'acknowledged';
                } else {
                    $row['Status'] = 'pending';
                }
                $toDate = !empty($row['toDate']) ? (new DateTime($row['toDate']))->setTime(0, 0, 0) : null;
                $diff = $fromDate->diff($toDate);
                // days difference (inclusive)
                $row['duration'] = (int) $diff->format('%a') + 1;
            }
            unset($row);

            return $data;
        } catch (PDOException $e) {
            var_dump("Failed to fetch absence reasons" . $e->getMessage());
            error_log('Failed to fetch absence reasons: ' . $e->getMessage());
            return [];
        }
    }
    public function getAllAbsenceReasons()
    {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT * FROM " . $this->table . " ORDER BY submittedDate DESC, reasonID DESC"
            );
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            var_dump("Failed to fetch all absence reasons: " . $e->getMessage());
            error_log('Failed to fetch all absence reasons: ' . $e->getMessage());
            return [];
        }
    }

    public function getAbsenceReasonsByClass($grade, $classId)
    {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT ar.* FROM " . $this->table . " ar
                 JOIN parents p ON ar.parentID = p.parentID
                 JOIN students s ON p.studentID = s.studentID
                 WHERE s.gradeID = :grade AND s.classID = :classId
                 ORDER BY ar.submittedDate DESC, ar.reasonID DESC"
            );
            $stmt->execute([
                'grade' => $grade,
                'classId' => $classId
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            var_dump("Failed to fetch absence reasons by class: " . $e->getMessage());
            error_log('Failed to fetch absence reasons by class: ' . $e->getMessage());
            return [];
        }
    }
}