<?php

class ReportModel
{
    protected $pdo;
    protected $table = 'report';

    public function __construct()
    {
        // Database::getInstance() already exists and is used in UserModel
        $this->pdo = Database::getInstance();
    }

    /**
     * Insert a new behavior report.
     *
     * Expected $data keys:
     *  - report_type
     *  - category
     *  - title
     *  - description
     *  - report_date (optional, if null -> NOW())
     */
    public function createReport(array $data)
    {
        try {
            // If caller did not pass report_date, let DB set NOW()
            if (!empty($data['report_date'])) {
                $sql = "INSERT INTO {$this->table}
                        (report_type, category, title, description, report_date)
                        VALUES (:type, :category, :title, :description, :report_date)";
            } else {
                $sql = "INSERT INTO {$this->table}
                        (report_type, category, title, description, report_date)
                        VALUES (:type, :category, :title, :description, NOW())";
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':type'        => $data['report_type'],
                ':category'    => $data['category'],
                ':title'       => $data['title'],
                ':description' => $data['description'],
                // If report_date is not set, PDO will just ignore this param
                ':report_date' => $data['report_date'] ?? null,
            ]);

            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error inserting report: " . $e->getMessage());
        }
    }

    /**
     * Get all reports (you can later filter by student/teacher if needed).
     */
    public function getAllReports()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY report_date DESC, id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
