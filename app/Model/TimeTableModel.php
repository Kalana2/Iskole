<?php

class TimeTableModel
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Get all distinct grades
     */
    public function getGrades()
    {
        $sql = "SELECT DISTINCT grade FROM `class` ORDER BY grade";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $out = [];
        foreach ($rows as $r) {
            $v = (string)$r['grade'];
            $out[] = ['value' => $v, 'label' => $v];
        }
        return $out;
    }

    /**
     * Get classes by grade
     */
    public function getClassesByGrade($grade)
    {
        $sql = "SELECT classID, class FROM `class` WHERE grade = :grade ORDER BY class";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['grade' => $grade]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all classes (fallback)
     */
    public function getClasses($grade = null)
    {
        if ($grade !== null && $grade !== '') {
            return $this->getClassesByGrade($grade);
        }
        
        $sql = "SELECT classID, class, grade FROM `class` ORDER BY grade, class";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*Get classID from grade and class name
            $sql = "SELECT classID, class FROM `class` WHERE grade = :grade ORDER BY class";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['grade' => $grade]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    public function getClassID($grade, $class)
    {
        $sql = "SELECT classID FROM `class` WHERE grade = :grade AND class = :class LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['grade' => $grade, 'class' => $class]);
        $result = $stmt->fetch();
        return $result ? $result['classID'] : null;
    }*/

    /**
     * Get all subjects
     */
    public function getSubjects()
    {
        $stmt = $this->pdo->prepare("SELECT subjectID, subjectName FROM subject ORDER BY subjectName");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get teachers mapped by subject
     */
    public function getTeachersMapping()
    {
        $sql = "SELECT t.teacherID, t.userID, t.subjectID, 
                COALESCE(
                    CONCAT(un.firstName, ' ', un.lastName), 
                    t.nic, 
                    CAST(t.userID AS CHAR)
                ) AS teacherName 
                FROM teachers t 
                LEFT JOIN userName un ON un.userID = t.userID 
                WHERE t.subjectID IS NOT NULL 
                ORDER BY un.firstName, un.lastName";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $map = [];
        foreach ($rows as $r) {
            $sub = (int)($r['subjectID'] ?? 0);
            if (!$sub) continue;
            if (!isset($map[$sub])) $map[$sub] = [];
            $map[$sub][] = [
                'teacherID' => (int)$r['teacherID'], 
                'name' => (string)$r['teacherName']
            ];
        }
        return $map;
    }

    /**
     * Get teachers by subject
     */
    public function getTeachersBySubject($subjectId)
    {
        $sql = "SELECT t.teacherID, t.userID, 
                COALESCE(
                    CONCAT(un.firstName, ' ', un.lastName), 
                    t.nic, 
                    CAST(t.userID AS CHAR)
                ) AS teacherName
                FROM teachers t
                LEFT JOIN userName un ON un.userID = t.userID
                WHERE t.subjectID = :subjectId AND t.subjectID IS NOT NULL
                ORDER BY un.firstName, un.lastName";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['subjectId' => $subjectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get school days
     */
    public function getDays()
    {
        $stmt = $this->pdo->prepare("SELECT id, day FROM schoolDays ORDER BY id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get day name to ID mapping
     */
    public function getDayMapping()
    {
        $stmt = $this->pdo->prepare("SELECT id, day FROM schoolDays");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $map = [];
        foreach ($rows as $row) {
            $map[$row['day']] = $row['id'];
        }
        return $map;
    }

    /**
     * Get periods
     */
    public function getPeriods()
    {
        $stmt = $this->pdo->prepare("SELECT periodID, startTime, endTime FROM periods ORDER BY periodID");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get period index to ID mapping
     */
    public function getPeriodMapping()
    {
        $stmt = $this->pdo->prepare("SELECT periodID FROM periods ORDER BY periodID");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $map = [];
        foreach ($rows as $index => $row) {
            $map[$index] = $row['periodID'];
        }
        return $map;
    }

    public function insertOrUpdateSlot($teacherId, $dayId, $periodId, $classId, $subjectId)
{
    // check existing slot
    $stmt = $this->pdo->prepare(
        "SELECT id FROM timetable
         WHERE classID = ? AND dayID = ? AND periodID = ?"
    );
    $stmt->execute([$classId, $dayId, $periodId]);
    $existing = $stmt->fetch();

    if ($existing) {
        // update
        $stmt = $this->pdo->prepare(
            "UPDATE timetable
             SET subjectID = ?, teacherID = ?
             WHERE id = ?"
        );
        return $stmt->execute([$subjectId, $teacherId, $existing['id']]);
    }

    // insert
    $stmt = $this->pdo->prepare(
        "INSERT INTO timetable (classID, dayID, periodID, subjectID, teacherID)
         VALUES (?, ?, ?, ?, ?)"
    );
    return $stmt->execute([$classId, $dayId, $periodId, $subjectId, $teacherId]);
}


    /**
     * Delete a single slot
     */
    public function deleteSlot($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM classTimetable WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Delete all slots for a class
     */
    public function deleteAllSlotsByClass($classId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM classTimetable WHERE classID = ?");
        return $stmt->execute([$classId]);
    }

    /**
     * Get timetable by classID
     */
    public function getTimetableByClass($classId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT ct.id, ct.teacherID, ct.dayID, ct.periodID, ct.classID, ct.subjectID,
                s.subjectName, 
                t.nic as teacherNic, 
                COALESCE(CONCAT(un.firstName, ' ', un.lastName), t.nic) AS teacherName,
                sd.day as dayName
             FROM classTimetable ct
             LEFT JOIN subject s ON s.subjectID = ct.subjectID
             LEFT JOIN teachers t ON t.teacherID = ct.teacherID
             LEFT JOIN userName un ON un.userID = t.userID
             LEFT JOIN schoolDays sd ON sd.id = ct.dayID
             WHERE ct.classID = ?
             ORDER BY ct.dayID, ct.periodID"
        );
        $stmt->execute([$classId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Transaction management
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    public function commit()
    {
        return $this->pdo->commit();
    }

    public function rollback()
    {
        return $this->pdo->rollBack();
    }
}