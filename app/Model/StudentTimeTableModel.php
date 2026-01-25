<?php

require_once __DIR__ . '/../Core/Database.php';

class StudentTimeTableModel
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

    private function getStudentRecordByUserId(int $userId): ?array
    {
        $studentTable = $this->pickExistingTable(['students', 'student']);
        if (!$studentTable) {
            return null;
        }

        // Try common student-ID / registration number column names
        $possibleRegCols = ['studentIDNumber', 'reg_no', 'regNo', 'registrationNo', 'registrationNumber'];
        $possibleStudentIdCols = ['studentID', 'studentId', 'student_id'];
        $cols = [];
        try {
            $colsInfo = $this->pdo->query("DESCRIBE `{$studentTable}`")->fetchAll(PDO::FETCH_ASSOC);
            $cols = array_map(static fn($c) => (string) $c['Field'], $colsInfo);
        } catch (Throwable $e) {
            $cols = [];
        }

        $studentIdCol = null;
        foreach ($possibleStudentIdCols as $c) {
            if (in_array($c, $cols, true)) {
                $studentIdCol = $c;
                break;
            }
        }

        $regCol = null;
        foreach ($possibleRegCols as $c) {
            if (in_array($c, $cols, true)) {
                $regCol = $c;
                break;
            }
        }

        $selectStudentId = $studentIdCol ? ", `{$studentIdCol}` as studentID" : ", NULL as studentID";
        $selectReg = $regCol ? ", `{$regCol}` as reg_no" : ", NULL as reg_no";

        $stmt = $this->pdo->prepare("SELECT classID{$selectStudentId} {$selectReg} FROM `{$studentTable}` WHERE userID = :userID LIMIT 1");
        $stmt->execute(['userID' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private function getStudentRecordByStudentId(int $studentId): ?array
    {
        $studentTable = $this->pickExistingTable(['students', 'student']);
        if (!$studentTable) {
            return null;
        }

        $possibleRegCols = ['studentIDNumber', 'reg_no', 'regNo', 'registrationNo', 'registrationNumber'];
        $possibleStudentIdCols = ['studentID', 'studentId', 'student_id'];

        $cols = [];
        try {
            $colsInfo = $this->pdo->query("DESCRIBE `{$studentTable}`")->fetchAll(PDO::FETCH_ASSOC);
            $cols = array_map(static fn($c) => (string) $c['Field'], $colsInfo);
        } catch (Throwable $e) {
            $cols = [];
        }

        $studentIdCol = null;
        foreach ($possibleStudentIdCols as $c) {
            if (in_array($c, $cols, true)) {
                $studentIdCol = $c;
                break;
            }
        }
        if (!$studentIdCol) {
            // Most schemas use `studentID`
            $studentIdCol = 'studentID';
        }

        $regCol = null;
        foreach ($possibleRegCols as $c) {
            if (in_array($c, $cols, true)) {
                $regCol = $c;
                break;
            }
        }

        $selectStudentId = $studentIdCol ? ", `{$studentIdCol}` as studentID" : ", NULL as studentID";
        $selectReg = $regCol ? ", `{$regCol}` as reg_no" : ", NULL as reg_no";

        $stmt = $this->pdo->prepare(
            "SELECT userID, classID{$selectStudentId} {$selectReg} FROM `{$studentTable}` WHERE `{$studentIdCol}` = :studentID LIMIT 1"
        );
        $stmt->execute(['studentID' => (int) $studentId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private function getUserFullName(int $userId): string
    {
        $first = $this->firstNameCol;
        $last = $this->lastNameCol;

        if (!$this->tableExists($this->nameTable)) {
            return '';
        }

        $stmt = $this->pdo->prepare("SELECT TRIM(CONCAT(COALESCE(n.`{$first}`,''), ' ', COALESCE(n.`{$last}`,''))) as fullName FROM `{$this->nameTable}` n WHERE n.userID = :userID LIMIT 1");
        $stmt->execute(['userID' => $userId]);
        $name = $stmt->fetchColumn();
        return trim((string) $name);
    }

    private function getClassLabel(int $classId): string
    {
        // Codebase uses `class` table in several places, but support `classes` too.
        $classTable = $this->pickExistingTable(['class', 'classes']);
        if (!$classTable) {
            return '';
        }

        if ($classTable === 'class') {
            // Expected columns in this repo: classID, grade, class
            $stmt = $this->pdo->prepare('SELECT grade, class FROM `class` WHERE classID = :classID LIMIT 1');
            $stmt->execute(['classID' => $classId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return '';
            }
            $grade = $row['grade'] ?? '';
            $section = $row['class'] ?? '';
            $gradeLabel = $grade !== '' ? ('Grade ' . $grade) : '';
            if ($gradeLabel !== '' && $section !== '') {
                return $gradeLabel . '-' . $section;
            }
            return trim($gradeLabel . ' ' . $section);
        }

        // Fallback to DATABASE-SCHEMA.md style: classes(className, grade, section)
        $stmt = $this->pdo->prepare('SELECT grade, section, className FROM `classes` WHERE classID = :classID LIMIT 1');
        $stmt->execute(['classID' => $classId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return '';
        }

        $grade = $row['grade'] ?? '';
        $section = $row['section'] ?? ($row['className'] ?? '');
        $gradeLabel = $grade !== '' ? ('Grade ' . $grade) : '';
        if ($gradeLabel !== '' && $section !== '') {
            return $gradeLabel . '-' . $section;
        }
        return trim($gradeLabel . ' ' . $section);
    }

    private function periodNumberToRowIndexMap(): array
    {
        // Must match the timetable templates where row index 4 is INTERVAL.
        return [
            1 => 0,
            2 => 1,
            3 => 2,
            4 => 3,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
        ];
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
        // Matches TimeTableModel behavior: order by periodID => maps to 1..N
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

    public function getStudentTimetableContext(int $userId, ?int $fallbackClassId = null): array
    {
        $userId = (int) $userId;
        if ($userId <= 0) {
            throw new InvalidArgumentException('Invalid userID');
        }

        $fullName = $this->getUserFullName($userId);

        $studentRow = $this->getStudentRecordByUserId($userId);
        $classId = $studentRow ? (int) ($studentRow['classID'] ?? 0) : 0;
        $regNo = $studentRow ? ($studentRow['reg_no'] ?? '') : '';
        $studentId = $studentRow ? ($studentRow['studentID'] ?? '') : '';

        if ($classId <= 0 && $fallbackClassId !== null) {
            $classId = (int) $fallbackClassId;
        }

        $classLabel = $classId > 0 ? $this->getClassLabel($classId) : '';

        $timetable = $this->buildTimetableForClass($classId);

        // Stats
        $uniqueSubjects = [];
        $uniqueTeachers = [];
        $totalClasses = 0;
        foreach ($timetable['schedule'] as $day => $cells) {
            foreach ($cells as $cell) {
                if (!$cell) {
                    continue;
                }
                $totalClasses++;
                if (isset($cell['subjectID'])) {
                    $uniqueSubjects[(int) $cell['subjectID']] = true;
                }
                if (isset($cell['teacherID'])) {
                    $uniqueTeachers[(int) $cell['teacherID']] = true;
                }
            }
        }

        $stats = [
            'total_periods' => 40,
            'subjects_count' => count($uniqueSubjects),
            'teachers_count' => count($uniqueTeachers),
        ];

        $studentInfo = [
            'name' => $fullName !== '' ? $fullName : ('User ' . $userId),
            'class' => $classLabel !== '' ? $classLabel : '—',
            // Used by studentAttendance template.
            'reg_no' => ($regNo !== '' ? $regNo : ($studentId !== '' ? $studentId : '—')),
            // Used by studentTimeTable template for the "ID" badge.
            'stu_id' => ($studentId !== '' ? $studentId : ($regNo !== '' ? $regNo : '—')),
            'classID' => $classId,
        ];

        return [
            'studentInfo' => $studentInfo,
            'timetable' => $timetable,
            'stats' => $stats,
        ];
    }

    public function getStudentInfoForUserId(int $userId, ?int $fallbackClassId = null): array
    {
        $userId = (int) $userId;
        if ($userId <= 0) {
            return [
                'name' => '—',
                'class' => '—',
                'reg_no' => '—',
                'stu_id' => '—',
                'classID' => 0,
            ];
        }

        $fullName = $this->getUserFullName($userId);

        $studentRow = $this->getStudentRecordByUserId($userId);
        $classId = $studentRow ? (int) ($studentRow['classID'] ?? 0) : 0;
        $regNo = $studentRow ? ($studentRow['reg_no'] ?? '') : '';
        $studentId = $studentRow ? ($studentRow['studentID'] ?? '') : '';

        if ($classId <= 0 && $fallbackClassId !== null) {
            $classId = (int) $fallbackClassId;
        }

        $classLabel = $classId > 0 ? $this->getClassLabel($classId) : '';

        return [
            'name' => $fullName !== '' ? $fullName : ('User ' . $userId),
            'class' => $classLabel !== '' ? $classLabel : '—',
            'reg_no' => ($regNo !== '' ? $regNo : ($studentId !== '' ? $studentId : '—')),
            'stu_id' => ($studentId !== '' ? $studentId : ($regNo !== '' ? $regNo : '—')),
            'classID' => $classId,
        ];
    }

    public function getStudentTimetableContextByStudentId(int $studentId, ?int $fallbackClassId = null): array
    {
        $studentId = (int) $studentId;
        if ($studentId <= 0) {
            throw new InvalidArgumentException('Invalid studentID');
        }

        $studentRow = $this->getStudentRecordByStudentId($studentId);
        if (!$studentRow) {
            throw new RuntimeException('Student not found');
        }

        $userId = (int) ($studentRow['userID'] ?? 0);
        $classId = (int) ($studentRow['classID'] ?? 0);
        $regNo = (string) ($studentRow['reg_no'] ?? '');
        $studentIdVal = (string) ($studentRow['studentID'] ?? $studentId);

        if ($classId <= 0 && $fallbackClassId !== null) {
            $classId = (int) $fallbackClassId;
        }

        $fullName = $userId > 0 ? $this->getUserFullName($userId) : '';
        $classLabel = $classId > 0 ? $this->getClassLabel($classId) : '';
        $timetable = $this->buildTimetableForClass($classId);

        $uniqueSubjects = [];
        $uniqueTeachers = [];
        foreach ($timetable['schedule'] as $cells) {
            foreach ($cells as $cell) {
                if (!$cell) {
                    continue;
                }
                if (isset($cell['subjectID'])) {
                    $uniqueSubjects[(int) $cell['subjectID']] = true;
                }
                if (isset($cell['teacherID'])) {
                    $uniqueTeachers[(int) $cell['teacherID']] = true;
                }
            }
        }

        $stats = [
            'total_periods' => 40,
            'subjects_count' => count($uniqueSubjects),
            'teachers_count' => count($uniqueTeachers),
        ];

        $studentInfo = [
            'name' => $fullName !== '' ? $fullName : ('Student ' . $studentId),
            'class' => $classLabel !== '' ? $classLabel : '—',
            'reg_no' => ($regNo !== '' ? $regNo : ($studentIdVal !== '' ? $studentIdVal : '—')),
            'stu_id' => ($studentIdVal !== '' ? $studentIdVal : ($regNo !== '' ? $regNo : '—')),
            'classID' => $classId,
        ];

        return [
            'studentInfo' => $studentInfo,
            'timetable' => $timetable,
            'stats' => $stats,
        ];
    }

    private function buildTimetableForClass(int $classId): array
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $periods = [
            ['time' => '07:50 - 08:30', 'period' => 1],
            ['time' => '08:30 - 09:10', 'period' => 2],
            ['time' => '09:10 - 09:50', 'period' => 3],
            ['time' => '09:50 - 10:30', 'period' => 4],
            ['time' => '10:30 - 10:50', 'period' => 'INTERVAL'],
            ['time' => '10:50 - 11:30', 'period' => 5],
            ['time' => '11:30 - 12:10', 'period' => 6],
            ['time' => '12:10 - 12:50', 'period' => 7],
            ['time' => '12:50 - 01:30', 'period' => 8],
        ];

        $schedule = [];
        foreach ($days as $d) {
            $schedule[$d] = array_fill(0, 9, null);
            $schedule[$d][4] = null; // interval row
        }

        if ($classId <= 0 || !$this->tableExists('classTimetable')) {
            return ['periods' => $periods, 'schedule' => $schedule];
        }

        $subjectTable = $this->pickExistingTable(['subject', 'subjects']) ?? 'subject';

        $dayIdToName = $this->getDayIdToName();
        $periodIdToNum = $this->getPeriodIdToNumber();
        $periodNumToRow = $this->periodNumberToRowIndexMap();

        $first = $this->firstNameCol;
        $last = $this->lastNameCol;
        $nameTable = $this->nameTable;

        $sql = "
            SELECT
                ct.dayID,
                ct.periodID,
                ct.subjectID,
                ct.teacherID,
                s.subjectName as subjectName,
                TRIM(CONCAT(COALESCE(n.`{$first}`,''), ' ', COALESCE(n.`{$last}`,''))) as teacherName
            FROM classTimetable ct
            LEFT JOIN `{$subjectTable}` s ON s.subjectID = ct.subjectID
            LEFT JOIN teachers t ON t.teacherID = ct.teacherID
            LEFT JOIN user u ON u.userID = t.userID
            LEFT JOIN `{$nameTable}` n ON n.userID = u.userID
            WHERE ct.classID = :classID
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['classID' => (int) $classId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        foreach ($rows as $r) {
            $dayId = (int) ($r['dayID'] ?? 0);
            $periodId = (int) ($r['periodID'] ?? 0);
            $subjectId = (int) ($r['subjectID'] ?? 0);
            $teacherId = (int) ($r['teacherID'] ?? 0);

            $dayName = $dayIdToName[$dayId] ?? null;
            $periodNum = $periodIdToNum[$periodId] ?? null;
            if (!$dayName || !$periodNum) {
                continue;
            }
            if (!in_array($dayName, $days, true)) {
                continue;
            }
            $rowIndex = $periodNumToRow[$periodNum] ?? null;
            if ($rowIndex === null) {
                continue;
            }

            $subjectName = (string) ($r['subjectName'] ?? '');
            $teacherName = trim((string) ($r['teacherName'] ?? ''));
            if ($teacherName === '') {
                $teacherName = $teacherId > 0 ? ('Teacher ' . $teacherId) : '';
            }

            $schedule[$dayName][$rowIndex] = [
                'subject' => $subjectName !== '' ? $subjectName : ('Subject ' . $subjectId),
                'teacher' => $teacherName,
                'subjectID' => $subjectId,
                'teacherID' => $teacherId,
            ];
        }

        return ['periods' => $periods, 'schedule' => $schedule];
    }
}
