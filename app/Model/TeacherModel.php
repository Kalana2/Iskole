<?php
require_once __DIR__ . '/UserModel.php';
class TeacherModel extends UserModel
{
    private $teacherTable = 'teachers';

    public function createTeacher($data)
    {
        $data['role'] = $this->userRoleMap['teacher'];
        $this->pdo->beginTransaction();
        try {
            $userId = $this->createUser($data);

            $sql = "INSERT INTO $this->teacherTable (userID, subjectID, nic, classID, grade) VALUES (:userId, :subject, :nic, :classId, :grade)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'userId' => $userId,
                'subject' => $data['subject'],
                'nic' => $data['nic'],
                'classId' => $data['classId'],
                'grade' => $data['grade']
            ]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new Exception("Error Processing Request to teacher table: " . $e->getMessage());
        }
    }

    public function getTeacherIDByUserID($userID)
    {
        $sql = "SELECT teacherID FROM $this->teacherTable WHERE userID = :userID";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['userID' => $userID]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['teacherID'] : null;
        } catch (PDOException $e) {
            throw new Exception("Error Fetching Teacher ID: " . $e->getMessage());
        }
    }

    public function getTeacherByClass($grade, $classId)
    {
        $sql = "SELECT t.*, u.* FROM `{$this->teacherTable}` t JOIN `{$this->userTable}` u ON t.`userID` = u.`userID` WHERE t.`grade` = :grade AND t.`classID` = :classId";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'grade' => $grade,
                'classId' => $classId
            ]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Error fetching teacher class: " . $e->getMessage());
        }
    }

    public function getTeacherByUserId($userId)
    {
        $sql = "SELECT t.*, u.* FROM `{$this->teacherTable}` t JOIN `{$this->userTable}` u ON t.`userID` = u.`userID` WHERE t.`userID` = :userId";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['userId' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching teacher by user ID: " . $e->getMessage());
        }
    }

    public function getAllTeachers()
    {
        $sql = "SELECT t.*, u.*, s.* FROM `{$this->teacherTable}` t JOIN `{$this->userTable}` u ON t.`userID` = u.`userID` JOIN `subject` s ON t.`subjectID` = s.`subjectID`";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching all teachers: " . $e->getMessage());
        }
    }
}
