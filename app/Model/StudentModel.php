<?php
require_once __DIR__ . '/UserModel.php';
class StudentModel extends UserModel
{
    private $studentTable = 'students';

    public function createStudent($data)
    {
        $this->pdo->beginTransaction();
        $userId = $this->createUser($data);

        $sql = "INSERT INTO $this->studentTable (userID, gradeID, classID) VALUES (:userId, :grade, :classId)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'userId' => $userId,
                'grade' => $data['grade'],
                'classId' => $data['classId']
            ]);
            $this->pdo->commit();
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new Exception("Error Processing Request to student table: " . $e->getMessage());
        }

        return true;
    }
    public function getStudentById($studentId)
    {
        $sql = "SELECT s.*, u.* FROM {$this->studentTable} s JOIN {$this->userTable} u ON s.userID = u.userID WHERE s.studentID = :studentId";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['studentId' => $studentId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching student by ID: " . $e->getMessage());
        }
    }
}
