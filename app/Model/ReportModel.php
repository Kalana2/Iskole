<?php

class ReportModel
{
    protected $pdo;
    protected $table = 'report';

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }



    public function createReport(array $data)
    {
        try {
            $sql = "INSERT INTO {$this->table}
                    (studentID,teacherID,report_type, category, title, description, report_date)
                    VALUES (:studentID,:teacherID,:type, :category, :title, :description, NOW())";

            $stmt = $this->pdo->prepare($sql);
            $ok = $stmt->execute([
                ':studentID'   => $data['studentID'],
                ':teacherID'   => $data['teacherID'],
                ':type'        => $data['report_type'],
                ':category'    => $data['category'],
                ':title'       => $data['title'],
                ':description' => $data['description'],
            ]);

            if (!$ok) {
                // TEMP debug – insert fail නම් මේක දැක්කො
                die('Insert failed: ' . print_r($stmt->errorInfo(), true));
            }

            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            // TEMP debug
            die('Exception in createReport: ' . $e->getMessage());
        }
    }

    public function getAllReports()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY report_date DESC, id DESC LIMIT 3";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getReportsForParent(int $parentUserId): array
    {
        $sql = "
        SELECT
            r.*,
            CONCAT(IFNULL(un.firstName,''),' ',IFNULL(un.lastName,'')) AS teacher_name
        FROM report r
        INNER JOIN parents p
            ON p.studentID = r.studentID
           AND p.userID = :parentUserId
        LEFT JOIN teachers t
            ON t.userID = r.teacherID
        LEFT JOIN userName un
            ON un.userID = t.userID
        ORDER BY r.report_date DESC, r.id DESC
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':parentUserId' => $parentUserId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function findStudentInClass(int $classId, string $q): ?array
    {
        $sql = "
        SELECT
            s.studentID,
            s.classID,
            s.gradeID,
            u.email,
            u.phone,
            u.dateOfBirth,
            un.firstName,
            un.lastName
        FROM students s
        INNER JOIN user u ON u.userID = s.userID
        LEFT JOIN userName un ON un.userID = s.userID
        WHERE s.classID = :class_id
          AND (
                s.studentID = :exact
             OR u.email LIKE :like
             OR u.phone LIKE :like
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

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }


    public function getReportsByTeacher(int $teacherId): array
    {
        $sql = "SELECT * FROM report
            WHERE teacherID = :tid
            ORDER BY report_date DESC, id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':tid' => $teacherId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
