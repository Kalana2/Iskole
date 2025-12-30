<?php

class ClassSubjectModel
{
    private $db; // Database instance (PDO wrapper)

    public function __construct()
    {
        $this->db = Database::getInstance(); // your project DB wrapper
    }

    /* ===================== CLASSES ===================== */

    public function getAllClasses(): array
    {
        // ✅ table columns: classID, grade, class
        $sql = "SELECT classID, grade, class 
                FROM class
                ORDER BY grade ASC, class ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function classExists(int $grade, string $section): bool
    {
        // ✅ class column stores section (A/B)
        $sql = "SELECT 1 FROM class WHERE grade = ? AND class = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$grade, strtoupper(trim($section))]);
        return (bool)$stmt->fetchColumn();
    }

    public function createClass(int $grade, string $section): bool
    {
        $sql = "INSERT INTO class (grade, class) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$grade, strtoupper(trim($section))]);
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
        $sql = "SELECT subjectID, subjectName
            FROM subject
            ORDER BY subjectName ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function subjectExists(int $grade, string $subjectName): bool
    {
        $sql = "SELECT 1 FROM subject WHERE grade = ? AND subjectName = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$grade, trim($subjectName)]);
        return (bool)$stmt->fetchColumn();
    }

    public function createSubject(int $grade, string $subjectName): bool
    {
        $sql = "INSERT INTO subject (grade, subjectName) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$grade, trim($subjectName)]);
    }

    public function deleteSubject(int $subjectId): bool
    {
        $sql = "DELETE FROM subject WHERE subjectID = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$subjectId]);
    }
}
