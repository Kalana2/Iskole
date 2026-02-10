<?php

class ClassTeacherModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all classes with their assigned class teachers
     */
    public function getAllClassesWithTeachers(): array
    {
        $sql = "SELECT 
                    c.classID,
                    c.grade,
                    c.class,
                    t.teacherID,
                    CONCAT(un.firstName, ' ', un.lastName) as teacherName
                FROM class c
                LEFT JOIN teachers t ON c.classID = t.classID
                LEFT JOIN user u ON t.userID = u.userID
                LEFT JOIN userName un ON u.userID = un.userID
                ORDER BY c.grade ASC, c.class ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all teachers who are active (role = 2)
     */
    public function getAllTeachers(): array
    {
        $sql = "SELECT 
                    t.teacherID,
                    t.userID,
                    t.classID,
                    CONCAT(un.firstName, ' ', un.lastName) as name,
                    s.subjectName
                FROM teachers t
                JOIN user u ON t.userID = u.userID
                LEFT JOIN userName un ON u.userID = un.userID
                LEFT JOIN subject s ON t.subjectID = s.subjectID
                WHERE u.active = 1 AND u.role = 2
                ORDER BY un.firstName ASC, un.lastName ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Assign a teacher to a class as class teacher
     */
    public function assignClassTeacher(int $classId, int $teacherId): bool
    {
        try {
            // First, remove any existing class teacher assignment for this class
            // (Set classID to NULL for any teacher currently assigned to this class)
            $sql = "UPDATE teachers SET classID = NULL WHERE classID = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$classId]);

            // Now assign the new teacher to this class
            $sql = "UPDATE teachers SET classID = ? WHERE teacherID = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$classId, $teacherId]);
        } catch (PDOException $e) {
            error_log("Error assigning class teacher: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove class teacher assignment from a class
     */
    public function removeClassTeacher(int $classId): bool
    {
        try {
            $sql = "UPDATE teachers SET classID = NULL WHERE classID = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$classId]);
        } catch (PDOException $e) {
            error_log("Error removing class teacher: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get teacher details by teacherID
     */
    public function getTeacherById(int $teacherId): ?array
    {
        $sql = "SELECT 
                    t.teacherID,
                    t.userID,
                    t.classID,
                    CONCAT(un.firstName, ' ', un.lastName) as name
                FROM teachers t
                JOIN user u ON t.userID = u.userID
                LEFT JOIN userName un ON u.userID = un.userID
                WHERE t.teacherID = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$teacherId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}
