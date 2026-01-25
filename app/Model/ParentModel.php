<?php
include_once __DIR__ . '/UserModel.php';
class ParentModel extends UserModel
{
    private $parentTable = 'parents';

    public function getLinkedStudentIdsByUserId(int $userId): array
    {
        $sql = "SELECT studentID FROM {$this->parentTable} WHERE userID = :userId";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['userId' => (int) $userId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            $out = [];
            foreach ($rows as $r) {
                $sid = $r['studentID'] ?? null;
                if ($sid === null || $sid === '') {
                    continue;
                }
                $out[] = (int) $sid;
            }
            return $out;
        } catch (PDOException $e) {
            throw new Exception("Error fetching linked students: " . $e->getMessage());
        }
    }

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

    public function getParentByUserId($userId)
    {
        $sql = "SELECT p.*, u.* FROM {$this->parentTable} p JOIN {$this->userTable} u ON p.userID = u.userID WHERE p.userID = :userId";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['userId' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching parent by user ID: " . $e->getMessage());
        }
    }
}