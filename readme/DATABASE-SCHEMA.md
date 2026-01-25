# 📊 Iskole Database Schema Documentation

## Table of Contents

1. [Overview](#overview)
2. [Database Design](#database-design)
3. [Tables](#tables)
4. [Relationships](#relationships)
5. [Indexes](#indexes)
6. [Sample Queries](#sample-queries)

---

## 1. Overview {#overview}

The Iskole database follows a normalized relational design to store school management data efficiently.

### Database Information

- **Name:** `iskole_db`
- **Engine:** InnoDB
- **Character Set:** utf8mb4
- **Collation:** utf8mb4_unicode_ci

### Core Entities

- Users (Teachers, Students, Parents, Admins, MP)
- Classes & Subjects
- Attendance
- Exams & Marks
- Announcements
- Timetables
- Leave Requests

---

## 2. Database Design {#database-design}

### Entity Relationship Diagram

```
┌──────────┐       ┌──────────┐       ┌──────────┐
│   user   │───────│ userName │       │userAddress│
└────┬─────┘       └──────────┘       └──────────┘
     │
     ├──────────────────┬──────────────────┬──────────────┐
     │                  │                  │              │
┌────▼────┐      ┌──────▼──────┐   ┌─────▼─────┐  ┌─────▼─────┐
│ teacher │      │   student   │   │  parent   │  │   admin   │
└────┬────┘      └──────┬──────┘   └─────┬─────┘  └───────────┘
     │                  │                  │
     │           ┌──────▼──────┐          │
     │           │   classes   │          │
     │           └──────┬──────┘          │
     │                  │                  │
     │           ┌──────▼──────┐          │
     │           │  subjects   │          │
     │           └──────┬──────┘          │
     │                  │                  │
     │           ┌──────▼──────┐          │
     │           │   exams     │          │
     │           └──────┬──────┘          │
     │                  │                  │
     │           ┌──────▼──────┐          │
     │           │    marks    │          │
     │           └─────────────┘          │
     │                                     │
     ├──────────┬──────────────────────────┤
     │          │                          │
┌────▼────┐ ┌──▼──────┐          ┌────────▼────────┐
│attendance│ │timetable│          │  announcements  │
└─────────┘ └─────────┘          └─────────────────┘
```

---

## 3. Tables {#tables}

### 3.1 user

Core user table for all system users.

```sql
CREATE TABLE user (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role TINYINT NOT NULL COMMENT '1=MP, 2=Admin, 3=Teacher, 4=Student, 5=Parent',
    active TINYINT DEFAULT 1 COMMENT '1=Active, 0=Deleted',
    createDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    pwdChanged TINYINT DEFAULT 0,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_active (active)
);
```

**Columns:**

- `userID`: Primary key
- `email`: Unique login email
- `password`: Hashed password (bcrypt)
- `role`: User role (1-5)
- `active`: Soft delete flag
- `createDate`: Account creation date
- `pwdChanged`: Password change flag

---

### 3.2 userName

Stores user personal information.

```sql
CREATE TABLE userName (
    nameID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    firstName VARCHAR(100) NOT NULL,
    lastName VARCHAR(100) NOT NULL,
    gender ENUM('Male', 'Female', 'Other'),
    phone VARCHAR(20),
    dateOfBirth DATE,
    FOREIGN KEY (userID) REFERENCES user(userID) ON DELETE CASCADE,
    INDEX idx_userID (userID)
);
```

---

### 3.3 userAddress

Stores user address information.

```sql
CREATE TABLE userAddress (
    addressID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    address_line1 VARCHAR(255),
    address_line2 VARCHAR(255),
    address_line3 VARCHAR(255),
    FOREIGN KEY (userID) REFERENCES user(userID) ON DELETE CASCADE,
    INDEX idx_userID (userID)
);
```

---

### 3.4 students

Student-specific information.

```sql
CREATE TABLE students (
    studentID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    studentIDNumber VARCHAR(50) UNIQUE,
    classID INT,
    enrollmentDate DATE,
    FOREIGN KEY (userID) REFERENCES user(userID) ON DELETE CASCADE,
    FOREIGN KEY (classID) REFERENCES classes(classID) ON DELETE SET NULL,
    INDEX idx_userID (userID),
    INDEX idx_classID (classID),
    INDEX idx_studentIDNumber (studentIDNumber)
);
```

---

### 3.5 teachers

Teacher-specific information.

```sql
CREATE TABLE teachers (
    teacherID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    employeeID VARCHAR(50) UNIQUE,
    qualification VARCHAR(255),
    specialization VARCHAR(255),
    joinDate DATE,
    FOREIGN KEY (userID) REFERENCES user(userID) ON DELETE CASCADE,
    INDEX idx_userID (userID),
    INDEX idx_employeeID (employeeID)
);
```

---

### 3.6 parents

Parent-specific information.

```sql
CREATE TABLE parents (
    parentID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    occupation VARCHAR(100),
    FOREIGN KEY (userID) REFERENCES user(userID) ON DELETE CASCADE,
    INDEX idx_userID (userID)
);
```

---

### 3.7 parent_student

Links parents to their children.

```sql
CREATE TABLE parent_student (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parentID INT NOT NULL,
    studentID INT NOT NULL,
    relationship ENUM('Father', 'Mother', 'Guardian') NOT NULL,
    FOREIGN KEY (parentID) REFERENCES parents(parentID) ON DELETE CASCADE,
    FOREIGN KEY (studentID) REFERENCES students(studentID) ON DELETE CASCADE,
    UNIQUE KEY unique_parent_student (parentID, studentID),
    INDEX idx_parentID (parentID),
    INDEX idx_studentID (studentID)
);
```

---

### 3.8 classes

Class/Grade information.

```sql
CREATE TABLE classes (
    classID INT AUTO_INCREMENT PRIMARY KEY,
    className VARCHAR(100) NOT NULL,
    grade INT NOT NULL,
    section VARCHAR(10),
    academicYear VARCHAR(20),
    classTeacherID INT,
    FOREIGN KEY (classTeacherID) REFERENCES teachers(teacherID) ON DELETE SET NULL,
    INDEX idx_grade (grade),
    INDEX idx_academicYear (academicYear),
    INDEX idx_classTeacher (classTeacherID)
);
```

---

### 3.9 subjects

Subject information.

```sql
CREATE TABLE subjects (
    subjectID INT AUTO_INCREMENT PRIMARY KEY,
    subjectName VARCHAR(100) NOT NULL,
    subjectCode VARCHAR(20) UNIQUE,
    description TEXT,
    INDEX idx_subjectCode (subjectCode)
);
```

---

### 3.10 class_subjects

Links classes to subjects.

```sql
CREATE TABLE class_subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    classID INT NOT NULL,
    subjectID INT NOT NULL,
    teacherID INT,
    FOREIGN KEY (classID) REFERENCES classes(classID) ON DELETE CASCADE,
    FOREIGN KEY (subjectID) REFERENCES subjects(subjectID) ON DELETE CASCADE,
    FOREIGN KEY (teacherID) REFERENCES teachers(teacherID) ON DELETE SET NULL,
    UNIQUE KEY unique_class_subject (classID, subjectID),
    INDEX idx_classID (classID),
    INDEX idx_subjectID (subjectID),
    INDEX idx_teacherID (teacherID)
);
```

---

### 3.11 exams

Exam information.

```sql
CREATE TABLE exams (
    examID INT AUTO_INCREMENT PRIMARY KEY,
    examName VARCHAR(100) NOT NULL,
    examType ENUM('Monthly', 'Midterm', 'Final', 'Quiz') NOT NULL,
    academicYear VARCHAR(20),
    term INT,
    startDate DATE,
    endDate DATE,
    INDEX idx_academicYear (academicYear),
    INDEX idx_examType (examType)
);
```

---

### 3.12 exam_timetable

Exam schedule.

```sql
CREATE TABLE exam_timetable (
    timetableID INT AUTO_INCREMENT PRIMARY KEY,
    examID INT NOT NULL,
    subjectID INT NOT NULL,
    classID INT NOT NULL,
    examDate DATE NOT NULL,
    startTime TIME,
    endTime TIME,
    room VARCHAR(50),
    FOREIGN KEY (examID) REFERENCES exams(examID) ON DELETE CASCADE,
    FOREIGN KEY (subjectID) REFERENCES subjects(subjectID) ON DELETE CASCADE,
    FOREIGN KEY (classID) REFERENCES classes(classID) ON DELETE CASCADE,
    INDEX idx_examID (examID),
    INDEX idx_examDate (examDate),
    INDEX idx_classID (classID)
);
```

---

### 3.13 marks

Student exam marks.

```sql
CREATE TABLE marks (
    markID INT AUTO_INCREMENT PRIMARY KEY,
    studentID INT NOT NULL,
    examID INT NOT NULL,
    subjectID INT NOT NULL,
    marks DECIMAL(5,2),
    maxMarks DECIMAL(5,2) DEFAULT 100,
    grade VARCHAR(5),
    remarks TEXT,
    enteredBy INT,
    enteredDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (studentID) REFERENCES students(studentID) ON DELETE CASCADE,
    FOREIGN KEY (examID) REFERENCES exams(examID) ON DELETE CASCADE,
    FOREIGN KEY (subjectID) REFERENCES subjects(subjectID) ON DELETE CASCADE,
    FOREIGN KEY (enteredBy) REFERENCES teachers(teacherID) ON DELETE SET NULL,
    UNIQUE KEY unique_student_exam_subject (studentID, examID, subjectID),
    INDEX idx_studentID (studentID),
    INDEX idx_examID (examID),
    INDEX idx_subjectID (subjectID)
);
```

---

### 3.14 attendance

Daily attendance records.

```sql
CREATE TABLE attendance (
    attendanceID INT AUTO_INCREMENT PRIMARY KEY,
    studentID INT NOT NULL,
    classID INT NOT NULL,
    attendanceDate DATE NOT NULL,
    status ENUM('Present', 'Absent', 'Late', 'Excused') NOT NULL,
    remarks TEXT,
    markedBy INT,
    markedDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (studentID) REFERENCES students(studentID) ON DELETE CASCADE,
    FOREIGN KEY (classID) REFERENCES classes(classID) ON DELETE CASCADE,
    FOREIGN KEY (markedBy) REFERENCES teachers(teacherID) ON DELETE SET NULL,
    UNIQUE KEY unique_student_date (studentID, attendanceDate),
    INDEX idx_studentID (studentID),
    INDEX idx_classID (classID),
    INDEX idx_attendanceDate (attendanceDate),
    INDEX idx_status (status)
);
```

---

### 3.15 teacher_attendance

Teacher attendance records.

```sql
CREATE TABLE teacher_attendance (
    attendanceID INT AUTO_INCREMENT PRIMARY KEY,
    teacherID INT NOT NULL,
    attendanceDate DATE NOT NULL,
    status ENUM('Present', 'Absent', 'Leave', 'Half-Day') NOT NULL,
    checkInTime TIME,
    checkOutTime TIME,
    remarks TEXT,
    FOREIGN KEY (teacherID) REFERENCES teachers(teacherID) ON DELETE CASCADE,
    UNIQUE KEY unique_teacher_date (teacherID, attendanceDate),
    INDEX idx_teacherID (teacherID),
    INDEX idx_attendanceDate (attendanceDate)
);
```

---

### 3.16 leave_requests

Teacher leave requests.

```sql
CREATE TABLE leave_requests (
    requestID INT AUTO_INCREMENT PRIMARY KEY,
    teacherID INT NOT NULL,
    leaveType ENUM('Sick', 'Casual', 'Maternity', 'Other') NOT NULL,
    startDate DATE NOT NULL,
    endDate DATE NOT NULL,
    reason TEXT,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    requestDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    approvedBy INT,
    approvalDate DATETIME,
    FOREIGN KEY (teacherID) REFERENCES teachers(teacherID) ON DELETE CASCADE,
    FOREIGN KEY (approvedBy) REFERENCES user(userID) ON DELETE SET NULL,
    INDEX idx_teacherID (teacherID),
    INDEX idx_status (status),
    INDEX idx_startDate (startDate)
);
```

---

### 3.17 announcements

System announcements.

```sql
CREATE TABLE announcements (
    announcementID INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    targetAudience ENUM('All', 'Teachers', 'Students', 'Parents') DEFAULT 'All',
    priority ENUM('Low', 'Normal', 'High') DEFAULT 'Normal',
    publishDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    expiryDate DATETIME,
    createdBy INT NOT NULL,
    active TINYINT DEFAULT 1,
    FOREIGN KEY (createdBy) REFERENCES user(userID) ON DELETE CASCADE,
    INDEX idx_targetAudience (targetAudience),
    INDEX idx_publishDate (publishDate),
    INDEX idx_active (active)
);
```

---

### 3.18 timetable

Class timetables.

```sql
CREATE TABLE timetable (
    timetableID INT AUTO_INCREMENT PRIMARY KEY,
    classID INT NOT NULL,
    subjectID INT NOT NULL,
    teacherID INT,
    dayOfWeek ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday') NOT NULL,
    period INT NOT NULL,
    startTime TIME,
    endTime TIME,
    room VARCHAR(50),
    FOREIGN KEY (classID) REFERENCES classes(classID) ON DELETE CASCADE,
    FOREIGN KEY (subjectID) REFERENCES subjects(subjectID) ON DELETE CASCADE,
    FOREIGN KEY (teacherID) REFERENCES teachers(teacherID) ON DELETE SET NULL,
    UNIQUE KEY unique_class_day_period (classID, dayOfWeek, period),
    INDEX idx_classID (classID),
    INDEX idx_teacherID (teacherID),
    INDEX idx_dayOfWeek (dayOfWeek)
);
```

---

### 3.19 behavior_reports

Student behavior records.

```sql
CREATE TABLE behavior_reports (
    reportID INT AUTO_INCREMENT PRIMARY KEY,
    studentID INT NOT NULL,
    teacherID INT NOT NULL,
    reportDate DATE NOT NULL,
    behaviorType ENUM('Excellent', 'Good', 'Poor', 'Disciplinary') NOT NULL,
    description TEXT NOT NULL,
    actionTaken TEXT,
    FOREIGN KEY (studentID) REFERENCES students(studentID) ON DELETE CASCADE,
    FOREIGN KEY (teacherID) REFERENCES teachers(teacherID) ON DELETE CASCADE,
    INDEX idx_studentID (studentID),
    INDEX idx_reportDate (reportDate),
    INDEX idx_behaviorType (behaviorType)
);
```

---

### 3.20 materials

Study materials.

```sql
CREATE TABLE materials (
    materialID INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    subjectID INT NOT NULL,
    classID INT,
    uploadedBy INT NOT NULL,
    fileName VARCHAR(255),
    filePath VARCHAR(500),
    fileType VARCHAR(50),
    uploadDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (subjectID) REFERENCES subjects(subjectID) ON DELETE CASCADE,
    FOREIGN KEY (classID) REFERENCES classes(classID) ON DELETE SET NULL,
    FOREIGN KEY (uploadedBy) REFERENCES teachers(teacherID) ON DELETE CASCADE,
    INDEX idx_subjectID (subjectID),
    INDEX idx_classID (classID),
    INDEX idx_uploadDate (uploadDate)
);
```

---

## 4. Relationships {#relationships}

### One-to-One Relationships

- `user` ↔ `userName` (1:1)
- `user` ↔ `userAddress` (1:1)

### One-to-Many Relationships

- `user` → `students` (1:N)
- `user` → `teachers` (1:N)
- `user` → `parents` (1:N)
- `classes` → `students` (1:N)
- `exams` → `marks` (1:N)
- `students` → `attendance` (1:N)
- `teachers` → `teacher_attendance` (1:N)

### Many-to-Many Relationships

- `parents` ↔ `students` (through `parent_student`)
- `classes` ↔ `subjects` (through `class_subjects`)
- `teachers` ↔ `classes` (through `class_subjects`)

---

## 5. Indexes {#indexes}

### Primary Keys

All tables have auto-incrementing PRIMARY KEY on `*ID` column.

### Foreign Key Indexes

Automatically created for all FOREIGN KEY constraints.

### Custom Indexes

```sql
-- User lookups
CREATE INDEX idx_user_email ON user(email);
CREATE INDEX idx_user_role ON user(role);
CREATE INDEX idx_user_active ON user(active);

-- Student searches
CREATE INDEX idx_student_number ON students(studentIDNumber);
CREATE INDEX idx_student_class ON students(classID);

-- Attendance queries
CREATE INDEX idx_attendance_date ON attendance(attendanceDate);
CREATE INDEX idx_attendance_status ON attendance(status);

-- Mark queries
CREATE INDEX idx_marks_student ON marks(studentID);
CREATE INDEX idx_marks_exam ON marks(examID);

-- Timetable lookups
CREATE INDEX idx_timetable_class_day ON timetable(classID, dayOfWeek);
```

---

## 6. Sample Queries {#sample-queries}

### Get User with Full Details

```sql
SELECT
    u.userID,
    u.email,
    u.role,
    un.firstName,
    un.lastName,
    un.gender,
    un.phone,
    un.dateOfBirth,
    ua.address_line1,
    ua.address_line2,
    ua.address_line3
FROM user u
LEFT JOIN userName un ON u.userID = un.userID
LEFT JOIN userAddress ua ON u.userID = ua.userID
WHERE u.userID = 123 AND u.active = 1;
```

### Get Student with Class Info

```sql
SELECT
    s.studentID,
    s.studentIDNumber,
    un.firstName,
    un.lastName,
    c.className,
    c.grade,
    c.section
FROM students s
JOIN user u ON s.userID = u.userID
JOIN userName un ON u.userID = un.userID
LEFT JOIN classes c ON s.classID = c.classID
WHERE s.studentID = 5;
```

### Get Student Attendance for Month

```sql
SELECT
    attendanceDate,
    status,
    remarks
FROM attendance
WHERE studentID = 10
  AND attendanceDate BETWEEN '2025-11-01' AND '2025-11-30'
ORDER BY attendanceDate DESC;
```

### Get Exam Marks for Student

```sql
SELECT
    e.examName,
    s.subjectName,
    m.marks,
    m.maxMarks,
    m.grade,
    ROUND((m.marks / m.maxMarks) * 100, 2) as percentage
FROM marks m
JOIN exams e ON m.examID = e.examID
JOIN subjects s ON m.subjectID = s.subjectID
WHERE m.studentID = 15
ORDER BY e.startDate DESC, s.subjectName;
```

### Get Class Timetable

```sql
SELECT
    t.dayOfWeek,
    t.period,
    t.startTime,
    t.endTime,
    s.subjectName,
    CONCAT(un.firstName, ' ', un.lastName) as teacherName,
    t.room
FROM timetable t
JOIN subjects s ON t.subjectID = s.subjectID
LEFT JOIN teachers te ON t.teacherID = te.teacherID
LEFT JOIN user u ON te.userID = u.userID
LEFT JOIN userName un ON u.userID = un.userID
WHERE t.classID = 8
ORDER BY
    FIELD(t.dayOfWeek, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'),
    t.period;
```

### Get Teacher's Classes

```sql
SELECT DISTINCT
    c.classID,
    c.className,
    c.grade,
    c.section,
    s.subjectName
FROM class_subjects cs
JOIN classes c ON cs.classID = c.classID
JOIN subjects s ON cs.subjectID = s.subjectID
WHERE cs.teacherID = 5;
```

### Get Pending Leave Requests

```sql
SELECT
    lr.requestID,
    CONCAT(un.firstName, ' ', un.lastName) as teacherName,
    lr.leaveType,
    lr.startDate,
    lr.endDate,
    lr.reason,
    lr.requestDate
FROM leave_requests lr
JOIN teachers t ON lr.teacherID = t.teacherID
JOIN user u ON t.userID = u.userID
JOIN userName un ON u.userID = un.userID
WHERE lr.status = 'Pending'
ORDER BY lr.requestDate DESC;
```

### Get Active Announcements

```sql
SELECT
    announcementID,
    title,
    content,
    targetAudience,
    priority,
    publishDate,
    CONCAT(un.firstName, ' ', un.lastName) as createdBy
FROM announcements a
JOIN user u ON a.createdBy = u.userID
JOIN userName un ON u.userID = un.userID
WHERE a.active = 1
  AND a.publishDate <= NOW()
  AND (a.expiryDate IS NULL OR a.expiryDate >= NOW())
ORDER BY a.priority DESC, a.publishDate DESC;
```

### Get Parent's Children

```sql
SELECT
    s.studentID,
    s.studentIDNumber,
    un.firstName,
    un.lastName,
    c.className,
    ps.relationship
FROM parent_student ps
JOIN students s ON ps.studentID = s.studentID
JOIN user u ON s.userID = u.userID
JOIN userName un ON u.userID = un.userID
LEFT JOIN classes c ON s.classID = c.classID
WHERE ps.parentID = 20;
```

### Calculate Class Average for Exam

```sql
SELECT
    s.subjectName,
    COUNT(m.markID) as totalStudents,
    ROUND(AVG(m.marks), 2) as averageMarks,
    MAX(m.marks) as highestMarks,
    MIN(m.marks) as lowestMarks
FROM marks m
JOIN subjects s ON m.subjectID = s.subjectID
JOIN students st ON m.studentID = st.studentID
WHERE m.examID = 3
  AND st.classID = 10
GROUP BY s.subjectID, s.subjectName
ORDER BY s.subjectName;
```

---

## Database Maintenance

### Backup Command

```bash
# Full backup
mysqldump -u root -p iskole_db > backup_$(date +%Y%m%d).sql

# Backup specific tables
mysqldump -u root -p iskole_db user userName students > users_backup.sql
```

### Restore Command

```bash
mysql -u root -p iskole_db < backup_20251121.sql
```

### Optimize Tables

```sql
OPTIMIZE TABLE user, students, teachers, attendance, marks;
```

---

**Last Updated:** November 21, 2025
**Version:** 1.0.0
