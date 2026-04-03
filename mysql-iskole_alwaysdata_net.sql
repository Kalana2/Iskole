-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql-iskole.alwaysdata.net
-- Generation Time: Apr 03, 2026 at 04:46 AM
-- Server version: 10.11.15-MariaDB
-- PHP Version: 8.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `iskole_db`
--
CREATE DATABASE IF NOT EXISTS `iskole_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `iskole_db`;

-- --------------------------------------------------------

--
-- Table structure for table `absentReasons`
--

CREATE TABLE `absentReasons` (
  `reasonID` int(11) NOT NULL,
  `parentID` int(11) DEFAULT NULL,
  `studentID` int(11) DEFAULT NULL,
  `teacherID` int(11) DEFAULT NULL,
  `reason` varchar(500) DEFAULT NULL,
  `fromDate` date DEFAULT NULL,
  `toDate` date DEFAULT NULL,
  `acknowledgedBy` int(11) DEFAULT NULL,
  `submittedAt` timestamp NULL DEFAULT current_timestamp(),
  `acknowledgedDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `announcement_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `published_by` int(11) NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `admin` tinyint(1) DEFAULT 0,
  `mp` tinyint(1) DEFAULT 0,
  `teacher` tinyint(1) DEFAULT 0,
  `parent` tinyint(1) DEFAULT 0,
  `student` tinyint(1) DEFAULT 0,
  `deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `classID` int(11) NOT NULL,
  `grade` int(11) DEFAULT NULL,
  `class` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `classTimetable`
--

