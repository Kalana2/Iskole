<?php
require_once __DIR__ . '/../Core/Database.php';

class ClassSubjectModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /* ===================== CLASSES ===================== */

    public function getAllClasses(): array
    {
        $sql = "SELECT classID, grade, class 
                FROM class
                ORDER BY grade ASC, class ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function classExists(int $grade, string $section): bool
    {
        $section = strtoupper(trim($section));

        $sql = "SELECT 1 FROM class WHERE grade = ? AND class = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$grade, $section]);

        return (bool)$stmt->fetchColumn();
    }

    public function createClass(int $grade, string $section): bool
    {
        $section = strtoupper(trim($section));

        // Safety validation in model too
        if (!preg_match('/^[A-Z]$/', $section)) {
            return false;
        }

        $sql = "INSERT INTO class (grade, class) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$grade, $section]);
    }

    public function deleteClass(int $classId): bool
    {
        $sql = "DELETE FROM class WHERE classID = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$classId]);
    }

    /* ===================== SUBJECTS ===================== */

    public function getAllSubjects(): array
    {
        $sql = "SELECT subjectID, subjectName FROM subject ORDER BY subjectName ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function subjectExists(string $subjectName): bool
    {
        $sql = "SELECT 1 FROM subject WHERE subjectName = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([trim($subjectName)]);

        return (bool)$stmt->fetchColumn();
    }

    public function createSubject(string $subjectName): bool
    {
        $sql = "INSERT INTO subject (subjectName) VALUES (?)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([trim($subjectName)]);
    }

    public function deleteSubject(int $subjectId): bool
    {
        $sql = "DELETE FROM subject WHERE subjectID = ?";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$subjectId]);
    }
}