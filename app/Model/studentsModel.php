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
}