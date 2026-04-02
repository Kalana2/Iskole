<?php
require_once __DIR__ . '/UserModel.php';
class ClassModel extends UserModel
{
    private $classTable = 'class';

    public function getClassById($classId)
    {
        $sql = "SELECT * FROM $this->classTable WHERE classID = :classId";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['classId' => $classId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching class by ID: " . $e->getMessage());
        }
    }

    public function getAllClasses()
    {
        $sql = "SELECT * FROM $this->classTable";
        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching all classes: " . $e->getMessage());
        }
    }
}