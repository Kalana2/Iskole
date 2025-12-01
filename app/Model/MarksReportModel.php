<?php
require_once __DIR__ . '/../Core/Database.php';

class MarksReportModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // -------------------------------
    // 1. Latest term marks
    // -------------------------------
    public function getSubjectsWithLatestTermMarks($studentId)
    {
        $sql = "
            SELECT s.subjectName AS name, 
                   m.marks AS score,
                   m.gradeLetter AS grade
            FROM marks m
            JOIN subjects s ON m.subjectID = s.subjectID
            WHERE m.studentID = ?
            AND m.term = (
                SELECT MAX(term) 
                FROM marks 
                WHERE studentID = ?
            )
            ORDER BY s.subjectID ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$studentId, $studentId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // -------------------------------
    // 2. Term-wise marks for chart
    // -------------------------------
    public function getTermWiseMarks($studentId)
    {
        // Result grouped by subject
        $result = [];

        $sql = "
            SELECT s.subjectName AS subject,
                   m.term,
                   m.marks
            FROM marks m
            JOIN subjects s ON m.subjectID = s.subjectID
            WHERE m.studentID = ?
            ORDER BY s.subjectID ASC, m.term ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$studentId]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $subject = $row['subject'];

            // Initialize array if not exists
            if (!isset($result[$subject])) {
                $result[$subject] = [null, null, null]; 
            }

            // Term 1 → index 0, Term 2 → index 1, Term 3 → index 2
            $result[$subject][$row['term'] - 1] = (int)$row['marks'];
        }

        return $result;
    }
}
