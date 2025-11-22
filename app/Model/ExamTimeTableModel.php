<?php
// app/Model/ExamTimeTableModel.php
require_once __DIR__ . '/../Core/Database.php';

class ExamTimeTableModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getByGrade($grade)
    {
        $stmt = $this->db->prepare("SELECT * FROM exam_time_tables WHERE grade = :grade LIMIT 1");
        $stmt->execute([':grade' => $grade]);
        return $stmt->fetch() ?: null;
    }

    public function create($grade, $filePath, $mime = null, $size = null)
    {
        $stmt = $this->db->prepare("
            INSERT INTO exam_time_tables (grade, file_path, mime, size, hidden)
            VALUES (:grade, :file_path, :mime, :size, 0)
        ");
        $ok = $stmt->execute([
    ':grade' => $grade,
    ':file_path' => $filePath,
    ':mime' => $mime,
    ':size' => $size
]);

if (!$ok) {
    echo "<pre>";
    print_r($stmt->errorInfo());
    echo "</pre>";
    exit;
}

return $ok;
    }

    public function updateFile($grade, $filePath, $mime = null, $size = null)
    {
        $stmt = $this->db->prepare("
            UPDATE exam_time_tables
            SET file_path = :file_path,
                mime = :mime,
                size = :size,
                uploaded_at = CURRENT_TIMESTAMP
            WHERE grade = :grade
        ");
        return $stmt->execute([
            ':grade' => $grade,
            ':file_path' => $filePath,
            ':mime' => $mime,
            ':size' => $size
        ]);
    }

    public function updateVisibility($grade, $hidden)
    {
        $stmt = $this->db->prepare("
            UPDATE exam_time_tables
            SET hidden = :hidden
            WHERE grade = :grade
        ");
        return $stmt->execute([
            ':grade' => $grade,
            ':hidden' => $hidden ? 1 : 0
        ]);
    }

    public function delete($grade)
    {
        $stmt = $this->db->prepare("DELETE FROM exam_time_tables WHERE grade = :grade");
        return $stmt->execute([':grade' => $grade]);
    }
}
?>
