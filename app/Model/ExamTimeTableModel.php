<?php
// app/Model/ExamTimeTableModel.php
require_once __DIR__ . '/../Core/Database.php';

class ExamTimeTableModel
{
    private $db;
    private $examTimeTableTable = 'examTimeTable';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create($grade, $filePath, $userID = null, $title = 'Exam Timetable')
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->examTimeTableTable} (userID, visibility, grade, title, file) VALUES (:userID, 1, :grade, :title, :file)");
        return $stmt->execute([
            ':userID' => $userID ?? ($_SESSION['user_id'] ?? 0),
            ':grade' => $grade,
            ':title' => $title,
            ':file' => $filePath
        ]);
    }

    public function getByGrade($grade)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->examTimeTableTable} WHERE grade = :grade ORDER BY timeTableID DESC LIMIT 1");
        $stmt->execute([':grade' => $grade]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($grade, $filePath, $userID = null, $title = 'Exam Timetable')
    {
        $stmt = $this->db->prepare("UPDATE {$this->examTimeTableTable} SET file = :file, userID = :userID, title = :title WHERE grade = :grade");
        return $stmt->execute([
            ':file' => $filePath,
            ':userID' => $userID ?? ($_SESSION['user_id'] ?? 0),
            ':title' => $title,
            ':grade' => $grade
        ]);
    }

    public function toggleVisibility($grade, $visibility)
    {
        $stmt = $this->db->prepare("UPDATE {$this->examTimeTableTable} SET visibility = :visibility WHERE grade = :grade");
        return $stmt->execute([
            ':visibility' => $visibility,
            ':grade' => $grade
        ]);
    }

    public function exists($grade)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM {$this->examTimeTableTable} WHERE grade = :grade");
        $stmt->execute([':grade' => $grade]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
}

?>