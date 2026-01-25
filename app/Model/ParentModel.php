<?php
include_once __DIR__ . '/UserModel.php';
class ParentModel extends UserModel
{
    private $parentTable = 'parents';

    public function createParent($data)
    {
        $data['role'] = $this->userRoleMap['parent'];
        $this->pdo->beginTransaction();
        try {
            $userId = $this->createUser($data);

            $sql = "INSERT INTO $this->parentTable (userID, relationshipType, studentID, nic) VALUES (:userId, :relationshipType, :studentId, :nic)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'userId' => $userId,
                'relationshipType' => $data['relationshipType'],
                'studentId' => $data['studentId'],
                'nic' => $data['nic']
            ]);
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new Exception("Error Processing Request to parent table: " . $e->getMessage());
        }
    }

    public function getParentByUserId($userId)
    {
        $sql = "SELECT p.*, u.* FROM {$this->parentTable} p JOIN {$this->userTable} u ON p.userID = u.userID WHERE p.userID = :userId";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['userId' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching parent by user ID: " . $e->getMessage());
        }
    }

    /**
     * Get student information for a parent's child
     * @param int $userId - The parent's user ID
     * @return array|null - Student information including name, class, and class teacher
     */
    public function getStudentInfoByParentUserId($userId)
    {
        $sql = "SELECT 
                    s.studentID,
                    un.firstName AS student_firstName,
                    un.lastName AS student_lastName,
                    c.grade,
                    c.class,
                    c.classID,
                    ct_un.firstName AS teacher_firstName,
                    ct_un.lastName AS teacher_lastName
                FROM {$this->parentTable} p
                JOIN students s ON p.studentID = s.studentID
                JOIN {$this->userNameTable} un ON s.userID = un.userID
                LEFT JOIN class c ON s.classID = c.classID
                LEFT JOIN teachers ct ON s.classID = ct.classID
                LEFT JOIN {$this->userNameTable} ct_un ON ct.userID = ct_un.userID
                WHERE p.userID = :userId
                LIMIT 1";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['userId' => $userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Format the data for the view
                return [
                    'student_name' => trim(($result['student_firstName'] ?? '') . ' ' . ($result['student_lastName'] ?? '')),
                    'class' => 'Grade ' . ($result['grade'] ?? 'N/A') . '-' . ($result['class'] ?? 'N/A'),
                    'class_teacher' => $result['teacher_firstName'] && $result['teacher_lastName']
                        ? trim(($result['teacher_firstName'] ?? '') . ' ' . ($result['teacher_lastName'] ?? ''))
                        : 'N/A',
                    'classID' => $result['classID'] ?? null,
                    'studentID' => $result['studentID'] ?? null
                ];
            }

            return null;
        } catch (PDOException $e) {
            throw new Exception("Error fetching student info by parent user ID: " . $e->getMessage());
        }
    }

    /**
     * Get all teachers for a student's class
     * Flow: parentUserID -> parentID -> studentID -> classID -> classTimetable -> teachers
     * @param int $classID - The class ID
     * @return array - Array of teachers with their contact information
     */
    public function getTeachersForClass($classID)
    {
        // Query follows the relationship:
        // classTimetable has teacherID for specific class
        // Join with teachers table to get teacher details
        // Join with user tables to get contact information
        // Join with subject to get subject name
        $sql = "SELECT DISTINCT
                    t.teacherID,
                    t.classID,
                    un.firstName,
                    un.lastName,
                    sub.subjectName,
                    u.email,
                    u.phone
                FROM classTimetable ct
                JOIN teachers t ON ct.teacherID = t.teacherID
                JOIN {$this->userTable} u ON t.userID = u.userID
                JOIN {$this->userNameTable} un ON t.userID = un.userID
                LEFT JOIN subject sub ON ct.subjectID = sub.subjectID
                WHERE ct.classID = :classID
                ORDER BY 
                    CASE WHEN t.classID = :classID THEN 0 ELSE 1 END,
                    sub.subjectName ASC";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['classID' => $classID]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $teachers = [];
            $seenTeachers = []; // Track unique teacher-subject combinations

            foreach ($results as $row) {
                $teacherKey = $row['teacherID'] . '_' . ($row['subjectName'] ?? 'N/A');

                // Skip if we've already added this teacher-subject combination
                if (isset($seenTeachers[$teacherKey])) {
                    continue;
                }

                $seenTeachers[$teacherKey] = true;

                $teachers[] = [
                    'name' => trim(($row['firstName'] ?? '') . ' ' . ($row['lastName'] ?? '')),
                    'subject' => $row['subjectName'] ?? 'N/A',
                    'email' => $row['email'] ?? '',
                    'phone' => $row['phone'] ?? 'N/A',
                    'is_class_teacher' => ((int) $row['classID'] === (int) $classID)
                ];
            }

            return $teachers;
        } catch (PDOException $e) {
            throw new Exception("Error fetching teachers for class: " . $e->getMessage());
        }
    }
}