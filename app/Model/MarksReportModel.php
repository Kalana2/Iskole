<?php

class MarksReportModel
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function getStudentByUserId($userId)
    {
        $sql = "SELECT st.studentID, st.userID, st.classID, st.gradeID,
                       un.firstName, un.lastName
                FROM students st
                LEFT JOIN userName un ON un.userID = st.userID
                WHERE st.userID = :uid
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['uid' => $userId]);
        $row = $stmt->fetch();
        if (!$row) return null;

        return [
            'studentID' => $row['studentID'],
            'userID' => $row['userID'],
            'classID' => $row['classID'],
            'gradeID' => $row['gradeID'],
            'firstName' => $row['firstName'] ?? '',
            'lastName' => $row['lastName'] ?? '',
            'name' => trim(($row['firstName'] ?? '') . ' ' . ($row['lastName'] ?? ''))
        ];
    }

    public function getAllSubjects()
    {
        $sql = "SELECT subjectID, subjectName FROM subject ORDER BY subjectName";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        return array_map(function ($r) {
            return [
                'subjectID' => isset($r['subjectID']) ? intval($r['subjectID']) : null,
                'subjectName' => $r['subjectName'] ?? ''
            ];
        }, $rows);
    }

    public function getMarksForStudent($studentId)
    {
        $sql = "SELECT m.markID, m.studentID, m.teacherID, m.subjectID, m.term,
                       m.marks, m.gradeLetter, m.enteredDate, m.updatedDate,
                       s.subjectName,
                       CONCAT(tn.firstName, ' ', tn.lastName) AS teacherName
                FROM marks m
                LEFT JOIN subject s ON s.subjectID = m.subjectID
                LEFT JOIN teachers t ON t.teacherID = m.teacherID
                LEFT JOIN userName tn ON tn.userID = t.userID
                WHERE m.studentID = :studentId
                ORDER BY m.term ASC, s.subjectName ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['studentId' => intval($studentId)]);
        $rows = $stmt->fetchAll();

        return array_map(function ($r) {
            return [
                'markID' => isset($r['markID']) ? intval($r['markID']) : null,
                'studentID' => isset($r['studentID']) ? intval($r['studentID']) : null,
                'teacherID' => isset($r['teacherID']) ? intval($r['teacherID']) : null,
                'subjectID' => isset($r['subjectID']) ? intval($r['subjectID']) : null,
                'subjectName' => $r['subjectName'] ?? '',
                'teacherName' => trim($r['teacherName'] ?? ''),
                'term' => isset($r['term']) ? strval($r['term']) : null,
                'marks' => isset($r['marks']) ? floatval($r['marks']) : null,
                'gradeLetter' => $r['gradeLetter'] ?? null,
                'enteredDate' => $r['enteredDate'] ?? null,
                'updatedDate' => $r['updatedDate'] ?? null,
            ];
        }, $rows);
    }

    public function getAvailableTermsForStudent(int $studentId): array
    {
        $sql = "
            SELECT DISTINCT term
            FROM marks
            WHERE studentID = :studentID
            ORDER BY term ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'studentID' => $studentId
        ]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Get the child's student record for a parent user
     * Uses the parents table which links parentUserID -> studentID
     */
    public function getChildStudentByParentUserId($parentUserId)
    {
        $sql = "SELECT st.studentID, st.userID, st.classID, st.gradeID,
                       un.firstName, un.lastName
                FROM parents p
                JOIN students st ON p.studentID = st.studentID
                LEFT JOIN userName un ON un.userID = st.userID
                WHERE p.userID = :parentUserId
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['parentUserId' => $parentUserId]);
        $row = $stmt->fetch();
        if (!$row) return null;

        return [
            'studentID' => $row['studentID'],
            'userID' => $row['userID'],
            'classID' => $row['classID'],
            'gradeID' => $row['gradeID'],
            'firstName' => $row['firstName'] ?? '',
            'lastName' => $row['lastName'] ?? '',
            'name' => trim(($row['firstName'] ?? '') . ' ' . ($row['lastName'] ?? ''))
        ];
    }

    /**
     * NEW METHOD: Get student's performance stats
     * Includes marks, average per term, and overall info
     */
    public function getStudentPerformanceStats(int $studentId, ?string $term = null): array
    {
        $marks = $this->getMarksForStudent($studentId);

        // Filter by term if provided
        if ($term !== null) {
            $marks = array_filter($marks, fn($m) => $m['term'] === $term);
        }

        // Compute average per term
        $average = 0;
        $count = 0;
        foreach ($marks as $m) {
            if (isset($m['marks'])) {
                $average += $m['marks'];
                $count++;
            }
        }
        $average = $count > 0 ? round($average / $count, 2) : null;

        return [
            'studentID' => $studentId,
            'term' => $term,
            'marks' => array_values($marks),
            'average' => $average,
        ];
    }
}
