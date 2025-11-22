<?php

class StudentAbsenceReason
{
    protected $pdo;
    private $table = "absentReasons";

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }
    public function submitAbsenceReason($data)
    {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO " . $this->table . " (parentID, studentID, teacherID, reason, fromDate, toDate) 
                 VALUES (:parentId, :studentId, :teacherId, :reason, :fromDate, :toDate)"
            );
            return $stmt->execute([
                'parentId' => $data['parentId'],
                'studentId' => $data['studentId'],
                'teacherId' => $data['teacherId'],
                'reason' => $data['reason'],
                'fromDate' => $data['fromDate'],
                'toDate' => $data['toDate'],
            ]);
        } catch (PDOException $e) {
            error_log('Failed to submit absence reason: ' . $e->getMessage());
            return false;
        }
    }


}