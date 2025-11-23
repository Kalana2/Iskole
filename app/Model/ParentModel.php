<?php
include_once __DIR__ . '/UserModel.php';
class ParentModel extends UserModel
{
    private $parentTable = 'parents';

    public function createParent($data)
    {
        $data['role'] = $this->userRoleMap['parent'];
        $userId = $this->createUser($data);

        $sql = "INSERT INTO $this->parentTable (userID, relationshipType, studentID, nic) VALUES (:userId, :relationshipType, :studentId, :nic)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'userId' => $userId,
                'relationshipType' => $data['relationshipType'],
                'studentId' => $data['studentId'],
                'nic' => $data['nic']
            ]);
        } catch (PDOException $e) {

            throw new Exception("Error Processing Request to parent table: " . $e->getMessage());
        }

        return true;
    }
}