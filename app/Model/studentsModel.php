<?php
class StudentsModel
{
    protected $pdo;
    private $studentsTable = 'students';

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function getGradeByUserID($userID)
    {
        $stmt = $this->pdo->prepare("SELECT c.grade 
                                        FROM students s
                                        JOIN class c
                                        ON s.classID = c.classID
                                        WHERE s.userID = :userID");
        $stmt->execute(['userID' => $userID]);
        return $stmt->fetch();
    }

    /**
     * Get child's information including grade for a parent
     * This assumes you have a parent-student relationship table
     * Adjust table/column names based on your actual database schema
     */
    public function getChildGradeByParentID($parentUserID)
    {
        // If you have a parent_student relationship table, use this:
        // $stmt = $this->pdo->prepare("SELECT c.grade, s.userID as childUserID
        //                                 FROM parent_student ps
        //                                 JOIN students s ON ps.studentUserID = s.userID
        //                                 JOIN class c ON s.classID = c.classID
        //                                 WHERE ps.parentUserID = :parentUserID
        //                                 LIMIT 1");
        
        // For now, return null - implement based on your database structure
        return $this->getGradeByUserID($parentUserID);
    }
}