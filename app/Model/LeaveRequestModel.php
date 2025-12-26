<?php

class LeaveRequestModel
{
    private PDO $pdo;

    public function __construct()
    {
        // ✅ Use your Database class / connection helper here
        // change this line based on your project:
        $this->pdo = Database::getInstance();
        // OR: $this->pdo = Database::getInstance();
        // OR: $this->pdo = (new Database())->getConnection();
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO leave_requests
                (teacherUserID, dateFrom, dateTo, leaveType, reason)
                VALUES (:teacherUserID, :dateFrom, :dateTo, :leaveType, :reason)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function getByTeacher(int $teacherUserID): array
    {
        $sql = "SELECT * FROM leave_requests
                WHERE teacherUserID = :tid
                ORDER BY createdAt DESC, id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':tid' => $teacherUserID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cancel(int $leaveId, int $teacherUserID): bool
    {
        $sql = "UPDATE leave_requests
                SET status = 'cancelled'
                WHERE id = :id
                  AND teacherUserID = :tid
                  AND status = 'pending'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $leaveId, ':tid' => $teacherUserID]);
        return $stmt->rowCount() > 0;
    }

    public function getAll(): array
    {
        $sql = "SELECT lr.*,
                       u.email AS teacher_email
                FROM leave_requests lr
                JOIN user u ON u.userID = lr.teacherUserID
                ORDER BY lr.createdAt DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function decide(int $leaveId, int $managerUserID, string $status, ?string $comment): bool
    {
        $sql = "UPDATE leave_requests
                SET status = :status,
                    managerUserID = :mid,
                    managerComment = :comment,
                    decidedAt = NOW()
                WHERE id = :id
                  AND status = 'pending'";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':status'  => $status,
            ':mid'     => $managerUserID,
            ':comment' => $comment,
            ':id'      => $leaveId
        ]);
    }


    // Manager: get ONLY pending requests
    public function getPending(): array
    {
        $sql = "SELECT lr.*
            FROM leave_requests lr
            WHERE lr.status = 'pending'
            ORDER BY lr.createdAt DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Manager: approve/reject (status update)
    public function updateStatus(int $id, int $managerUserID, string $status, ?string $comment = null): bool
    {
        $sql = "UPDATE leave_requests
            SET status = :status,
                managerUserID = :mid,
                managerComment = :comment,
                decidedAt = NOW()
            WHERE id = :id AND status = 'pending'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':status' => $status,
            ':mid' => $managerUserID,
            ':comment' => $comment,
            ':id' => $id
        ]);
        return $stmt->rowCount() > 0;
    }
}
