<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/StudentModel.php';

class StudentAttendance extends StudentModel
{
    private $table = "studentAttendance";
    private $studentID;

    public function __construct()
    {
        parent::__construct(); // Initialize parent class
        try {
            $this->pdo = Database::getInstance();
            if (!$this->pdo) {
                throw new Exception("Database connection failed");
            }
        } catch (Exception $e) {
            error_log("StudentAttendance model initialization error: " . $e->getMessage());
            throw $e;
        }
    }

    public function recordAttendance($studentID, $date, $status)
    {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO " . $this->table . " (studentID, attendance_date, status)
                 VALUES (?, ?, ?)"
            );
            if (!$stmt) {
                throw new Exception("Prepare statement failed");
            }

            $result = $stmt->execute([$studentID, $date, $status]);

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
     * Get all students with their attendance status for a specific class and date
     */
    public function getStudentsWithAttendance($classID, $date)
    {
        try {
            $sql = "SELECT 
                        s.studentID as id,
                        s.userID as reg_number,
                        CASE 
                            WHEN un.firstName IS NULL AND un.lastName IS NULL THEN 'Unknown'
                            ELSE CONCAT(COALESCE(un.firstName, ''), ' ', COALESCE(un.lastName, ''))
                        END as name,
                        COALESCE(a.status, 'not-marked') as status
                    FROM students s
                    JOIN user u ON s.userID = u.userID
                    LEFT JOIN userName un ON u.userID = un.userID
                    LEFT JOIN " . $this->table . " a ON s.studentID = a.studentID AND a.attendance_date = ?
                    WHERE s.classID = ? AND u.active = 1
                    ORDER BY un.firstName, un.lastName";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$date, $classID]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error fetching students with attendance: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get attendance statistics for a specific class and date
     */
    public function getAttendanceStats($classID, $date)
    {
        try {
            $sql = "SELECT 
                        COUNT(DISTINCT s.studentID) as total,
                        SUM(CASE WHEN a.status = 'Present' THEN 1 ELSE 0 END) as present,
                        SUM(CASE WHEN a.status = 'Absent' THEN 1 ELSE 0 END) as absent,
                        SUM(CASE WHEN a.status = 'Late' THEN 1 ELSE 0 END) as late,
                        SUM(CASE WHEN a.status = 'Excused' THEN 1 ELSE 0 END) as excused
                    FROM students s
                    JOIN user u ON s.userID = u.userID
                    LEFT JOIN " . $this->table . " a ON s.studentID = a.studentID AND a.attendance_date = ?
                    WHERE s.classID = ? AND u.active = 1";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$date, $classID]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error fetching attendance stats: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update attendance status for a student
     */
    public function updateAttendance($studentID, $classID, $date, $status, $markedBy = null)
    {
        try {
            // Check if record exists
            $checkSql = "SELECT attendanceID FROM " . $this->table . " WHERE studentID = ? AND attendance_date = ?";
            $checkStmt = $this->pdo->prepare($checkSql);
            $checkStmt->execute([$studentID, $date]);
            $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                // Update existing record
                $sql = "UPDATE " . $this->table . " SET status = ? WHERE studentID = ? AND attendance_date = ?";

                $stmt = $this->pdo->prepare($sql);
                return $stmt->execute([$status, $studentID, $date]);
            } else {
                // Insert new record
                return $this->recordAttendance($studentID, $date, $status);
            }
        } catch (Exception $e) {
            error_log("Error updating attendance: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get attendance rate for a student
     */
    public function getStudentAttendanceRate($studentID)
    {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_days,
                        SUM(CASE WHEN status = 'Present' OR status = 'Late' THEN 1 ELSE 0 END) as present_days
                    FROM " . $this->table . "
                    WHERE studentID = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$studentID]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && $result['total_days'] > 0) {
                return round(($result['present_days'] / $result['total_days']) * 100);
            }
            return 0;
        } catch (Exception $e) {
            error_log("Error fetching student attendance rate: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all grades available
     */
    public function getGrades()
    {
        try {
            $sql = "SELECT DISTINCT grade FROM class ORDER BY grade";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            error_log("Error fetching grades: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get classes for a specific grade
     */
    public function getClassesByGrade($grade)
    {
        try {
            $sql = "SELECT classID, class as section FROM class WHERE grade = ? ORDER BY class";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$grade]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error fetching classes: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get class ID by grade and section
     */
    public function getClassID($grade, $section)
    {
        try {
            $sql = "SELECT classID FROM class WHERE grade = ? AND class = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$grade, $section]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['classID'] : null;
        } catch (Exception $e) {
            error_log("Error fetching class ID: " . $e->getMessage());
            throw $e;
        }
    }
}
