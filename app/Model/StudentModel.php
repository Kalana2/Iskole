<?php
require_once __DIR__ . '/UserModel.php';
class StudentModel extends UserModel
{
    private $studentTable = 'students';

    public function createStudent($data)
    {
        $this->pdo->beginTransaction();
        try {
            $userId = $this->createUser($data);

            $sql = "INSERT INTO $this->studentTable (userID, gradeID, classID) VALUES (:userId, :grade, :classId)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'userId' => $userId,
                'grade' => $data['grade'],
                'classId' => $data['classId']
            ]);
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new Exception("Error Processing Request to student table: " . $e->getMessage());
        }
    }

    public function getStudentGradeClass($userId)
    {
        $sql = "SELECT c.grade, c.class
                FROM students AS st
                JOIN class AS c ON c.classID = st.classID
                WHERE st.userID = :userId";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['userId' => $userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            throw new Exception("Error fetching student grade: " . $e->getMessage());
        }
    }

    public function getStudentById($studentId)
    {
        $sql = "SELECT s.*, u.*, un.* FROM {$this->studentTable} s
            JOIN {$this->userTable} u ON s.userID = u.userID
            LEFT JOIN {$this->userNameTable} un ON u.userID = un.userID
            WHERE s.studentID = :studentId";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['studentId' => $studentId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching student by ID: " . $e->getMessage());
        }
    }



    public function getStudentsByGrade($grade)
    {
        $sql = "SELECT 
                s.studentID,
                un.firstName,
                un.lastName
            FROM students s
            JOIN userName un ON s.userID = un.userID
            WHERE s.gradeID = :grade
            ORDER BY un.firstName ASC";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['grade' => $grade]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching students by grade: " . $e->getMessage());
        }
    }



    public function getStudentsByClassId($classId)
    {
        $sql = "SELECT s.studentID, un.firstName, un.lastName
            FROM students s
            LEFT JOIN userName un ON un.userID = s.userID
            WHERE s.classID = :classId
            ORDER BY un.firstName ASC, un.lastName ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':classId' => (int)$classId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
