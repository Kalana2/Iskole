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
        $sql = "INSERT INTO leaveRequests
                (teacherUserID, dateFrom, dateTo, leaveType, reason)
                VALUES (:teacherUserID, :dateFrom, :dateTo, :leaveType, :reason)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function getByTeacher(int $teacherUserID, int $limit = 6): array
    {
        $sql = "SELECT *
            FROM leaveRequests
            WHERE teacherUserID = :tid
            ORDER BY createdAt DESC, id DESC
            LIMIT :lim";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':tid', $teacherUserID, PDO::PARAM_INT);
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function cancel(int $leaveId, int $teacherUserID): bool
    {
        $sql = "UPDATE leaveRequests
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
                FROM leaveRequests lr
                JOIN user u ON u.userID = lr.teacherUserID
                ORDER BY lr.createdAt DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function decide(int $leaveId, int $managerUserID, string $status, ?string $comment): bool
    {
        $sql = "UPDATE leaveRequests
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
    public function getPending(int $limit = 50): array
    {
        $sql = "SELECT lr.*,
                   lr.teacherUserID AS teacher_id,
                   CONCAT(un.firstName, ' ', un.lastName) AS teacher_name
            FROM leaveRequests lr
            JOIN userName un ON un.userID = lr.teacherUserID
            WHERE lr.status = 'pending'
            ORDER BY lr.createdAt DESC, lr.id DESC
            LIMIT :lim";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Manager: approve/reject (status update)
    public function updateStatus(int $id, int $managerUserID, string $status, ?string $comment = null): bool
    {
        $sql = "UPDATE leaveRequests
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
