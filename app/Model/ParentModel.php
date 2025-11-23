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
}