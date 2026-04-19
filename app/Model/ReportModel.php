<?php

require_once __DIR__ . '/../Core/Database.php';

class ReportModel
{
    protected $pdo;
    protected $table = 'report';

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    private function baseSelectSql(): string
    {
        return "
            SELECT
                r.*,
                r.id AS report_id,
                TRIM(CONCAT(IFNULL(un.firstName,''),' ',IFNULL(un.lastName,''))) AS teacher_name
            FROM report r
            LEFT JOIN teachers t ON t.userID = r.teacherID
            LEFT JOIN userName un ON un.userID = t.userID
        ";
    }

    public function getReportsByTeacher(int $teacherUserId): array
    {
        $sql = $this->baseSelectSql() . "
            WHERE r.teacherID = :tid
            ORDER BY r.report_date DESC, r.id DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':tid' => $teacherUserId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReportsByStudent(int $studentID): array
    {
        $sql = $this->baseSelectSql() . "
            WHERE r.studentID = :sid
            ORDER BY r.report_date DESC, r.id DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':sid' => $studentID]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReportsForParent(int $parentUserId): array
    {
        $sql = "
            SELECT
                r.*,
                r.id AS report_id,
                TRIM(CONCAT(IFNULL(tun.firstName,''), ' ', IFNULL(tun.lastName,''))) AS teacher_name,
                TRIM(CONCAT(IFNULL(sun.firstName,''), ' ', IFNULL(sun.lastName,''))) AS student_name
            FROM report r
            INNER JOIN students s ON s.studentID = r.studentID
            INNER JOIN parents p ON p.studentID = s.studentID
            LEFT JOIN teachers t ON t.userID = r.teacherID
            LEFT JOIN userName tun ON tun.userID = t.userID
            LEFT JOIN userName sun ON sun.userID = s.userID
            WHERE p.userID = :parentUserId
            ORDER BY r.report_date DESC, r.id DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':parentUserId' => $parentUserId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReportByIdWithTeacherName(int $reportId): ?array
    {
        $sql = $this->baseSelectSql() . "
            WHERE r.id = :id
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $reportId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function createReport(array $data)
    {
        $sql = "INSERT INTO {$this->table}
                (studentID, teacherID, report_type,severity_type, category, title, description, report_date)
                VALUES (:studentID, :teacherID, :type, :severity_type, :category, :title, :description, NOW())";

        $stmt = $this->pdo->prepare($sql);
        $ok = $stmt->execute([
            ':studentID'   => $data['studentID'],
            ':teacherID'   => $data['teacherID'],
            ':type'        => $data['report_type'], 
            ':severity_type' => $data['severity_type'],
            ':category'    => $data['category'],
            ':title'       => $data['title'],
            ':description' => $data['description'],
        ]);

        if (!$ok) {
            return false;
        }

        return (int)$this->pdo->lastInsertId();
    }

    public function deleteReportByIdAndTeacher(int $reportId, int $teacherUserId): bool
    {
        $sql = "DELETE FROM report WHERE id = :id AND teacherID = :tid";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $reportId,
            ':tid' => $teacherUserId
        ]);
    }

    public function getReportByIdAndTeacher(int $reportId, int $teacherUserId)
    {
        $sql = "SELECT * FROM report WHERE id = :id AND teacherID = :tid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $reportId,
            ':tid' => $teacherUserId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateReportByTeacher(int $reportId, int $teacherUserId, array $data): bool
    {
        $sql = "UPDATE report SET
                  report_type = :type,
                  severity_type = :severity_type,
                  category    = :cat,
                  title       = :title,
                  description = :desc
                WHERE id = :id AND teacherID = :tid";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':type'  => $data['report_type'],
            ':severity_type' => $data['severity_type'],
            ':cat'   => $data['category'],
            ':title' => $data['title'],
            ':desc'  => $data['description'],
            ':id'    => $reportId,
            ':tid'   => $teacherUserId
        ]);
    }

    public function getStudentsByClass(int $classId): array
    {
        $sql = "
            SELECT
                s.studentID,
                s.classID,
                c.class AS className,
                c.grade AS grade,
                u.email,
                u.phone,
                u.dateOfBirth,
                un.firstName,
                un.lastName
            FROM students s
            INNER JOIN class c ON c.classID = s.classID
            INNER JOIN user u ON u.userID = s.userID
            LEFT JOIN userName un ON un.userID = s.userID
            WHERE s.classID = :class_id
            ORDER BY un.firstName ASC, un.lastName ASC, s.studentID ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':class_id' => $classId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findStudentInClass(int $classId, string $q): ?array
    {
        $sql = "
            SELECT
                s.studentID,
                s.classID,
                c.class AS className,
                c.grade AS grade,
                u.email,
                u.phone,
                u.dateOfBirth,
                un.firstName,
                un.lastName
            FROM students s
            INNER JOIN class c ON c.classID = s.classID
            INNER JOIN user u ON u.userID = s.userID
            LEFT JOIN userName un ON un.userID = s.userID
            WHERE s.classID = :class_id
              AND (
                    s.studentID = :exact
                 OR CONCAT(IFNULL(un.firstName,''),' ',IFNULL(un.lastName,'')) LIKE :like
                 OR un.firstName LIKE :like
                 OR un.lastName LIKE :like
              )
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':class_id' => $classId,
            ':exact'    => $q,
            ':like'     => "%$q%",
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}