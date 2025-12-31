<?php
require_once __DIR__ . '/../Core/Database.php';

class TeacherModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ✅ Get teacher grade using logged-in user ID
    public function getGradeByUserID($userId)
    {
        $sql = "SELECT grade 
                FROM teachers 
                WHERE userID = :uid 
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':uid' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC); 
        // returns: ['grade' => 6]
    }
}
