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
                    (report_type, category, title, description, report_date)
                    VALUES (:type, :category, :title, :description, NOW())";

            $stmt = $this->pdo->prepare($sql);
            $ok = $stmt->execute([
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
}
