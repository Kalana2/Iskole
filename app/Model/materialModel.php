<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/TeacherModel.php';

class Material extends TeacherModel
{
    private $table = "material";
    private $teacherID;

    public function __construct()
    {
        parent::__construct(); // Initialize parent class
        try {
            $this->pdo = Database::getInstance();
            if (!$this->pdo) {
                throw new Exception("Database connection failed");
            }

            if (!isset($_SESSION['user_id'])) {
                throw new Exception("No user session found");
            }

            $this->teacherID = $this->getTeacherIDByUserID($_SESSION['user_id']);
            if (!$this->teacherID) {
                throw new Exception("Teacher ID not found for user");
            }
        } catch (Exception $e) {
            error_log("Material model initialization error: " . $e->getMessage());
            throw $e;
        }
    }

    public function getConnectionStatus()
    {
        return $this->pdo !== null;
    }

    public function addMaterial($grade, $class, $subject, $title, $description, $filePath)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO material (grade, class, subjectID, title, description, file, teacherID) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare statement failed");
            }

            $result = $stmt->execute([$grade, $class, $subject, $title, $description, $filePath, $this->teacherID]);

            if ($result) {
                return true;
            } else {
                throw new Exception("Execute failed");
            }
        } catch (Exception $e) {
            error_log("Error adding material: " . $e->getMessage());
            throw $e;
        }
    }

    public function showMaterials()
    {
        $stmt = $this->pdo->prepare("SELECT material.*,subject.subjectName FROM material LEFT JOIN subject ON material.subjectID = subject.subjectID LEFT JOIN teachers ON material.teacherID = teachers.teacherID JOIN user ON teachers.userID = user.userID WHERE material.teacherID = ? AND material.deleted = 0 ORDER BY date DESC");
        $stmt->execute([$this->teacherID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function hideVisibility($materialID)
    {
        $stmt = $this->pdo->prepare("UPDATE material SET visibility = 0 WHERE materialID = ? AND teacherID = ?");
        return $stmt->execute([$materialID, $this->teacherID]);
    }

    public function deleteMaterial($materialID)
    {
        $stmt = $this->pdo->prepare("UPDATE material SET deleted = 1 WHERE materialID = ? AND teacherID = ?");
        return $stmt->execute([$materialID, $this->teacherID]);
    }

    public function unhideMaterial($materialID)
    {
        $stmt = $this->pdo->prepare("UPDATE material SET visibility = 1 WHERE materialID = ? AND teacherID = ?");
        return $stmt->execute([$materialID, $this->teacherID]);
    }

    public function getMaterial($grade, $class)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM material 
        JOIN subject ON material.subjectID = subject.subjectID
        JOIN teacher ON material.teacherID = teacher.teacherID
        JOIN user ON teacher.userID = user.userID
         WHERE material.grade = ? AND material.class = ? AND material.visibility = 1 AND material.deleted = 0
         ORDER BY date DESC");
        $stmt->execute([$grade, $class]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchFileName($materialID)
    {
        $stmt = $this->pdo->prepare("SELECT file FROM material WHERE materialID = ?");
        $stmt->execute([$materialID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function editMaterial($materialID, $grade, $class, $subjectID, $title, $description, $file, $teacherID)
    {
        $query = "UPDATE material SET grade = ?, class = ?, subjectID = ?, title = ?, description = ?, file = ? WHERE materialID = ? AND teacherID = ?";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([$grade, $class, $subjectID, $title, $description, $file, $materialID, $teacherID]);
    }
}
