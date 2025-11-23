<?php
require_once __DIR__ . '/UserModel.php';
class TeacherModel extends UserModel
{
    private $teacherTable = 'teachers';

    public function createTeacher($data)
    {
        $data['role'] = $this->userRoleMap['teacher'];
        $userId = $this->createUser($data);

        $sql = "INSERT INTO $this->teacherTable (userID, subjectID, nic, classID, grade) VALUES (:userId, :subject, :nic, :classId, :grade)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'userId' => $userId,
                'subject' => $data['subject'],
                'nic' => $data['nic'],
                'classId' => $data['classId'],
                'grade' => $data['grade']
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error Processing Request to teacher table: " . $e->getMessage());
        }

        return true;
    }
    public function getTeacherByClass($grade, $class)
    {
        $sql = "SELECT classID FROM $this->teacherTable WHERE grade = :grade AND classID = :class LIMIT 1";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'grade' => $grade,
                'class' => $class
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['classID'] : null;
        } catch (PDOException $e) {
            throw new Exception("Error fetching teacher class: " . $e->getMessage());
        }
    }
}