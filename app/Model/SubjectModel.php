<?php
require_once __DIR__ . '/UserModel.php';
class SubjectModel extends UserModel
{
    private $subjectTable = 'subject';

    public function getAllSubjects()
    {
        $sql = "SELECT * FROM $this->subjectTable";
        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching subjects: " . $e->getMessage());
        }
    }

    public function getSubjectById($subjectId)
    {
        $sql = "SELECT * FROM $this->subjectTable WHERE subjectID = :subjectId";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['subjectId' => $subjectId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching subject by ID: " . $e->getMessage());
        }
    }
}