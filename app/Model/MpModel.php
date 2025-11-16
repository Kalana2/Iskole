<?php
include_once 'UserModel.php';
class MpModel extends UserModel
{
    private $mpTable = 'managers';

    public function createMp($data)
    {
        $this->pdo->beginTransaction();
        $userId = $this->createUser($data);
        $sql = "INSERT INTO $this->mpTable (userID,nic) VALUES (:userId, :nic)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'userId' => $userId,
                'nic' => $data['nic']
            ]);
            $this->pdo->commit();
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new Exception("Error Processing Request to mp table: " . $e->getMessage());
        }

        return true;
    }
}