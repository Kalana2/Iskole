<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/TeacherModel.php';

class TeacherAttendance extends TeacherModel
{
    private $table = "teacherAttendance";
    private $teacherID;

    public function __construct()
    {
        parent::__construct(); // Initialize parent class
        try {
            $this->pdo = Database::getInstance();
            if (!$this->pdo) {
                throw new Exception("Database connection failed");
            }
        } catch (Exception $e) {
            error_log("TeacherAttendance model initialization error: " . $e->getMessage());
            throw $e;
        }
    }

    public function recordAttendance($teacherID, $date, $status)
    {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO " . $this->table . " (teacherID, attendance_date, status)
                        SELECT ?, ?, ?
                        WHERE NOT EXISTS (
                            SELECT 1 FROM " . $this->table . "
                            WHERE teacherID = ?
                            AND attendance_date = ?
                        )"
            );
            if (!$stmt) {
                throw new Exception("Prepare statement failed");
            }

            $result = $stmt->execute([$teacherID, $date, $status, $teacherID, $date]);

            if ($result) {
                return true;
            } else {
                throw new Exception("Execute failed");
            }
        } catch (Exception $e) {
            error_log("Error recording attendance: " . $e->getMessage());
            throw $e;
        }
    }

    public function getConnectionStatus()
    {
        return $this->pdo !== null;
    }

    /**
     * Get all teachers with their attendance status for a specific date
     */
    public function getTeachersWithAttendance($date)
    {
        try {
            $sql = "SELECT 
                        t.teacherID as id,
                        CONCAT(un.firstName, ' ', un.lastName) as name,
                        s.subjectName as subject,
                        COALESCE(ta.status, 'not-marked') as status
                    FROM teachers t
                    JOIN user u ON t.userID = u.userID
                    LEFT JOIN userName un ON u.userID = un.userID
                    LEFT JOIN subject s ON t.subjectID = s.subjectID
                    LEFT JOIN " . $this->table . " ta ON t.teacherID = ta.teacherID AND ta.attendance_date = ?
                    WHERE u.active = 1
                    ORDER BY un.firstName, un.lastName";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$date]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error fetching teachers with attendance: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get attendance statistics for a specific date
     */
    public function getAttendanceStats($date)
    {
        try {
            $sql = "SELECT 
                        COUNT(DISTINCT t.teacherID) as total,
                        SUM(CASE WHEN ta.status = 'present' THEN 1 ELSE 0 END) as present,
                        SUM(CASE WHEN ta.status = 'absent' THEN 1 ELSE 0 END) as absent,
                        SUM(CASE WHEN ta.status = 'leave' THEN 1 ELSE 0 END) as on_leave
                    FROM teachers t
                    JOIN user u ON t.userID = u.userID
                    LEFT JOIN " . $this->table . " ta ON t.teacherID = ta.teacherID AND ta.attendance_date = ?
                    WHERE u.active = 1";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$date]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error fetching attendance stats: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update attendance status for a teacher
     */
    public function updateAttendance($teacherID, $date, $status)
    {
        try {
            // Check if record exists
            $checkSql = "SELECT attendanceID FROM " . $this->table . " WHERE teacherID = ? AND attendance_date = ?";
            $checkStmt = $this->pdo->prepare($checkSql);
            $checkStmt->execute([$teacherID, $date]);
            $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                // Update existing record
                $sql = "UPDATE " . $this->table . " SET status = ? WHERE teacherID = ? AND attendance_date = ?";
                $stmt = $this->pdo->prepare($sql);
                return $stmt->execute([$status, $teacherID, $date]);
            } else {
                // Insert new record
                return $this->recordAttendance($teacherID, $date, $status);
            }
        } catch (Exception $e) {
            error_log("Error updating attendance: " . $e->getMessage());
            throw $e;
        }
    }
}
