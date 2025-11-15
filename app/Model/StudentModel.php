<?php
require_once __DIR__ . '/UserModel.php';
class StudentModel extends UserModel
{
    private $studentTable = 'students';

    public function createStudent($data)
    {
        $userId = $this->createUser($data);

        $sql = "INSERT INTO $this->studentTable (userID, gradeID, classID) VALUES (:userId, :grade, :classId)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'userId' => $userId,
                'grade' => $data['grade'],
                'classId' => $data['classId']
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error Processing Request to student table: " . $e->getMessage());
        }

        return true;
    }
}