CREATE TABLE `classTimetable` (
  `id` int(11) NOT NULL,
  `teacherID` int(11) NOT NULL,
  `dayID` int(11) NOT NULL,
  `periodID` int(11) NOT NULL,
  `classID` int(11) NOT NULL,
  `subjectID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `examTimeTable`
--

CREATE TABLE `examTimeTable` (
  `timeTableID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `visibility` tinyint(1) DEFAULT 1,
  `grade` varchar(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `submittedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `leaveRequests`
--

CREATE TABLE `leaveRequests` (
  `id` int(11) NOT NULL,
  `teacherUserID` int(11) NOT NULL,
  `dateFrom` date NOT NULL,
  `dateTo` date NOT NULL,
  `leaveType` varchar(50) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `managerUserID` int(11) DEFAULT NULL,
  `managerComment` text DEFAULT NULL,
  `decidedAt` datetime DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `mpId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `nic` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

CREATE TABLE `marks` (
  `markID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `teacherID` int(11) NOT NULL,
  `subjectID` int(11) NOT NULL,
  `term` tinyint(4) NOT NULL CHECK (`term` between 1 and 3),
  `marks` decimal(5,2) NOT NULL CHECK (`marks` between 0 and 100),
  `gradeLetter` char(1) DEFAULT NULL,
  `enteredDate` datetime DEFAULT current_timestamp(),
  `updatedDate` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `material`
--

CREATE TABLE `material` (
  `materialID` int(11) NOT NULL,
  `teacherID` int(11) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `class` varchar(10) NOT NULL,
  `subjectID` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `file` varchar(300) NOT NULL,
  `date` timestamp NULL DEFAULT current_timestamp(),
  `visibility` tinyint(4) DEFAULT 1,
  `deleted` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `userID` int(11) NOT NULL,
  `parentID` int(11) NOT NULL,
  `relationshipType` varchar(50) NOT NULL,
  `studentID` int(11) NOT NULL,
  `nic` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `periods`
--

CREATE TABLE `periods` (
  `periodID` int(11) NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `reliefAssignments`
--

CREATE TABLE `reliefAssignments` (
  `reliefID` int(11) NOT NULL,
  `timetableID` int(11) NOT NULL,
  `reliefTeacherID` int(11) NOT NULL,
  `reliefDate` date NOT NULL,
  `dayID` int(11) NOT NULL,
  `periodID` int(11) NOT NULL,
  `status` enum('assigned','cancelled','completed') DEFAULT 'assigned',
  `createdBy` int(11) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `id` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `teacherID` int(11) NOT NULL,
  `report_type` varchar(50) NOT NULL,
  `category` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `report_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `roleID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `roleName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schoolDays`
--

CREATE TABLE `schoolDays` (
  `id` int(11) NOT NULL,
  `day` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `studentAttendance`
--

CREATE TABLE `studentAttendance` (
  `attendanceID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `userID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `classID` int(11) NOT NULL,
  `gradeID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subjectID` int(11) NOT NULL,
  `subjectName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `targetAudience`
--

CREATE TABLE `targetAudience` (
  `audienceID` int(11) NOT NULL,
  `audienceName` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `teacherAttendance`
--

CREATE TABLE `teacherAttendance` (
  `attendanceID` int(11) NOT NULL,
  `teacherID` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `userID` int(11) NOT NULL,
  `teacherID` int(11) NOT NULL,
  `nic` varchar(50) DEFAULT NULL,
  `subjectID` int(11) DEFAULT NULL,
  `classID` int(11) DEFAULT NULL,
  `grade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `createDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `dateOfBirth` date DEFAULT NULL,
  `password` varchar(400) DEFAULT NULL,
  `pwdChanged` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- --------------------------------------------------------

--
-- Table structure for table `userAddress`
--

CREATE TABLE `userAddress` (
  `userID` int(11) NOT NULL,
  `address_line1` varchar(200) NOT NULL,
  `address_line2` varchar(200) NOT NULL,
  `address_line3` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `userName`
--

CREATE TABLE `userName` (
  `userID` int(11) NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `userRoles`
--

CREATE TABLE `userRoles` (
  `roleID` int(11) NOT NULL,
  `roleName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
--
-- --------------------------------------------------------

--
-- Minimal seed data for initial admin account
--

INSERT INTO `userRoles` (`roleID`, `roleName`) VALUES
(0, 'Admin'),
(1, 'MP'),
(2, 'Teacher'),
(3, 'Student'),
(4, 'Parent');

INSERT INTO `user` (`userID`, `gender`, `email`, `phone`, `createDate`, `role`, `active`, `dateOfBirth`, `password`, `pwdChanged`) VALUES
(1, 'Male', 'admin@gmail.com', '071123231', curdate(), 0, 1, '2001-05-14', '$2y$10$0P35BO/EclquW8mApVR7MeYAIgZLEGE5CT/LNo.6d9f6FYBG1xEjC', 1);

INSERT INTO `userName` (`userID`, `firstName`, `lastName`) VALUES
(1, 'System', 'Administrator');

INSERT INTO `userAddress` (`userID`, `address_line1`, `address_line2`, `address_line3`) VALUES
(1, 'line 1', 'line 2', 'city');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absentReasons`
--
ALTER TABLE `absentReasons`
  ADD PRIMARY KEY (`reasonID`),
  ADD KEY `studentID` (`studentID`),
  ADD KEY `parentID` (`parentID`),
  ADD KEY `teacherID` (`teacherID`);

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`announcement_id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`classID`);

--
-- Indexes for table `classTimetable`
--
ALTER TABLE `classTimetable`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dayID` (`dayID`,`periodID`,`classID`),
  ADD UNIQUE KEY `teacherID` (`teacherID`,`dayID`,`periodID`),
  ADD KEY `classID` (`classID`),
  ADD KEY `periodID` (`periodID`),
  ADD KEY `subjectID` (`subjectID`);

--
-- Indexes for table `examTimeTable`
--
ALTER TABLE `examTimeTable`
  ADD PRIMARY KEY (`timeTableID`),
  ADD KEY `fk_examTimeTable_user` (`userID`);

--
-- Indexes for table `leaveRequests`
--
ALTER TABLE `leaveRequests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacherUserID` (`teacherUserID`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`mpId`),
  ADD UNIQUE KEY `userId` (`userId`),
  ADD KEY `fk_MP_user` (`userId`) USING BTREE;

--
-- Indexes for table `marks`
--
ALTER TABLE `marks`
  ADD PRIMARY KEY (`markID`),
  ADD UNIQUE KEY `unique_mark_entry` (`studentID`,`subjectID`,`term`),
  ADD KEY `teacherID` (`teacherID`),
  ADD KEY `subjectID` (`subjectID`);

--
-- Indexes for table `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`materialID`),
  ADD KEY `teacherID` (`teacherID`),
  ADD KEY `fk_subject` (`subjectID`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`parentID`),
  ADD KEY `studentID` (`studentID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `periods`
--
ALTER TABLE `periods`
  ADD PRIMARY KEY (`periodID`);

--
-- Indexes for table `reliefAssignments`
--
ALTER TABLE `reliefAssignments`
  ADD PRIMARY KEY (`reliefID`),
  ADD UNIQUE KEY `uq_relief_unique` (`timetableID`,`reliefDate`),
  ADD KEY `fk_relief_teacher` (`reliefTeacherID`),
  ADD KEY `fk_relief_createdBy` (`createdBy`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`roleID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `schoolDays`
--
ALTER TABLE `schoolDays`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `day` (`day`);

--
-- Indexes for table `studentAttendance`
--
ALTER TABLE `studentAttendance`
  ADD PRIMARY KEY (`attendanceID`),
  ADD UNIQUE KEY `studentID` (`studentID`,`attendance_date`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`studentID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `classID` (`classID`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subjectID`);

--
-- Indexes for table `targetAudience`
--
ALTER TABLE `targetAudience`
  ADD PRIMARY KEY (`audienceID`);

--
-- Indexes for table `teacherAttendance`
--
ALTER TABLE `teacherAttendance`
  ADD PRIMARY KEY (`attendanceID`),
  ADD UNIQUE KEY `teacherID` (`teacherID`,`attendance_date`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacherID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `subjectID` (`subjectID`),
  ADD KEY `fk_classID` (`classID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `userID` (`userID`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_user_role` (`role`);

--
-- Indexes for table `userAddress`
--
ALTER TABLE `userAddress`
  ADD PRIMARY KEY (`userID`,`address_line1`,`address_line2`,`address_line3`);

--
-- Indexes for table `userName`
--
ALTER TABLE `userName`
  ADD PRIMARY KEY (`userID`,`firstName`,`lastName`);

--
-- Indexes for table `userRoles`
--
ALTER TABLE `userRoles`
  ADD PRIMARY KEY (`roleID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absentReasons`
--
ALTER TABLE `absentReasons`
  MODIFY `reasonID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `classID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `classTimetable`
--
ALTER TABLE `classTimetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- AUTO_INCREMENT for table `examTimeTable`
--
ALTER TABLE `examTimeTable`
  MODIFY `timeTableID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `leaveRequests`
--
ALTER TABLE `leaveRequests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `mpId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `marks`
--
ALTER TABLE `marks`
  MODIFY `markID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `materialID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `parentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `periods`
--
ALTER TABLE `periods`
  MODIFY `periodID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reliefAssignments`
--
ALTER TABLE `reliefAssignments`
  MODIFY `reliefID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `roleID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schoolDays`
--
ALTER TABLE `schoolDays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `studentAttendance`
--
ALTER TABLE `studentAttendance`
  MODIFY `attendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `studentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `subjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `teacherAttendance`
--
ALTER TABLE `teacherAttendance`
  MODIFY `attendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacherID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=278;

--
-- AUTO_INCREMENT for table `userRoles`
--
ALTER TABLE `userRoles`
  MODIFY `roleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absentReasons`
--
ALTER TABLE `absentReasons`
  ADD CONSTRAINT `absentReasons_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `students` (`studentID`),
  ADD CONSTRAINT `absentReasons_ibfk_2` FOREIGN KEY (`parentID`) REFERENCES `parents` (`parentID`),
  ADD CONSTRAINT `absentReasons_ibfk_3` FOREIGN KEY (`teacherID`) REFERENCES `teachers` (`teacherID`);

--
-- Constraints for table `classTimetable`
--
ALTER TABLE `classTimetable`
  ADD CONSTRAINT `classTimetable_ibfk_1` FOREIGN KEY (`classID`) REFERENCES `class` (`classID`) ON DELETE CASCADE,
  ADD CONSTRAINT `classTimetable_ibfk_2` FOREIGN KEY (`dayID`) REFERENCES `schoolDays` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `classTimetable_ibfk_3` FOREIGN KEY (`periodID`) REFERENCES `periods` (`periodID`) ON DELETE CASCADE,
  ADD CONSTRAINT `classTimetable_ibfk_4` FOREIGN KEY (`subjectID`) REFERENCES `subject` (`subjectID`) ON DELETE CASCADE,
  ADD CONSTRAINT `classTimetable_ibfk_5` FOREIGN KEY (`teacherID`) REFERENCES `teachers` (`teacherID`) ON DELETE CASCADE;

--
-- Constraints for table `examTimeTable`
--
ALTER TABLE `examTimeTable`
  ADD CONSTRAINT `fk_examTimeTable_user` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `managers`
--
ALTER TABLE `managers`
  ADD CONSTRAINT `fk_MP_user` FOREIGN KEY (`userId`) REFERENCES `user` (`userID`);

--
-- Constraints for table `marks`
--
ALTER TABLE `marks`
  ADD CONSTRAINT `marks_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `students` (`studentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `marks_ibfk_2` FOREIGN KEY (`teacherID`) REFERENCES `teachers` (`teacherID`),
  ADD CONSTRAINT `marks_ibfk_3` FOREIGN KEY (`subjectID`) REFERENCES `subject` (`subjectID`);

--
-- Constraints for table `material`
--
ALTER TABLE `material`
  ADD CONSTRAINT `fk_subject` FOREIGN KEY (`subjectID`) REFERENCES `subject` (`subjectID`),
  ADD CONSTRAINT `material_ibfk_1` FOREIGN KEY (`teacherID`) REFERENCES `teachers` (`teacherID`);

--
-- Constraints for table `parents`
--
ALTER TABLE `parents`
  ADD CONSTRAINT `parents_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `students` (`studentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `parents_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reliefAssignments`
--
ALTER TABLE `reliefAssignments`
  ADD CONSTRAINT `fk_relief_createdBy` FOREIGN KEY (`createdBy`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `fk_relief_teacher` FOREIGN KEY (`reliefTeacherID`) REFERENCES `teachers` (`teacherID`),
  ADD CONSTRAINT `fk_relief_timetable` FOREIGN KEY (`timetableID`) REFERENCES `classTimetable` (`id`);

--
-- Constraints for table `studentAttendance`
--
ALTER TABLE `studentAttendance`
  ADD CONSTRAINT `studentAttendance_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `students` (`studentID`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`classID`) REFERENCES `class` (`classID`) ON UPDATE CASCADE;

--
-- Constraints for table `teacherAttendance`
--
ALTER TABLE `teacherAttendance`
  ADD CONSTRAINT `teacherAttendance_ibfk_1` FOREIGN KEY (`teacherID`) REFERENCES `teachers` (`teacherID`);

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `fk_classID` FOREIGN KEY (`classID`) REFERENCES `class` (`classID`) ON DELETE SET NULL,
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teachers_ibfk_2` FOREIGN KEY (`subjectID`) REFERENCES `subject` (`subjectID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`role`) REFERENCES `userRoles` (`roleID`);

--
-- Constraints for table `userAddress`
--
ALTER TABLE `userAddress`
  ADD CONSTRAINT `userAddress_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `userName`
--
ALTER TABLE `userName`
  ADD CONSTRAINT `userName_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

