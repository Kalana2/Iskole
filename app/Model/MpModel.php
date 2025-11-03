<?php
include_once 'UserModel.php';
class MpModel extends UserModel
{
    private $mpTable = 'mp';

    public function createMp($data)
    {
        $userId = $this->createUser($data);

        $sql = "INSERT INTO $this->mpTable (userId,nic) VALUES (:userId, :nic)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'userId' => $userId,
                'nic' => $data['nic']
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error Processing Request to mp table: " . $e->getMessage());
        }

        return true;
    }
}