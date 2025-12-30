<?php

require_once __DIR__ . '/../Core/Database.php';

class TeacherTimeTableModel
{
    private PDO $pdo;
    private string $nameTable;
    private string $firstNameCol;
    private string $lastNameCol;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
        $this->nameTable = $this->detectNameTable();
        [$this->firstNameCol, $this->lastNameCol] = $this->detectNameColumns($this->nameTable);
    }

    private function detectNameTable(): string
    {
        $tables = $this->pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_NUM);
        $flat = array_map(static fn($r) => (string) $r[0], $tables);
        if (in_array('userName', $flat, true)) {
            return 'userName';
        }
        if (in_array('username', $flat, true)) {
            return 'username';
        }
        return 'userName';
    }

    private function detectNameColumns(string $table): array
    {
        try {
            $cols = $this->pdo->query("DESCRIBE `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return ['firstName', 'lastName'];
        }
        $names = array_map(static fn($c) => (string) $c['Field'], $cols);
        $first = in_array('firstName', $names, true) ? 'firstName' : (in_array('fName', $names, true) ? 'fName' : 'firstName');
        $last = in_array('lastName', $names, true) ? 'lastName' : (in_array('lName', $names, true) ? 'lName' : 'lastName');
        return [$first, $last];
    }

    private function tableExists(string $tableName): bool
    {
        $stmt = $this->pdo->prepare('SHOW TABLES LIKE ?');
        $stmt->execute([$tableName]);
        return (bool) $stmt->fetchColumn();
    }

    private function pickExistingTable(array $candidates): ?string
    {
        foreach ($candidates as $t) {
            if ($this->tableExists($t)) {
                return $t;
            }
        }
        return null;
    }

    private function getTeacherByUserId(int $userId): ?array
    {
        if (!$this->tableExists('teachers')) {
            return null;
        }

        // employeeID may not exist in some environments.
        $employeeExpr = 'NULL as employee_id';
        try {
            $colsInfo = $this->pdo->query('DESCRIBE `teachers`')->fetchAll(PDO::FETCH_ASSOC);
            $cols = array_map(static fn($c) => (string) $c['Field'], $colsInfo);
            if (in_array('employeeID', $cols, true)) {
                $employeeExpr = 'employeeID as employee_id';
            } elseif (in_array('emp_no', $cols, true)) {
                $employeeExpr = 'emp_no as employee_id';
            }
        } catch (Throwable $e) {
            // ignore
        }

        $stmt = $this->pdo->prepare("SELECT teacherID, {$employeeExpr} FROM teachers WHERE userID = :userID LIMIT 1");
        $stmt->execute(['userID' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private function getUserFullName(int $userId): string
    {
        if (!$this->tableExists($this->nameTable)) {
            return '';
        }
        $first = $this->firstNameCol;
        $last = $this->lastNameCol;
        $stmt = $this->pdo->prepare("SELECT TRIM(CONCAT(COALESCE(n.`{$first}`,''), ' ', COALESCE(n.`{$last}`,''))) as fullName FROM `{$this->nameTable}` n WHERE n.userID = :userID LIMIT 1");
        $stmt->execute(['userID' => $userId]);
        return trim((string) $stmt->fetchColumn());
    }

    private function getDayIdToName(): array
    {
        if (!$this->tableExists('schoolDays')) {
            return [];
        }
        $stmt = $this->pdo->prepare('SELECT id, day FROM schoolDays');
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        $out = [];
        foreach ($rows as $r) {
            $out[(int) ($r['id'] ?? 0)] = (string) ($r['day'] ?? '');
        }
        return $out;
    }

    private function getPeriodIdToNumber(): array
    {
        if (!$this->tableExists('periods')) {
            return [];
        }
        $stmt = $this->pdo->prepare('SELECT periodID FROM periods ORDER BY periodID');
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        $out = [];
        $idx = 1;
        foreach ($rows as $r) {
            $pid = (int) ($r['periodID'] ?? 0);
            if ($pid > 0) {
                $out[$pid] = $idx;
                $idx++;
            }
        }
        return $out;
    }

    private function getClassLabelById(int $classId): string
    {
        $classTable = $this->pickExistingTable(['class', 'classes']);
        if (!$classTable) {
            return '';
        }

        if ($classTable === 'class') {
            $stmt = $this->pdo->prepare('SELECT grade, class FROM `class` WHERE classID = :classID LIMIT 1');
            $stmt->execute(['classID' => $classId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return '';
            }
            $grade = $row['grade'] ?? '';
            $section = $row['class'] ?? '';
            $gradeLabel = $grade !== '' ? ('Grade ' . $grade) : '';
            return ($gradeLabel !== '' && $section !== '') ? ($gradeLabel . '-' . $section) : trim($gradeLabel . ' ' . $section);
        }

        $stmt = $this->pdo->prepare('SELECT grade, section, className FROM `classes` WHERE classID = :classID LIMIT 1');
        $stmt->execute(['classID' => $classId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return '';
        }
        $grade = $row['grade'] ?? '';
        $section = $row['section'] ?? ($row['className'] ?? '');
        $gradeLabel = $grade !== '' ? ('Grade ' . $grade) : '';
        return ($gradeLabel !== '' && $section !== '') ? ($gradeLabel . '-' . $section) : trim($gradeLabel . ' ' . $section);
    }

    public function getTeacherTimetableContext(int $userId): array
    {
        $userId = (int) $userId;
        if ($userId <= 0) {
            throw new InvalidArgumentException('Invalid userID');
        }

        $teacher = $this->getTeacherByUserId($userId);
        if (!$teacher) {
            throw new RuntimeException('Teacher record not found for this user');
        }

        $teacherId = (int) ($teacher['teacherID'] ?? 0);
        if ($teacherId <= 0) {
            throw new RuntimeException('Invalid teacherID');
        }

        $fullName = $this->getUserFullName($userId);

        $timeSlots = [
            ['time' => '07:30 - 08:30', 'period' => 1],
            ['time' => '08:30 - 09:30', 'period' => 2],
            ['time' => '09:30 - 10:30', 'period' => 3],
            ['time' => '10:30 - 11:30', 'period' => 4],
            ['time' => 'INTERVAL', 'period' => 'break'],
            ['time' => '11:50 - 12:50', 'period' => 5],
            ['time' => '12:50 - 13:30', 'period' => 6],
            ['time' => '13:30 - 14:30', 'period' => 7],
            ['time' => '14:30 - 15:10', 'period' => 8],
        ];

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $schedule = [];
        foreach ($days as $d) {
            $schedule[$d] = [1 => null, 2 => null, 3 => null, 4 => null, 5 => null, 6 => null, 7 => null, 8 => null];
        }

        $uniqueSubjects = [];
        $totalClasses = 0;
        $classesPerDay = array_fill_keys($days, 0);

        if ($this->tableExists('classTimetable')) {
            $subjectTable = $this->pickExistingTable(['subject', 'subjects']) ?? 'subject';

            $dayIdToName = $this->getDayIdToName();
            $periodIdToNum = $this->getPeriodIdToNumber();

            $sql = "
                SELECT
                    ct.dayID,
                    ct.periodID,
                    ct.subjectID,
                    ct.classID,
                    s.subjectName as subjectName
                FROM classTimetable ct
                LEFT JOIN `{$subjectTable}` s ON s.subjectID = ct.subjectID
                WHERE ct.teacherID = :teacherID
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['teacherID' => $teacherId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

            foreach ($rows as $r) {
                $dayId = (int) ($r['dayID'] ?? 0);
                $periodId = (int) ($r['periodID'] ?? 0);
                $subjectId = (int) ($r['subjectID'] ?? 0);
                $classId = (int) ($r['classID'] ?? 0);

                $dayName = $dayIdToName[$dayId] ?? null;
                $periodNum = $periodIdToNum[$periodId] ?? null;
                if (!$dayName || !$periodNum) {
                    continue;
                }
                if (!in_array($dayName, $days, true)) {
                    continue;
                }
                if ($periodNum < 1 || $periodNum > 8) {
                    continue;
                }

                $classLabel = $this->getClassLabelById($classId);
                $subjectName = (string) ($r['subjectName'] ?? '');

                $schedule[$dayName][$periodNum] = [
                    'class' => $classLabel !== '' ? $classLabel : ('Class ' . $classId),
                    'subject' => $subjectName !== '' ? $subjectName : ('Subject ' . $subjectId),
                    'classID' => $classId,
                    'subjectID' => $subjectId,
                ];

                $totalClasses++;
                $classesPerDay[$dayName] = ($classesPerDay[$dayName] ?? 0) + 1;
                if ($subjectId > 0) {
                    $uniqueSubjects[$subjectId] = true;
                }
            }
        }

        $subjectLabel = count($uniqueSubjects) === 1 ? '1 Subject' : (count($uniqueSubjects) > 1 ? 'Multiple' : '—');

        $teacherInfo = [
            'name' => $fullName !== '' ? $fullName : ('Teacher ' . $teacherId),
            'subject' => $subjectLabel,
            'employee_id' => $teacher['employee_id'] ?? null,
            'teacherID' => $teacherId,
        ];

        return [
            'teacherInfo' => $teacherInfo,
            'timeSlots' => $timeSlots,
            'timetable' => $schedule,
            'days' => $days,
            'totalClasses' => $totalClasses,
            'classesPerDay' => $classesPerDay,
        ];
    }
}
