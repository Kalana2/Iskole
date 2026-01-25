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

    /**
     * Get studentID from userID by joining user and students table
     */
    public function getStudentIDByUserID($userID)
    {
        try {
            $sql = "SELECT s.studentID FROM students s 
                    JOIN user u ON s.userID = u.userID 
                    WHERE u.userID = ? AND u.active = 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userID]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['studentID'] : null;
        } catch (Exception $e) {
            error_log("Error fetching studentID: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get student info including name, class, and registration number
     */
    public function getStudentInfo($studentID)
    {
        try {
            $sql = "SELECT 
                        s.studentID,
                        CONCAT(COALESCE(un.firstName, ''), ' ', COALESCE(un.lastName, '')) as name,
                        CONCAT('Grade ', c.grade, '-', c.class) as class,
                        CONCAT('STU', YEAR(CURDATE()), '-', s.studentID) as reg_no,
                        YEAR(CURDATE()) as year
                    FROM students s
                    JOIN user u ON s.userID = u.userID
                    LEFT JOIN userName un ON u.userID = un.userID
                    LEFT JOIN class c ON s.classID = c.classID
                    WHERE s.studentID = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$studentID]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error fetching student info: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get attendance statistics for a student from January 1st to today
     */
    public function getStudentAttendanceStats($studentID)
    {
        try {
            $currentYear = date('Y');
            $startDate = $currentYear . '-01-01';
            $today = date('Y-m-d');

            $sql = "SELECT 
                        COUNT(*) as total_days,
                        SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present_days,
                        SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent_days
                    FROM " . $this->table . "
                    WHERE studentID = ? 
                    AND attendance_date >= ? 
                    AND attendance_date <= ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$studentID, $startDate, $today]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $totalDays = (int)$result['total_days'];
            $presentDays = (int)$result['present_days'];
            $absentDays = (int)$result['absent_days'];

            $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;

            // Get this month's rate
            $thisMonthStart = date('Y-m-01');
            $thisMonthRate = $this->getMonthlyAttendanceRate($studentID, $thisMonthStart, $today);

            // Get last month's rate
            $lastMonthStart = date('Y-m-01', strtotime('first day of last month'));
            $lastMonthEnd = date('Y-m-t', strtotime('last day of last month'));
            $lastMonthRate = $this->getMonthlyAttendanceRate($studentID, $lastMonthStart, $lastMonthEnd);

            return [
                'total_days' => $totalDays,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'attendance_rate' => $attendanceRate,
                'this_month_rate' => $thisMonthRate,
                'last_month_rate' => $lastMonthRate
            ];
        } catch (Exception $e) {
            error_log("Error fetching student attendance stats: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get attendance rate for a specific month period
     */
    private function getMonthlyAttendanceRate($studentID, $startDate, $endDate)
    {
        try {
            $sql = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present
                    FROM " . $this->table . "
                    WHERE studentID = ? 
                    AND attendance_date >= ? 
                    AND attendance_date <= ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$studentID, $startDate, $endDate]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $total = (int)$result['total'];
            $present = (int)$result['present'];

            return $total > 0 ? round(($present / $total) * 100, 2) : 0;
        } catch (Exception $e) {
            error_log("Error calculating monthly attendance rate: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get monthly attendance data from January 1st to current date
     * Returns data for each month showing present and absent counts
     */
    public function getMonthlyAttendanceData($studentID)
    {
        try {
            $currentYear = date('Y');
            $currentMonth = (int)date('n');
            $today = date('Y-m-d');
            $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            $monthlyData = [];

            for ($month = 1; $month <= 12; $month++) {
                $monthStart = sprintf('%s-%02d-01', $currentYear, $month);
                $monthEnd = date('Y-m-t', strtotime($monthStart));

                // For future months, set all counts to 0
                if ($month > $currentMonth) {
                    $monthlyData[] = [
                        'month' => $monthNames[$month - 1],
                        'present' => 0,
                        'absent' => 0,
                        'total' => 0
                    ];
                    continue;
                }

                // For current month, limit to today's date
                if ($month == $currentMonth) {
                    $monthEnd = $today;
                }

                $sql = "SELECT 
                            SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present,
                            SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent,
                            COUNT(*) as total
                        FROM " . $this->table . "
                        WHERE studentID = ? 
                        AND attendance_date >= ? 
                        AND attendance_date <= ?";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$studentID, $monthStart, $monthEnd]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                $monthlyData[] = [
                    'month' => $monthNames[$month - 1],
                    'present' => (int)$result['present'],
                    'absent' => (int)$result['absent'],
                    'total' => (int)$result['total']
                ];
            }

            return $monthlyData;
        } catch (Exception $e) {
            error_log("Error fetching monthly attendance data: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get studentID from parent's userID by joining user and parents table
     * Parents table has a direct studentID column linking to their child
     */
    public function getStudentIDByParentUserID($userID)
    {
        try {
            $sql = "SELECT p.studentID FROM parents p 
                    JOIN user u ON p.userID = u.userID 
                    WHERE u.userID = ? AND u.active = 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userID]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['studentID'] : null;
        } catch (Exception $e) {
            error_log("Error fetching studentID from parent: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get complete attendance context for student view
     * This combines all necessary data for the studentAttendance.php template
     * Supports both student (role=3) and parent (role=4) users
     * 
     * @param int $userID The logged-in user's ID
     * @param int $userRole The user's role (3=student, 4=parent)
     * @return array Contains studentInfo, attendanceStats, and monthlyData
     */
    public function getStudentAttendanceContext($userID, $userRole = 3)
    {
        try {
            // Get studentID based on user role
            if ($userRole == 4) {
                // Parent role - get studentID from parents table
                $studentID = $this->getStudentIDByParentUserID($userID);
                if (!$studentID) {
                    throw new Exception("Student not found for parent user ID: " . $userID);
                }
            } else {
                // Student role (default) - get studentID from students table
                $studentID = $this->getStudentIDByUserID($userID);
                if (!$studentID) {
                    throw new Exception("Student not found for user ID: " . $userID);
                }
            }

            // Get student info
            $studentInfo = $this->getStudentInfo($studentID);
            if (!$studentInfo) {
                throw new Exception("Could not fetch student information");
            }

            // Get attendance statistics
            $attendanceStats = $this->getStudentAttendanceStats($studentID);

            // Get monthly attendance data
            $monthlyData = $this->getMonthlyAttendanceData($studentID);

            return [
                'studentInfo' => $studentInfo,
                'attendanceStats' => $attendanceStats,
                'monthlyData' => $monthlyData
            ];
        } catch (Exception $e) {
            error_log("Error building student attendance context: " . $e->getMessage());
            throw $e;
        }
    }
}
