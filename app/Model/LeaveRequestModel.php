<?php

require_once __DIR__ . '/../Core/Database.php';

class LeaveRequestModel
{
    private PDO $pdo;
    private int $annualLeaveLimit = 25;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
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
        $sql = "SELECT lr.*
            FROM leaveRequests lr
            LEFT JOIN teachers t ON t.teacherID = lr.teacherUserID
            WHERE lr.teacherUserID = :tid
               OR t.userID = :tid
            ORDER BY lr.createdAt DESC, lr.id DESC
            LIMIT :lim";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':tid', $teacherUserID, PDO::PARAM_INT);
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAnnualLeaveLimit(): int
    {
        return $this->annualLeaveLimit;
    }

    public function getLeaveBalanceByTeacher(int $teacherUserID): array
    {
        $sql = "SELECT COUNT(*) AS used_leave_days
            FROM teacherAttendance ta
            JOIN teachers t ON t.teacherID = ta.teacherID
            WHERE t.userID = :tid
              AND LOWER(ta.status) = 'absent'
              AND YEAR(ta.attendance_date) = YEAR(CURDATE())";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':tid', $teacherUserID, PDO::PARAM_INT);
        $stmt->execute();

        $used = (int)($stmt->fetchColumn() ?: 0);
        $remaining = max($this->annualLeaveLimit - $used, 0);

        return [
            'used_leave_days' => $used,
            'remaining_leave_days' => $remaining,
            'annual_limit' => $this->annualLeaveLimit,
        ];
    }

    public function cancel(int $leaveId, int $teacherUserID): bool
    {
        $sql = "UPDATE leaveRequests
                SET status = 'cancelled'
                WHERE id = :id
                  AND (
                        teacherUserID = :tid
                        OR teacherUserID = (
                            SELECT t.teacherID
                            FROM teachers t
                            WHERE t.userID = :tid
                            LIMIT 1
                        )
                  )
                  AND status = 'pending'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id'  => $leaveId,
            ':tid' => $teacherUserID
        ]);

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

    public function getPending(int $limit = 50): array
    {
        $sql = "SELECT 
                    lr.*,
                    lr.teacherUserID AS teacher_id,
                    CONCAT(un.firstName, ' ', un.lastName) AS teacher_name,

                    COALESCE((
                                                SELECT COUNT(*)
                                                FROM teacherAttendance ta2
                                                JOIN teachers t2 ON t2.teacherID = ta2.teacherID
                                                WHERE t2.userID = lr.teacherUserID
                                                    AND LOWER(ta2.status) = 'absent'
                                                    AND YEAR(ta2.attendance_date) = YEAR(CURDATE())
                    ), 0) AS used_leave_days,

                    GREATEST(
                        :annualLimit - COALESCE((
                                                        SELECT COUNT(*)
                                                        FROM teacherAttendance ta3
                                                        JOIN teachers t3 ON t3.teacherID = ta3.teacherID
                                                        WHERE t3.userID = lr.teacherUserID
                                                            AND LOWER(ta3.status) = 'absent'
                                                            AND YEAR(ta3.attendance_date) = YEAR(CURDATE())
                        ), 0),
                        0
                    ) AS remaining_leave_days

                FROM leaveRequests lr
                JOIN userName un ON un.userID = lr.teacherUserID
                WHERE lr.status = 'pending'
                ORDER BY lr.createdAt DESC, lr.id DESC
                LIMIT :lim";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':annualLimit', $this->annualLeaveLimit, PDO::PARAM_INT);
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus(int $id, int $managerUserID, string $status, ?string $comment = null): bool
    {
        $sql = "UPDATE leaveRequests
                SET status = :status,
                    managerUserID = :mid,
                    managerComment = :comment,
                    decidedAt = NOW()
                WHERE id = :id
                  AND status = 'pending'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':status' => $status,
            ':mid'    => $managerUserID,
            ':comment' => $comment,
            ':id'     => $id
        ]);

        return $stmt->rowCount() > 0;
    }
}
