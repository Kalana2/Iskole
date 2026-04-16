<?php
include_once 'UserModel.php';
class MpModel extends UserModel
{
    private $mpTable = 'managers';

    public function createMp($data)
    {
        $this->pdo->beginTransaction();
        try {
            $userId = $this->createUser($data);
            $sql = "INSERT INTO $this->mpTable (userID,nic) VALUES (:userId, :nic)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'userId' => $userId,
                'nic' => $data['nic']
            ]);
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new Exception("Error Processing Request to mp table: " . $e->getMessage());
        }
    }


    public function getMpByUserId($userId)
    {
        $sql = "SELECT p.*, u.* FROM {$this->mpTable} p JOIN {$this->userTable} u ON p.userID = u.userID WHERE p.userID = :userId";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['userId' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching parent by user ID: " . $e->getMessage());
        }
    }
}
