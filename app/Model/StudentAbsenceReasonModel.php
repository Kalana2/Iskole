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
            // Only allow deletion of non-acknowledged requests
            $stmt = $this->pdo->prepare(
                "DELETE FROM " . $this->table . " 
                 WHERE reasonID = :reasonId 
                 AND acknowledgedBy IS NULL"
            );
            $result = $stmt->execute([
                'reasonId' => $reasonId,
            ]);

            // Check if any rows were actually deleted
            if ($result && $stmt->rowCount() > 0) {
                return true;
            }

            error_log('No rows deleted for reasonId: ' . $reasonId . '. Possibly already acknowledged or invalid ID.');
            return false;
        } catch (PDOException $e) {
            error_log('Failed to delete absence reason: ' . $e->getMessage());
            var_dump('Delete error: ' . $e->getMessage());
            return false;
        }
    }
    public function updateAbsenceReason($data)
    {
        try {
            // Allow updates only for non-acknowledged requests
            $stmt = $this->pdo->prepare(
                "UPDATE " . $this->table . " 
                 SET reason = :reason, fromDate = :fromDate, toDate = :toDate
                 WHERE reasonID = :reasonId 
                 AND acknowledgedBy IS NULL"
            );
            $result = $stmt->execute([
                'reasonId' => $data['reasonId'],
                'reason' => $data['reason'],
                'fromDate' => $data['fromDate'],
                'toDate' => $data['toDate'],
            ]);

            // Check if any rows were actually updated
            if ($result && $stmt->rowCount() > 0) {
                return true;
            }

            // If no rows were updated, log and return false
            error_log('No rows updated for reasonId: ' . $data['reasonId'] . '. Possibly already acknowledged or invalid ID.');
            return false;

        } catch (PDOException $e) {
            error_log('Failed to update absence reason: ' . $e->getMessage());
            var_dump('Update error: ' . $e->getMessage());
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
                "SELECT ar.*, 
                        parent_u.phone as parent_contact,
                        s.gradeID as grade,
                        s.classID as class,
                        s.studentID
                 FROM " . $this->table . " ar
                 JOIN parents p ON ar.parentID = p.parentID
                 JOIN students s ON p.studentID = s.studentID
                 JOIN user parent_u ON p.userID = parent_u.userID
                 WHERE s.gradeID = :grade AND s.classID = :classId
                 ORDER BY ar.submittedAt DESC, ar.reasonID DESC"
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

    public function acknowledgeAbsenceReason($reasonId, $acknowledgedBy)
    {
        try {
            $stmt = $this->pdo->prepare(
                "UPDATE " . $this->table . " 
                 SET acknowledgedBy = :acknowledgedBy, 
                     acknowledgedDate = NOW()
                 WHERE reasonID = :reasonId 
                 AND acknowledgedBy IS NULL"
            );
            $result = $stmt->execute([
                'reasonId' => $reasonId,
                'acknowledgedBy' => $acknowledgedBy
            ]);

            // Check if any rows were actually updated
            if ($result && $stmt->rowCount() > 0) {
                return true;
            }

            error_log('No rows updated for reasonId: ' . $reasonId . '. Possibly already acknowledged.');
            return false;
        } catch (PDOException $e) {
            error_log('Failed to acknowledge absence reason: ' . $e->getMessage());
            return false;
        }
    }
}