-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql-iskole.alwaysdata.net
-- Generation Time: Apr 18, 2026 at 01:58 PM
-- Server version: 10.11.15-MariaDB
-- PHP Version: 8.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

Create database if not exists`iskole_database` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `iskole_database`;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `iskole_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `absentReasons`
--

CREATE TABLE `absentReasons` (
  `reasonID` int(11) NOT NULL,
  `parentID` int(11) DEFAULT NULL,
  `teacherID` int(11) DEFAULT NULL,
  `reason` varchar(500) DEFAULT NULL,
  `fromDate` date DEFAULT NULL,
  `toDate` date DEFAULT NULL,
  `acknowledgedBy` int(11) DEFAULT NULL,
  `submittedAt` timestamp NULL DEFAULT current_timestamp(),
  `acknowledgedDate` date DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `classID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absentReasons`
--

INSERT INTO `absentReasons` (`reasonID`, `parentID`, `teacherID`, `reason`, `fromDate`, `toDate`, `acknowledgedBy`, `submittedAt`, `acknowledgedDate`, `title`, `classID`) VALUES
(19, 34, NULL, 'Hello Title', '2026-04-08', '2026-04-17', NULL, '2026-04-14 19:41:27', NULL, 'Absence Title', NULL),
(20, 34, NULL, 'Hello Title', '2026-04-08', '2026-04-17', NULL, '2026-04-14 19:41:45', NULL, 'Absence Title', NULL),
(21, 35, NULL, 'For aurudu naagam.', '2026-04-11', '2026-04-15', 299, '2026-04-15 18:01:54', '2026-04-15', NULL, NULL),
(22, 35, NULL, 'Dear Teacher, my child was absent today due to a fever and is now recovering.', '2026-04-01', '2026-04-03', NULL, '2026-04-15 18:26:55', NULL, NULL, NULL),
(26, 35, NULL, 'aurudu weda elleema', '2026-04-16', '2026-04-17', NULL, '2026-04-15 18:56:49', NULL, NULL, 31),
(27, 36, NULL, 'My child could not attend school due to a family emergency.', '2026-04-15', '2026-04-17', 299, '2026-04-15 19:00:54', '2026-04-15', NULL, 31),
(28, 36, NULL, 'Please excuse my child’s absence due to participation in a sports event.', '2026-04-09', '2026-04-09', 299, '2026-04-15 19:12:50', '2026-04-15', NULL, 31),
(29, 36, NULL, 'sick', '2026-04-18', '2026-04-19', NULL, '2026-04-18 10:30:01', NULL, NULL, 31),
(30, 36, NULL, 'aaa', '2026-04-25', '2026-04-19', NULL, '2026-04-18 11:18:13', NULL, NULL, 31);

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
-- Dumping data for table `announcement`
--

INSERT INTO `announcement` (`announcement_id`, `title`, `content`, `published_by`, `role`, `created_at`, `admin`, `mp`, `teacher`, `parent`, `student`, `deleted`) VALUES
(117, 'Implementation of New Attendance Policy', 'Dear All,\n\nThe school management has introduced an updated attendance policy to ensure better academic performance and discipline.\n\nStudents must maintain a minimum of 80% attendance each term.\nLate arrivals beyond 15 minutes will be marked as absent for the first period.\nParents will be notified in case of continuous absences.\n\nWe kindly request parents and students to adhere strictly to this policy.\n\nFor further details, please contact the school office.', 278, '1', '2026-04-03 06:47:47', 0, 1, 1, 1, 1, 0),
(118, 'Announcement for all', 'All students, teachers, parents, and staff are advised to regularly check school announcements, follow schedules, and stay engaged with academic activities. Teachers should update materials and records, while students and parents should monitor progress and stay connected.', 1, '0', '2026-04-03 06:53:48', 1, 1, 1, 1, 1, 0),
(119, 'Upcoming Class Test Notification', 'Dear Students and Parents,\r\n\r\nThis is to inform you that a class test in Mathematics will be conducted on Friday.\r\n\r\nTopics included:\r\n\r\nFractions\r\nDecimals\r\nBasic Algebra\r\n\r\nStudents are advised to revise thoroughly and complete all related exercises. Parents are encouraged to support their children in preparation.\r\n\r\nPlease ensure attendance on the test day.\r\n\r\nThank you.', 279, '2', '2026-04-03 06:57:21', 0, 0, 0, 1, 1, 0),
(120, 'System Maintenance Notice', 'The system will be unavailable on Sunday from 10 PM to 3 AM for maintenance.\nPlease save your work and avoid logging in during this period.', 1, '0', '2026-04-15 11:22:41', 1, 1, 1, 1, 1, 1),
(121, 'System Maintenance Notice', 'The system will be unavailable on Sunday from 10 PM to 2 AM for maintenance.\r\nPlease save your work and avoid logging in during this period.', 1, '0', '2026-04-15 11:23:46', 1, 1, 1, 1, 1, 0),
(122, 'Internal Staff Meeting', 'A management meeting is scheduled for Friday at 9:00 AM in the conference room.\nAll panel members are requested to attend on time.', 278, '1', '2026-04-15 21:02:48', 0, 1, 0, 0, 0, 1),
(123, 'Internal Staff Meeting', 'A management meeting is scheduled for Friday at 9:00 AM in the conference room.\r\nAll panel members are requested to attend on time.', 278, '1', '2026-04-15 21:03:52', 0, 1, 0, 0, 0, 0),
(124, 'Lesson Plan Submission Reminder', 'Please submit your lesson plans for next week before Thursday.\r\nEnsure all required topics are clearly outlined.', 278, '1', '2026-04-15 21:05:06', 0, 0, 1, 0, 0, 0),
(125, 'Mid-Term Exam Schedule Released', 'The mid-term examination timetable has been published.\nStudents should check the schedule and prepare accordingly.', 298, '2', '2026-04-15 21:05:46', 0, 0, 0, 1, 1, 1),
(126, 'Mid-Term Exam Schedule Released', 'The mid-term examination timetable has been published.\r\nStudents should check the schedule and prepare accordingly.', 278, '1', '2026-04-15 21:14:21', 0, 0, 0, 0, 1, 0),
(128, 'Parent-Teacher Meeting', 'A parent-teacher meeting will be held next Monday.\r\nParents are requested to attend and discuss student progress.', 278, '1', '2026-04-15 21:22:00', 0, 0, 0, 1, 0, 0),
(129, 'Curriculum Update', 'The updated syllabus has been uploaded to the system.\r\nPlease review the changes and adjust your plans accordingly.', 1, '0', '2026-04-15 21:23:06', 0, 1, 1, 0, 0, 1),
(130, 'Curriculum Update', 'The updated syllabus has been uploaded to the system.\r\nPlease review the changes and adjust your plans accordingly.', 278, '1', '2026-04-15 21:26:46', 0, 1, 1, 0, 0, 0),
(131, 'New School Policy Update', 'A new attendance policy has been introduced.\r\nStudents should review it carefully to avoid issues.', 278, '1', '2026-04-15 21:27:51', 0, 1, 0, 0, 1, 0),
(132, 'Fee Payment Reminder', 'The deadline for term fee payment is approaching.\r\nParents are requested to complete payments on time.', 278, '1', '2026-04-15 21:29:13', 0, 1, 0, 1, 0, 0),
(133, 'Science Exhibition Announcement', 'The annual science exhibition will be held next month.\r\nTeachers and students should start preparing their projects.', 278, '1', '2026-04-15 21:30:44', 0, 0, 1, 0, 1, 0),
(134, 'Student Performance Review', 'Teachers are requested to update student progress reports.\r\nParents will be able to review them by the end of the week.', 278, '1', '2026-04-15 21:32:04', 0, 0, 1, 1, 0, 0),
(135, 'School Holiday Notice', 'The school will remain closed on Friday due to a public holiday.\r\nClasses will resume as usual on Monday.', 278, '1', '2026-04-15 21:34:42', 0, 0, 0, 1, 1, 0),
(136, 'Emergency Drill Notification', 'An emergency evacuation drill will be conducted tomorrow morning.\r\nAll participants must follow instructions carefully.', 278, '1', '2026-04-15 21:36:22', 0, 1, 1, 0, 1, 0),
(137, 'Health & Safety Guidelines', 'Updated health and safety guidelines have been issued.\r\nPlease review and ensure compliance at all times.', 278, '1', '2026-04-15 21:37:33', 0, 1, 1, 1, 0, 0),
(138, 'Annual Sports Meet', 'The annual sports meet is scheduled for next Saturday.\r\nStudents and parents are invited to participate and attend.', 278, '1', '2026-04-15 21:38:37', 0, 1, 0, 1, 1, 0),
(139, 'Online Learning Guidelines', 'New guidelines for online classes have been released.\r\nAll users must follow them to ensure smooth learning.', 278, '1', '2026-04-15 21:40:00', 0, 0, 1, 1, 1, 0),
(140, 'Important System Update', 'The system has been upgraded with new features.\r\nAll users are encouraged to explore and use them effectively.', 278, '1', '2026-04-15 21:44:40', 0, 1, 1, 1, 1, 0),
(141, 'Homework Submission Reminder', 'Please submit your assignments before the given deadline.\r\nLate submissions may not be accepted.', 298, '2', '2026-04-15 21:45:59', 0, 0, 0, 0, 1, 0),
(142, 'Student Progress Update', 'Your child’s recent performance has been updated.\r\nKindly log in to review the details.', 298, '2', '2026-04-15 21:48:13', 0, 0, 0, 1, 0, 0),
(143, 'Class Test Notification', 'A class test will be conducted this Friday.\r\nStudents should revise all relevant lessons.', 298, '2', '2026-04-15 21:49:05', 0, 0, 0, 1, 1, 0),
(144, 'Test', 'test2', 278, '1', '2026-04-18 12:13:06', 1, 1, 1, 1, 1, 1),
(145, 'test', 'testttt', 278, '1', '2026-04-18 13:05:05', 1, 1, 1, 1, 1, 1),
(146, 'test', 'nasdnlaksd', 278, '1', '2026-04-18 13:39:51', 1, 1, 1, 1, 1, 1);

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
-- Dumping data for table `class`
--

INSERT INTO `class` (`classID`, `grade`, `class`) VALUES
(31, 6, 'A'),
(32, 7, 'A'),
(33, 8, 'A'),
(34, 6, 'B'),
(36, 7, 'B'),
(37, 9, 'A'),
(38, 9, 'B'),
(39, 8, 'B'),
(43, 9, 'C'),
(44, 9, 'D');

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
-- Dumping data for table `classTimetable`
--

INSERT INTO `classTimetable` (`id`, `teacherID`, `dayID`, `periodID`, `classID`, `subjectID`) VALUES
(198, 36, 6, 9, 31, 18),
(199, 36, 6, 10, 31, 18),
(201, 38, 6, 12, 31, 22),
(202, 43, 6, 13, 31, 24),
(203, 44, 6, 14, 31, 25),
(205, 37, 6, 16, 31, 21),
(206, 36, 7, 9, 31, 18),
(207, 43, 7, 10, 31, 24),
(208, 46, 7, 11, 31, 28),
(209, 40, 7, 12, 31, 19),
(210, 42, 7, 13, 31, 23),
(211, 37, 7, 14, 31, 21),
(212, 46, 7, 15, 31, 28),
(213, 38, 7, 16, 31, 22),
(214, 36, 8, 9, 31, 18),
(215, 42, 8, 10, 31, 23),
(216, 41, 8, 11, 31, 26),
(217, 40, 8, 12, 31, 19),
(218, 39, 8, 13, 31, 20),
(219, 37, 8, 14, 31, 21),
(220, 36, 8, 15, 31, 18),
(221, 40, 8, 16, 31, 19),
(222, 36, 9, 9, 31, 18),
(223, 36, 9, 10, 31, 18),
(224, 44, 9, 11, 31, 25),
(225, 39, 9, 12, 31, 20),
(226, 40, 9, 13, 31, 19),
(227, 41, 9, 14, 31, 26),
(228, 46, 9, 15, 31, 28),
(229, 39, 9, 16, 31, 20),
(230, 36, 10, 9, 31, 18),
(231, 39, 10, 10, 31, 20),
(232, 44, 10, 11, 31, 25),
(233, 42, 10, 12, 31, 23),
(234, 38, 10, 13, 31, 22),
(235, 35, 10, 14, 31, 17),
(237, 42, 10, 16, 31, 23);

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
-- Dumping data for table `examTimeTable`
--

INSERT INTO `examTimeTable` (`timeTableID`, `userID`, `visibility`, `grade`, `title`, `file`, `submittedAt`) VALUES
(8, 1, 1, '6', 'Exam Timetable', '/assets/exam_timetable_images/exam_tt_grade_6_1776512312.png', '2026-04-15 12:52:55'),
(9, 1, 1, '7', 'Exam Timetable', '/assets/exam_timetable_images/exam_tt_grade_7_1776276316.png', '2026-04-15 20:05:16');

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
-- Dumping data for table `leaveRequests`
--

INSERT INTO `leaveRequests` (`id`, `teacherUserID`, `dateFrom`, `dateTo`, `leaveType`, `reason`, `status`, `managerUserID`, `managerComment`, `decidedAt`, `createdAt`, `updatedAt`) VALUES
(27, 303, '2026-04-13', '2026-04-17', 'personal', 'Need leave for aurudu season', 'pending', NULL, NULL, NULL, '2026-04-15 20:23:08', '2026-04-15 20:23:08'),
(28, 299, '2026-04-10', '2026-04-14', 'medical', 'Im sick. Please give me leave', 'approved', 278, NULL, '2026-04-15 21:09:41', '2026-04-15 21:09:07', '2026-04-15 21:09:41'),
(29, 304, '2026-04-16', '2026-04-16', 'personal', 'aurudu', '', NULL, NULL, NULL, '2026-04-16 06:54:02', '2026-04-16 06:55:13'),
(30, 304, '2026-04-16', '2026-04-16', 'personal', 'new', 'approved', 278, NULL, '2026-04-17 04:47:59', '2026-04-16 06:57:28', '2026-04-17 04:47:59'),
(31, 300, '2026-04-17', '2026-04-17', 'personal', 'sick.......', 'pending', NULL, NULL, NULL, '2026-04-16 07:02:27', '2026-04-16 09:37:12'),
(32, 300, '2026-04-17', '2026-04-17', 'medical', 'xx', '', NULL, NULL, NULL, '2026-04-16 08:16:18', '2026-04-16 08:16:30'),
(33, 299, '2026-04-22', '2026-04-24', 'medical', 'sick', 'pending', NULL, NULL, NULL, '2026-04-18 12:21:58', '2026-04-18 12:21:58'),
(34, 299, '2026-04-24', '2026-04-25', 'medical', 'Test', 'pending', NULL, NULL, NULL, '2026-04-18 13:14:15', '2026-04-18 13:14:15');

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
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`mpId`, `userId`, `nic`) VALUES
(39, 278, 2003674287),
(40, 316, 123),
(41, 317, 1213),
(42, 318, 12345678912),
(43, 320, 200227100724);

-- --------------------------------------------------------

--
-- Table structure for table `markDrafts`
--

CREATE TABLE `markDrafts` (
  `draftID` int(11) NOT NULL,
  `teacherID` int(11) NOT NULL,
  `subjectID` int(11) NOT NULL,
  `classID` int(11) NOT NULL,
  `term` varchar(10) NOT NULL,
  `studentID` int(11) NOT NULL,
  `marks` decimal(5,2) NOT NULL,
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `markDrafts`
--

INSERT INTO `markDrafts` (`draftID`, `teacherID`, `subjectID`, `classID`, `term`, `studentID`, `marks`, `updatedAt`) VALUES
(6, 46, 28, 32, '1', 53, 78.00, '2026-04-15 18:56:36'),
(7, 46, 28, 32, '1', 54, 98.00, '2026-04-15 18:56:37');

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
-- Dumping data for table `marks`
--

INSERT INTO `marks` (`markID`, `studentID`, `teacherID`, `subjectID`, `term`, `marks`, `gradeLetter`, `enteredDate`, `updatedDate`) VALUES
(35, 50, 36, 18, 1, 87.00, 'A', '2026-04-15 13:28:51', '2026-04-15 20:42:26'),
(36, 48, 36, 18, 1, 50.00, 'S', '2026-04-15 13:28:52', '2026-04-15 13:28:52'),
(37, 63, 36, 18, 1, 10.00, 'W', '2026-04-15 13:28:53', '2026-04-15 13:28:53'),
(38, 50, 41, 26, 1, 50.00, 'S', '2026-04-15 13:39:58', '2026-04-15 13:39:58'),
(39, 48, 41, 26, 1, 82.00, 'A', '2026-04-15 13:40:00', '2026-04-15 13:40:00'),
(40, 63, 41, 26, 1, 99.00, 'A', '2026-04-15 13:40:01', '2026-04-15 13:40:01'),
(41, 47, 41, 26, 1, 98.00, 'A', '2026-04-15 13:40:59', '2026-04-15 13:42:54'),
(42, 51, 41, 26, 1, 78.00, 'A', '2026-04-15 13:41:01', '2026-04-15 13:42:55'),
(43, 52, 41, 26, 1, 96.00, 'A', '2026-04-15 13:41:02', '2026-04-15 13:42:56'),
(44, 53, 41, 26, 1, 65.00, 'B', '2026-04-15 13:44:13', '2026-04-15 13:44:39'),
(45, 54, 41, 26, 1, 45.00, 'S', '2026-04-15 13:44:14', '2026-04-15 13:44:39'),
(46, 58, 41, 26, 1, 45.00, 'S', '2026-04-15 13:45:19', '2026-04-15 13:45:48'),
(47, 57, 41, 26, 1, 54.00, 'S', '2026-04-15 13:45:20', '2026-04-15 13:45:49'),
(48, 47, 36, 18, 1, 78.00, 'A', '2026-04-15 13:51:30', '2026-04-15 13:52:32'),
(49, 51, 36, 18, 1, 78.00, 'A', '2026-04-15 13:51:31', '2026-04-15 13:52:33'),
(50, 52, 36, 18, 1, 79.00, 'A', '2026-04-15 13:51:32', '2026-04-15 13:52:33'),
(51, 53, 40, 19, 1, 80.00, 'A', '2026-04-15 18:41:26', '2026-04-15 18:41:26'),
(52, 54, 40, 19, 1, 90.00, 'A', '2026-04-15 18:41:26', '2026-04-15 18:41:26'),
(53, 50, 40, 19, 1, 70.00, 'B', '2026-04-15 18:42:40', '2026-04-15 18:42:40'),
(54, 48, 40, 19, 1, 50.00, 'S', '2026-04-15 18:42:40', '2026-04-15 18:42:40'),
(55, 63, 40, 19, 1, 74.00, 'B', '2026-04-15 18:42:41', '2026-04-15 18:42:41'),
(56, 47, 40, 19, 1, 69.00, 'B', '2026-04-15 18:43:18', '2026-04-15 18:43:18'),
(57, 51, 40, 19, 1, 97.00, 'A', '2026-04-15 18:43:18', '2026-04-15 18:43:18'),
(58, 52, 40, 19, 1, 88.00, 'A', '2026-04-15 18:43:19', '2026-04-15 18:43:19'),
(59, 56, 40, 19, 1, 87.00, 'A', '2026-04-15 18:43:59', '2026-04-15 18:43:59'),
(60, 55, 40, 19, 1, 91.00, 'A', '2026-04-15 18:43:59', '2026-04-15 18:43:59'),
(61, 53, 36, 18, 1, 89.00, 'A', '2026-04-15 18:44:42', '2026-04-15 18:44:42'),
(62, 54, 36, 18, 1, 100.00, 'A', '2026-04-15 18:44:43', '2026-04-15 18:44:43'),
(63, 56, 36, 18, 1, 50.00, 'S', '2026-04-15 18:45:47', '2026-04-15 18:45:47'),
(64, 55, 36, 18, 1, 76.00, 'A', '2026-04-15 18:45:48', '2026-04-15 18:45:48'),
(65, 50, 39, 20, 1, 58.00, 'C', '2026-04-15 18:50:17', '2026-04-15 18:50:17'),
(66, 48, 39, 20, 1, 79.00, 'A', '2026-04-15 18:50:17', '2026-04-15 18:50:17'),
(67, 63, 39, 20, 1, 43.00, 'S', '2026-04-15 18:50:17', '2026-04-15 18:50:17'),
(68, 47, 39, 20, 1, 87.00, 'A', '2026-04-15 18:50:37', '2026-04-15 18:50:37'),
(69, 51, 39, 20, 1, 36.00, 'S', '2026-04-15 18:50:37', '2026-04-15 18:50:37'),
(70, 52, 39, 20, 1, 98.00, 'A', '2026-04-15 18:50:38', '2026-04-15 18:50:38'),
(71, 50, 35, 17, 1, 87.00, 'A', '2026-04-15 18:50:42', '2026-04-15 18:50:42'),
(72, 48, 35, 17, 1, 78.00, 'A', '2026-04-15 18:50:42', '2026-04-15 18:50:42'),
(73, 63, 35, 17, 1, 65.00, 'B', '2026-04-15 18:50:43', '2026-04-15 18:50:43'),
(74, 53, 39, 20, 1, 78.00, 'A', '2026-04-15 18:51:05', '2026-04-15 18:51:05'),
(75, 54, 39, 20, 1, 50.00, 'S', '2026-04-15 18:51:06', '2026-04-15 18:51:06'),
(76, 51, 35, 17, 1, 98.00, 'A', '2026-04-15 18:51:14', '2026-04-15 18:51:21'),
(77, 52, 35, 17, 1, 78.00, 'A', '2026-04-15 18:51:14', '2026-04-15 18:51:21'),
(78, 47, 35, 17, 1, 54.00, 'S', '2026-04-15 18:51:33', '2026-04-15 18:51:33'),
(79, 56, 39, 20, 1, 78.00, 'A', '2026-04-15 18:51:33', '2026-04-15 18:51:33'),
(80, 55, 39, 20, 1, 55.00, 'C', '2026-04-15 18:51:34', '2026-04-15 18:51:34'),
(81, 53, 35, 17, 1, 12.00, 'W', '2026-04-15 18:51:57', '2026-04-15 18:51:57'),
(82, 54, 35, 17, 1, 65.00, 'B', '2026-04-15 18:51:57', '2026-04-15 18:51:57'),
(83, 56, 35, 17, 1, 98.00, 'A', '2026-04-15 18:53:41', '2026-04-15 18:53:41'),
(84, 55, 35, 17, 1, 56.00, 'C', '2026-04-15 18:53:41', '2026-04-15 18:53:41'),
(85, 50, 37, 21, 1, 88.00, 'A', '2026-04-15 18:54:38', '2026-04-15 18:54:38'),
(86, 48, 37, 21, 1, 70.00, 'B', '2026-04-15 18:54:38', '2026-04-15 18:54:38'),
(87, 63, 37, 21, 1, 35.00, 'S', '2026-04-15 18:54:39', '2026-04-15 18:54:39'),
(88, 50, 46, 28, 1, 45.00, 'S', '2026-04-15 18:55:10', '2026-04-15 18:55:10'),
(89, 48, 46, 28, 1, 98.00, 'A', '2026-04-15 18:55:11', '2026-04-15 18:55:11'),
(90, 63, 46, 28, 1, 77.00, 'A', '2026-04-15 18:55:12', '2026-04-15 18:55:12'),
(91, 47, 46, 28, 1, 65.00, 'B', '2026-04-15 18:55:35', '2026-04-15 18:55:35'),
(92, 51, 46, 28, 1, 45.00, 'S', '2026-04-15 18:55:36', '2026-04-15 18:55:36'),
(93, 52, 46, 28, 1, 89.00, 'A', '2026-04-15 18:55:37', '2026-04-15 18:55:37'),
(94, 53, 46, 28, 1, 78.00, 'A', '2026-04-15 18:55:59', '2026-04-15 18:56:30'),
(95, 54, 46, 28, 1, 98.00, 'A', '2026-04-15 18:56:00', '2026-04-15 18:56:31'),
(96, 53, 46, 28, 2, 65.00, 'B', '2026-04-15 18:59:15', '2026-04-15 18:59:15'),
(97, 54, 46, 28, 2, 45.00, 'S', '2026-04-15 18:59:15', '2026-04-15 18:59:15'),
(98, 50, 44, 25, 1, 65.00, 'B', '2026-04-15 19:04:14', '2026-04-15 19:05:33'),
(99, 48, 44, 25, 1, 45.00, 'S', '2026-04-15 19:04:15', '2026-04-15 19:05:34'),
(100, 63, 44, 25, 1, 78.00, 'A', '2026-04-15 19:04:15', '2026-04-15 19:05:34'),
(101, 47, 44, 25, 1, 65.00, 'B', '2026-04-15 19:05:58', '2026-04-15 19:05:58'),
(102, 51, 44, 25, 1, 45.00, 'S', '2026-04-15 19:05:58', '2026-04-15 19:05:58'),
(103, 52, 44, 25, 1, 65.00, 'B', '2026-04-15 19:05:59', '2026-04-15 19:05:59'),
(104, 53, 44, 25, 1, 65.00, 'B', '2026-04-15 19:06:28', '2026-04-15 19:06:41'),
(105, 54, 44, 25, 1, 45.00, 'S', '2026-04-15 19:06:28', '2026-04-15 19:06:42'),
(106, 60, 35, 17, 1, 65.00, 'B', '2026-04-15 19:16:19', '2026-04-15 19:16:19'),
(107, 59, 35, 17, 1, 89.00, 'A', '2026-04-15 19:16:20', '2026-04-15 19:16:20'),
(108, 47, 37, 21, 1, 89.00, 'A', '2026-04-15 19:18:07', '2026-04-15 19:18:07'),
(109, 51, 37, 21, 1, 79.00, 'A', '2026-04-15 19:18:08', '2026-04-15 19:18:08'),
(110, 52, 37, 21, 1, 98.00, 'A', '2026-04-15 19:18:08', '2026-04-15 19:18:08'),
(111, 53, 37, 21, 1, 69.00, 'B', '2026-04-15 19:18:33', '2026-04-15 19:18:33'),
(112, 54, 37, 21, 1, 39.00, 'S', '2026-04-15 19:18:33', '2026-04-15 19:18:33'),
(113, 56, 37, 21, 1, 87.00, 'A', '2026-04-15 19:18:50', '2026-04-15 19:18:50'),
(114, 55, 37, 21, 1, 78.00, 'A', '2026-04-15 19:18:50', '2026-04-15 19:18:50'),
(115, 60, 35, 17, 3, 95.00, 'A', '2026-04-15 19:20:34', '2026-04-15 19:21:52'),
(116, 59, 35, 17, 3, 76.00, 'A', '2026-04-15 19:20:35', '2026-04-15 19:21:52'),
(117, 50, 38, 22, 1, 50.00, 'S', '2026-04-15 19:23:04', '2026-04-15 19:23:04'),
(118, 48, 38, 22, 1, 76.00, 'A', '2026-04-15 19:23:04', '2026-04-15 19:23:04'),
(119, 63, 38, 22, 1, 87.00, 'A', '2026-04-15 19:23:04', '2026-04-15 19:23:04'),
(120, 47, 38, 22, 1, 87.00, 'A', '2026-04-15 19:23:46', '2026-04-15 19:23:46'),
(121, 51, 38, 22, 1, 67.00, 'B', '2026-04-15 19:23:47', '2026-04-15 19:23:47'),
(122, 52, 38, 22, 1, 98.00, 'A', '2026-04-15 19:23:47', '2026-04-15 19:23:47'),
(123, 53, 38, 22, 1, 87.00, 'A', '2026-04-15 19:24:14', '2026-04-15 19:24:14'),
(124, 54, 38, 22, 1, 59.00, 'C', '2026-04-15 19:24:14', '2026-04-15 19:24:14'),
(125, 56, 38, 22, 1, 78.00, 'A', '2026-04-15 19:24:33', '2026-04-15 19:24:33'),
(126, 55, 38, 22, 1, 98.00, 'A', '2026-04-15 19:24:33', '2026-04-15 19:24:33'),
(127, 50, 43, 24, 1, 45.00, 'S', '2026-04-15 19:25:01', '2026-04-15 19:25:20'),
(128, 48, 43, 24, 1, 65.00, 'B', '2026-04-15 19:25:01', '2026-04-15 19:25:21'),
(129, 63, 43, 24, 1, 89.00, 'A', '2026-04-15 19:25:02', '2026-04-15 19:25:21'),
(130, 53, 43, 24, 1, 54.00, 'S', '2026-04-15 19:25:46', '2026-04-15 19:26:03'),
(131, 54, 43, 24, 1, 89.00, 'A', '2026-04-15 19:25:47', '2026-04-15 19:26:03'),
(132, 50, 42, 23, 1, 65.00, 'B', '2026-04-15 19:28:19', '2026-04-15 19:28:19'),
(133, 48, 42, 23, 1, 89.00, 'A', '2026-04-15 19:28:19', '2026-04-15 19:28:19'),
(134, 63, 42, 23, 1, 78.00, 'A', '2026-04-15 19:28:20', '2026-04-15 19:28:20'),
(135, 47, 42, 23, 1, 65.00, 'B', '2026-04-15 19:28:44', '2026-04-15 19:29:02'),
(136, 51, 42, 23, 1, 89.00, 'A', '2026-04-15 19:28:44', '2026-04-15 19:29:03'),
(137, 52, 42, 23, 1, 78.00, 'A', '2026-04-15 19:28:45', '2026-04-15 19:29:03'),
(138, 50, 44, 25, 2, 54.00, 'S', '2026-04-15 19:30:48', '2026-04-15 19:30:48'),
(139, 48, 44, 25, 2, 45.00, 'S', '2026-04-15 19:30:49', '2026-04-15 19:30:49'),
(140, 63, 44, 25, 2, 65.00, 'B', '2026-04-15 19:30:49', '2026-04-15 19:30:49'),
(141, 62, 36, 18, 3, 98.00, 'A', '2026-04-15 19:31:38', '2026-04-15 19:32:49'),
(143, 57, 35, 17, 3, 97.00, 'A', '2026-04-15 20:30:08', '2026-04-15 20:36:59'),
(144, 58, 35, 17, 3, 85.00, 'A', '2026-04-15 20:48:34', '2026-04-15 20:48:34'),
(145, 50, 36, 18, 3, 45.00, 'S', '2026-04-18 12:02:32', '2026-04-18 12:27:00'),
(146, 48, 36, 18, 3, 56.00, 'C', '2026-04-18 12:27:00', '2026-04-18 12:27:00'),
(147, 63, 36, 18, 3, 56.00, 'C', '2026-04-18 12:27:01', '2026-04-18 12:27:01'),
(148, 50, 36, 18, 2, 34.00, 'W', '2026-04-18 13:16:07', '2026-04-18 13:16:07'),
(149, 48, 36, 18, 2, 34.00, 'W', '2026-04-18 13:16:07', '2026-04-18 13:16:07'),
(150, 63, 36, 18, 2, 56.00, 'C', '2026-04-18 13:16:07', '2026-04-18 13:16:07');

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
-- Dumping data for table `material`
--

INSERT INTO `material` (`materialID`, `teacherID`, `grade`, `class`, `subjectID`, `title`, `description`, `file`, `date`, `visibility`, `deleted`) VALUES
(73, 36, '6', 'A', 18, 'Introduction to Biology – Lesson Notes', 'This material explains key scientific concepts related to the lesson topic.\r\nIt includes examples, diagrams, and activities to improve understanding.\r\nStudents are encouraged to review and complete the given tasks.', '/uploads/materials/Screenshot 2026-03-15 214011_1776233718_69df2cf6079f3.png', '2026-04-15 06:15:34', 1, 0),
(74, 41, '6', 'B', 26, 'Basics of Computers – Lesson Notes', 'This resource covers important ICT concepts and practical skills.\r\nFollow the instructions carefully and try out the activities on a computer.\r\nHelpful for both theory and hands-on learning.', '/uploads/materials/Screenshot 2026-03-15 214319_1776234683_69df30bb1fc0d.png', '2026-04-15 06:31:39', 1, 0),
(75, 40, '7', 'A', 19, 'Grammar (ව්‍යාකරණ) – Lesson Notes', 'මෙම පාඩම් ද්‍රව්‍යය අදාළ විෂය කරුණු සරලව පැහැදිලි කරයි.\r\nඅභ්‍යාස හා උදාහරණ මගින් ඉගෙනීම ශක්තිමත් කරයි.\r\nසිසුන් විසින් නිවැරදිව අධ්‍යයනය කළ යුතුය.', '/uploads/materials/Screenshot 2026-03-15 214447_1776235205_69df32c5da264.png', '2026-04-15 06:40:22', 1, 0),
(76, 39, '7', 'B', 20, 'Grammar Exercises – Worksheet', 'This material focuses on improving reading, writing, and grammar skills.\r\nExamples and exercises are included for practice.\r\nStudents should complete all activities for better understanding.', '/uploads/materials/Screenshot 2026-03-15 214630_1776236178_69df369251642.png', '2026-04-15 06:56:34', 1, 0),
(77, 38, '8', 'B', 22, 'Earth Structure – Lesson Notes', 'This material covers key geographical concepts and real-world examples.\r\nMaps, diagrams, and activities are included for better learning.\r\nStudents should carefully study and practice the exercises.', '/uploads/materials/Screenshot 2026-03-15 224438_1776236770_69df38e2d935d.png', '2026-04-15 07:06:27', 1, 0),
(78, 42, '9', 'B', 23, 'Rights and Responsibilities – Lesson Notes', 'This lesson focuses on social values, responsibilities, and good citizenship.\r\nStudents will learn how to contribute positively to society.\r\nComplete the activities to enhance understanding.', '/uploads/materials/Screenshot 2026-03-15 214850_1776237043_69df39f399407.png', '2026-04-15 07:11:00', 1, 0),
(79, 43, '9', 'A', 24, 'Color Theory – Practical Guide', 'This material helps develop creativity and artistic skills.\r\nIncludes practical activities and examples for practice.\r\nStudents are encouraged to actively participate.', '/uploads/materials/Screenshot 2026-03-15 214738_1776237241_69df3ab9e2e06.png', '2026-04-15 07:14:18', 1, 0),
(80, 36, '8', 'A', 18, 'Widyawa', 'isubvrsbv', '/uploads/materials/Screenshot 2026-03-15 214630_1776237629_69df3c3d36662.png', '2026-04-15 07:20:45', 1, 1),
(81, 35, '8', 'A', 17, 'Mathematics – Algebra Basics – Lesson Notes', 'This material explains mathematical concepts with examples and exercises.\r\nPractice the given problems to improve your skills.\r\nReview the lesson regularly for better understanding.', '/uploads/materials/Screenshot 2026-03-15 225013_1776238428_69df3f5c72c42.png', '2026-04-15 07:34:04', 1, 0),
(82, 36, '6', 'A', 18, 'test', 'test', '/uploads/materials/Timetable_1776507641_69e35af98fb64.png', '2026-04-18 10:20:41', 1, 0),
(83, 36, '6', 'A', 18, 'Test ', 'etst', '/uploads/materials/Timetable_1776510749_69e3671dd1739.png', '2026-04-18 11:12:29', 1, 1);

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
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`userID`, `parentID`, `relationshipType`, `studentID`, `nic`) VALUES
(282, 34, 'father', 47, '200112345682'),
(311, 35, 'father', 48, '0712205445123'),
(312, 36, 'mother', 50, '0712205445123'),
(313, 37, 'father', 51, '123412341234'),
(314, 38, 'gardian', 52, '0712205445123'),
(315, 39, 'father', 53, '0712205445');

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
-- Dumping data for table `periods`
--

INSERT INTO `periods` (`periodID`, `startTime`, `endTime`) VALUES
(9, '07:50:00', '08:30:00'),
(10, '08:30:00', '09:10:00'),
(11, '09:10:00', '09:50:00'),
(12, '09:50:00', '10:30:00'),
(13, '10:50:00', '11:30:00'),
(14, '11:30:00', '12:10:00'),
(15, '12:10:00', '12:50:00'),
(16, '12:50:00', '13:30:00');

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

--
-- Dumping data for table `reliefAssignments`
--

INSERT INTO `reliefAssignments` (`reliefID`, `timetableID`, `reliefTeacherID`, `reliefDate`, `dayID`, `periodID`, `status`, `createdBy`, `createdAt`, `updatedAt`) VALUES
(1, 214, 35, '2026-04-15', 8, 9, 'assigned', 1, '2026-04-15 21:41:28', '2026-04-15 21:41:28'),
(2, 217, 39, '2026-04-15', 8, 12, 'assigned', 278, '2026-04-15 22:14:34', '2026-04-15 22:14:34'),
(3, 198, 43, '2026-04-18', 6, 9, 'assigned', 278, '2026-04-18 12:18:19', '2026-04-18 12:18:19'),
(4, 201, 36, '2026-04-18', 6, 12, 'assigned', 278, '2026-04-18 13:09:12', '2026-04-18 13:09:12');

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
-- Dumping data for table `report`
--

INSERT INTO `report` (`id`, `studentID`, `teacherID`, `report_type`, `category`, `title`, `description`, `report_date`) VALUES
(52, 50, 299, 'positive', 'Exams', 'Great performance on Exams', 'He performed well in exams. Really proud about that.', '2026-04-15 11:32:03'),
(54, 50, 299, 'positive', 'a', 'a', 'a', '2026-04-18 10:05:13'),
(55, 50, 299, 'neutral', 'acadamic', 'abc', 'abs', '2026-04-18 10:28:32'),
(56, 50, 299, 'neutral', 'a', 'cc', 'c', '2026-04-18 11:16:52');

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
-- Dumping data for table `schoolDays`
--

INSERT INTO `schoolDays` (`id`, `day`) VALUES
(10, 'Friday'),
(6, 'Monday'),
(9, 'Thursday'),
(7, 'Tuesday'),
(8, 'Wednesday');

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
-- Dumping data for table `studentAttendance`
--

INSERT INTO `studentAttendance` (`attendanceID`, `studentID`, `attendance_date`, `status`) VALUES
(62, 48, '2026-04-15', 'Present'),
(63, 50, '2026-04-15', 'Present'),
(64, 47, '2026-04-15', 'Present'),
(65, 51, '2026-04-15', 'Absent'),
(66, 52, '2026-04-15', 'Present'),
(67, 53, '2026-04-15', 'Present'),
(68, 54, '2026-04-15', 'Present'),
(69, 55, '2026-04-15', 'Absent'),
(70, 56, '2026-04-15', 'Present'),
(71, 49, '2026-04-15', 'Present'),
(72, 61, '2026-04-15', 'Absent'),
(73, 62, '2026-04-15', 'Present'),
(74, 59, '2026-04-15', 'Absent'),
(75, 60, '2026-04-15', 'Present'),
(76, 57, '2026-04-15', 'Present'),
(77, 58, '2026-04-15', 'Present'),
(78, 47, '2026-04-18', 'Present'),
(79, 51, '2026-04-18', 'Present'),
(80, 52, '2026-04-18', 'Absent'),
(81, 48, '2026-04-18', 'Absent'),
(82, 50, '2026-04-18', 'Present');

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
-- Dumping data for table `students`
--

INSERT INTO `students` (`userID`, `studentID`, `classID`, `gradeID`) VALUES
(280, 47, 34, 6),
(283, 48, 31, 6),
(284, 49, 33, 8),
(285, 50, 31, 6),
(286, 51, 34, 6),
(287, 52, 34, 6),
(288, 53, 32, 7),
(289, 54, 32, 7),
(290, 55, 36, 7),
(291, 56, 36, 7),
(292, 57, 38, 9),
(293, 58, 38, 9),
(294, 59, 37, 9),
(295, 60, 37, 9),
(296, 61, 33, 8),
(297, 62, 39, 8),
(310, 63, 31, 6);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subjectID` int(11) NOT NULL,
  `subjectName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subjectID`, `subjectName`) VALUES
(17, 'Mathematics'),
(18, 'Science'),
(19, 'First Language'),
(20, 'English'),
(21, 'History'),
(22, 'Geography'),
(23, 'Citizenship Education'),
(24, 'Aesthetic Subject'),
(25, 'Health & Physical Education'),
(26, 'ICT'),
(28, 'Religion'),
(29, 'test');

-- --------------------------------------------------------

--
-- Table structure for table `targetAudience`
--

CREATE TABLE `targetAudience` (
  `audienceID` int(11) NOT NULL,
  `audienceName` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Dumping data for table `teacherAttendance`
--

INSERT INTO `teacherAttendance` (`attendanceID`, `teacherID`, `attendance_date`, `status`) VALUES
(75, 34, '2026-04-15', 'present'),
(76, 35, '2026-04-15', 'present'),
(77, 36, '2026-04-15', 'absent'),
(78, 37, '2026-04-15', 'present'),
(79, 38, '2026-04-15', 'absent'),
(80, 39, '2026-04-15', 'present'),
(81, 40, '2026-04-15', 'absent'),
(82, 41, '2026-04-15', 'present'),
(83, 42, '2026-04-15', 'present'),
(84, 43, '2026-04-15', 'present'),
(85, 44, '2026-04-15', 'present'),
(86, 45, '2026-04-15', 'absent'),
(87, 46, '2026-04-15', 'present'),
(88, 34, '2026-04-18', 'present'),
(89, 35, '2026-04-18', 'present'),
(90, 36, '2026-04-18', 'absent'),
(91, 37, '2026-04-18', 'present'),
(92, 38, '2026-04-18', 'absent'),
(93, 39, '2026-04-18', 'present'),
(94, 40, '2026-04-18', 'absent'),
(95, 41, '2026-04-18', 'present'),
(96, 42, '2026-04-18', 'absent'),
(97, 43, '2026-04-18', 'present'),
(98, 44, '2026-04-18', 'absent'),
(99, 45, '2026-04-18', 'absent'),
(100, 46, '2026-04-18', 'present');

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
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`userID`, `teacherID`, `nic`, `subjectID`, `classID`, `grade`) VALUES
(279, 34, '2003125854', 17, NULL, 6),
(298, 35, '200074938529', 17, 43, NULL),
(299, 36, '200085496508', 18, 31, NULL),
(300, 37, '200068367956', 21, 33, NULL),
(301, 38, '200054966184', 22, 39, NULL),
(302, 39, '20037394287', 20, 36, NULL),
(303, 40, '197187543267', 19, 32, NULL),
(304, 41, '200484723973', 26, 34, NULL),
(305, 42, '200387235965', 23, 37, NULL),
(306, 43, '200085733298', 24, 38, NULL),
(307, 44, '200078397532', 25, 44, NULL),
(308, 45, '200086000444', NULL, NULL, NULL),
(309, 46, '200000546798', 28, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `createDate` datetime NOT NULL DEFAULT current_timestamp(),
  `role` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `dateOfBirth` date DEFAULT NULL,
  `password` varchar(400) DEFAULT NULL,
  `pwdChanged` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `gender`, `email`, `phone`, `createDate`, `role`, `active`, `dateOfBirth`, `password`, `pwdChanged`) VALUES
(1, 'Male', 'admin@gmail.com', '071123231', '2026-04-03 00:00:00', 0, 1, '2001-05-14', '$2y$10$0P35BO/EclquW8mApVR7MeYAIgZLEGE5CT/LNo.6d9f6FYBG1xEjC', 1),
(278, 'Male', 'kalanajinendra@gmail.com', '0712345678', '2026-04-03 04:41:34', 1, 1, '2003-01-01', '$2y$10$8le7QyL3agcqRBESs7OPHuNsxkMydJ.sUUG7ya0MdqFxZQD3iKMjK', 2026),
(279, 'Male', 'senhirusenaweera@gmail.com', '0702222676', '2026-04-03 04:52:47', 2, 1, '2003-05-24', '$2y$10$Ki88QgeIavEm8fvBUs7XL.g.uWyYhuShbNiyidw2qY6504Xrku57y', 2026),
(280, 'Male', 'adithaanusara31@gmail.com', '0781234567', '2026-04-12 16:07:28', 3, 1, '2003-06-21', '$2y$10$HD5lXHee5r/q5pVPRbNTquVQMypGtpVXSKZVAHbFdFo7aCcGN7GSy', 2026),
(282, 'Male', 'parent@gmail.com', '0711234561', '2026-04-14 19:11:27', 4, 1, '2023-04-05', '$2y$10$ntz29etsnlgo1vDizQGBfuuPN9anN9tKPs4R8dJwGtNU1fHH/q8zq', 0),
(283, 'Male', 'NimalPerera.6A@gmail.com', '0711234567', '2026-04-15 04:27:22', 3, 1, '2009-01-14', '$2y$10$ucjCCvC.xU67wyngDTHqGux1rHeSfJZgVkkLfWiRWeHr0HnwB6P9O', 0),
(284, 'Male', 'RoshanWeerasinghe.8A@gmail.com', '0711234561', '2026-04-15 04:31:32', 3, 1, '2023-04-04', '$2y$10$cpB4oRpsQ0kNUhsFIkpCr.BIeTLTJZ9KWJH.3hOMES4lX54iXyyF2', 0),
(285, 'Female', 'KasuniFernando.6A@gmail.com', '0712345678', '2026-04-15 04:35:23', 3, 1, '2019-06-13', '$2y$10$uIhiynsAQChte.LaofEbSusfisUjgwmQVWulpC.j/cqRu5YWkIwAK', 0),
(286, 'Male', 'SahanSilva.6B@gmail.com', '0712345678', '2026-04-15 04:36:51', 3, 1, '2012-08-24', '$2y$10$K1DRCuCpVU3sVlfoFkB87Okcl2dHf/Gf3u9ZiMEyC9zs04YQPMPKS', 0),
(287, 'Male', 'TharinduJayasinghe.6B@gmail.com', '0712345678', '2026-04-15 04:38:23', 3, 1, '2012-11-23', '$2y$10$cubQ0KICdrGkBzmZiDU9u.nHGzSXSn25MsCRJZdEbQRf.mP/4ZOJy', 0),
(288, 'Male', 'ChamaraGunawardena.7A@gmail.com', '0712345678', '2026-04-15 04:39:53', 3, 1, '2012-05-04', '$2y$10$aL6/tv5c8eIujADmCpyg6.2bb/yfk3qSt9rKH3li2L.SntuxhHvE.', 0),
(289, 'Male', 'DilshanWijesinghe.7A@gmail.com', '0712345678', '2026-04-15 04:43:42', 3, 1, '2012-04-08', '$2y$10$3lJRnj//Yz.P/C3XPfjALuaa8k6DEkhark6nZZ4WEgUKh8mwccJ3W', 0),
(290, 'Male', 'RoshanAbeysekara.7B@gmail.com', '0712345678', '2026-04-15 04:45:43', 3, 1, '2012-12-11', '$2y$10$PntUOudqV1wpqi3N9bf31.q0wUASP6FTpytm7sKnQ.qPk50BjEI3u', 0),
(291, 'Male', 'IshanMadushan.7B@gmail.com', '0712345678', '2026-04-15 04:47:48', 3, 1, '2012-03-03', '$2y$10$fXV8XnRgV3mfequFmZkLr.LF0/RrnGBO7HnY.ThWhCgeXNxShKDFa', 0),
(292, 'Female', 'SachiniRanasinghe.9B@gmail.com', '0711234561', '2026-04-15 04:48:05', 3, 1, '2023-04-06', '$2y$10$0vxh7yTjgaGmpplhosOr3uiQIBn1l0LAbAFH.u2R231LZRDIx6052', 0),
(293, 'Male', 'LahiruBandara.9B@gmail.com', '0712311231', '2026-04-15 04:51:53', 3, 1, '2023-04-04', '$2y$10$ZQBgRNr/08bl.CqBRvTvtuPLnfoBvdSVQ3f05cfbjlJuyh/Q1kysC', 0),
(294, 'Female', 'UdariEkanayake.9A@gmail.com', '0712321231', '2026-04-15 04:54:41', 3, 1, '2022-02-16', '$2y$10$F9R9Pm6Fbt2FvTF03sfsXefaomo67RdU2OK3.baNnHUL.zAy86GES', 0),
(295, 'Female', 'RameshKarunaratne.9A@gmail.com', '0711234561', '2026-04-15 04:57:52', 3, 1, '2023-04-05', '$2y$10$U3IS0Ca53qIpdJjictUTUebcNwjj.PgT8Eech6rvGKIO29otHaDom', 0),
(296, 'Female', 'HansiniDeSilva.8A@gmail.com', '0712311231', '2026-04-15 05:02:41', 3, 1, '2023-03-07', '$2y$10$HpQjkzeB7JzM4Y7sCbn0e.gciFSBlonZ0kWU/QMs.54IjZYQTap7O', 0),
(297, 'Male', 'GayanWickramasinghe.8B@gmail.com', '0711234561', '2026-04-15 05:06:33', 3, 1, '2023-04-06', '$2y$10$0d9IbJ5ppx3VqD.2.DFK0uSTNRag/xOSexOCW.57JD.49ICZStD/a', 0),
(298, 'Male', 'AmilaPerera.Mathematics@gmail.com', '0712345678', '2026-04-15 05:16:05', 2, 1, '2000-07-12', '$2y$10$N3Sbv53S6oGMdY5dykF8D.HBopvCqDH9.N.NVatFybv/kGeFAAwhq', 0),
(299, 'Male', 'KavinduFernando.Science@gmail.com', '0712345678', '2026-04-15 05:18:02', 2, 1, '2000-04-22', '$2y$10$MjBXB2O82iE38B2PBVaxxeAFhFSMHi37ONrMe2Pjniq3Bk7Ihat9O', 0),
(300, 'Male', 'SandaruSilva.History@gmail.com', '0712345678', '2026-04-15 05:21:00', 2, 1, '2000-05-14', '$2y$10$foiQRXecQJyIhf3to0iQOeB4sBpWV4RUaDkBILWfJDUdKWVTE6l3i', 0),
(301, 'Female', 'TharukaJayasuriya.Geography@gmail.com', '0712345678', '2026-04-15 05:27:29', 2, 1, '1999-06-05', '$2y$10$kO0z49s9uDjKsCLQvLMM5uxtbxA6BO4U2o5Jry8Vcwr7rbzhRyv.q', 0),
(302, 'Male', 'NimeshGunawardena.English@gmail.com', '0712345678', '2026-04-15 05:29:41', 2, 1, '2000-01-04', '$2y$10$5WAtW1HudR9r10.9CXpYluOstwe5GqXU/bBdeRN1bvrrJ8w6dZJFO', 0),
(303, 'Male', 'PasanWijeratne.FirstLanguage@gmail.com', '0712345678', '2026-04-15 05:32:37', 2, 1, '1999-11-14', '$2y$10$0ykVRKq34KkYc07KTRBOMeiq12cWq2LXNaFova.hj6VEk4m/sI0Je', 0),
(304, 'Male', 'IsankaAbeysinghe.ICT@gmail.com', '0712345678', '2026-04-15 05:38:06', 2, 1, '1999-08-10', '$2y$10$47tac0cUDw7GHsff8bIPZuVqLdUgiad.YCksAuV21znmV1qztI1cC', 0),
(305, 'Male', 'RuchiraMadushanka.Civics@gmail.com', '0712345678', '2026-04-15 05:40:16', 2, 1, '1999-04-22', '$2y$10$kHryjEV4YqNWTShfHJyNiuf2IZOLYOrRvs.GMHCnNaLcuoGYtOCZq', 0),
(306, 'Female', 'DinukiPerera.Aesthetic@gmail.com', '0712345678', '2026-04-15 05:42:22', 2, 1, '2000-04-30', '$2y$10$hhHIgKEIe7N93zx.USZKyeB7B1K3npPcvY7.wyrDODuSbBINixtuG', 0),
(307, 'Male', 'GihanSenanayake.Health@gmail.com', '0712345678', '2026-04-15 05:44:07', 2, 1, '2000-07-19', '$2y$10$ESqye1WruaDn3C/ywLdwPeYDTEbgP17ntRQy4ZcAOgSUO2t/ZQd.G', 0),
(308, 'Female', 'MalshaRajapaksha.PTS@gmail.com', '0712345678', '2026-04-15 05:46:00', 2, 1, '2000-01-08', '$2y$10$1jCWIzHFa7tNT.NMpIpNm.kB6lXxYCtffhiuaFCgQ1rQKLXIpqKsu', 0),
(309, 'Male', 'LahiruWickramasinghe.Religion@gmail.com', '0712345678', '2026-04-15 05:47:50', 2, 1, '1998-07-15', '$2y$10$mUNg3yMYnAlXrklrRvQoSeALVlPf/J9F6Ea894fc7iWGX2ttF/MJC', 0),
(310, 'Male', 'umesha@gmail.com', '0761248862', '2026-04-15 09:39:08', 3, 0, '2003-04-13', '$2y$10$sU2vWPBY/yqWCsw/hPyeJ.qtLeIy22Va2/EoCsrxv9s6RKpDSZgqy', 0),
(311, 'Male', 'NimalPerera.6A.parent@gmail.com', '0711234561', '2026-04-15 16:29:28', 4, 1, '2023-04-06', '$2y$10$wNYtEG5yrdpKot4mK.q0MOBOHTYlaHyAgVMxCnWWuwdEEAmH/OhnC', 0),
(312, 'Female', 'KasuniFernando.6A.parent@gmail.com', '0711231230', '2026-04-15 16:32:28', 4, 1, '2023-04-04', '$2y$10$z7ZkYsk9SmDKD4OXoOHaZO1BvgcjnzFo/A3nlpjnyyl0f5rD5RXei', 0),
(313, 'Male', 'PradeepSilva.6B.guardian@gmail.com', '0711234561', '2026-04-15 17:44:57', 4, 1, '2023-04-07', '$2y$10$nBqpVM2TQA5cIO/uqXvL8.pa5R4Xeq8V6iiLb35YVPO48jRMVFu/u', 0),
(314, 'Male', 'AjithJayasinghe.6B.guardian@gmail.com', '0711234561', '2026-04-15 18:00:54', 4, 1, '2023-04-05', '$2y$10$HMUctGwgS2KC6IzgpHYbfOMgQlAbOqCa1y8I.Qyi0JCi4YHolxpji', 0),
(315, 'Male', 'LalithGunawardena.7A.guardian@gmail.com', '0711234561', '2026-04-15 18:03:29', 4, 1, '2023-04-05', '$2y$10$A/eg.RijYjVykSEzZYhV8uYsMXflDB0jYT9WaNwMgOSmHxYqiEAZK', 0),
(316, 'Male', 'KalanaNirwan@gmail.com', '0711234561', '2026-04-16 04:38:04', 1, 0, '2023-04-06', '$2y$10$9PRgq9v3MXgGZTExn/SsDuszEpuW4rDJuGdnsX4EhsiXsSvsF1ZVK', 0),
(317, 'Male', 'AdithaWalisara@gmail.com', '0711234561', '2026-04-16 04:41:04', 1, 0, '2023-04-06', '$2y$10$B.9s8fAKXhRhhww6tB0mUu4/oFC6IeiR5FXOF7qc4sqjKSBgA8Xhi', 0),
(318, 'Male', 'Kalana@gmail.com', '0712345678', '2026-04-18 10:01:25', 1, 0, '2023-04-14', '$2y$10$Kfv8T85aJs5O3x.TF8Am4eTuCvqoUKl5b2GlqgfcDKdwf9KI78EEO', 0),
(320, 'Female', 'kalna@gmail.com', '0712205445', '2026-04-18 11:33:11', 1, 0, '2023-04-13', '$2y$10$1Q3C/2z2AiV1bD8ONx1e3uKjrIAKa1mNYE.m0MKMDt/Vr20Ft3C92', 0);

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
-- Dumping data for table `userAddress`
--

INSERT INTO `userAddress` (`userID`, `address_line1`, `address_line2`, `address_line3`) VALUES
(1, 'line 1', 'line 2', 'city'),
(278, 'Mr.Kalana Jinendra', 'Colombo-Badulla Road', 'Badulla'),
(279, 'No33,', 'Flower garden', 'Colombo 06'),
(280, 'No55, Galle Road', 'Galle', ''),
(282, 'line 1', 'line 2', ''),
(283, 'No.551, Clubs road', 'colombo 7', ''),
(284, '23/12', 'Flower Road', 'Badulla'),
(285, 'NO5 , Galle road', 'Galle', ''),
(286, 'No88, Walasmulla road', 'Walasmulla', ''),
(287, 'No99/1, Yatiyana road', 'Matara', ''),
(288, 'No77, Kalinga mawatha', 'Colombo 6', ''),
(289, 'No7, Garden lane', 'Colombo 7', ''),
(290, 'No123, ', 'Galle', ''),
(291, 'No7, ', 'Matara', ''),
(292, 'No 65', 'Mal Para', 'Badulla'),
(293, 'No 8', 'Flower Road', 'Colombo'),
(294, '74', 'Kosagama', 'Matara'),
(295, '5 ', 'Puwakpitiya', 'Galle'),
(296, '4/78', 'Hakmana', 'Kaneliya'),
(297, '8', 'Araliya Mawatha', 'Mawathagama'),
(298, 'Amila, Gampaha Road', 'Gampaha', ''),
(299, 'Kavindu, Benthota road', 'Hiriketiya', ''),
(300, 'No.77, Devananda MW', 'Colombo 10', ''),
(301, 'Hapan road, Gunadasa pura', 'Katugasthota', ''),
(302, 'Kankanam Waththa', 'Malsiripura', ''),
(303, 'Koppara Waththa', 'Galle', ''),
(304, 'No77, Chotty road', 'Badulla', ''),
(305, 'Kompanchca widiya', 'Colombo 10', ''),
(306, 'Dinuki Perera, Mahena watta', 'Gampola', ''),
(307, 'Gihan, Saththu Waththa', 'Panadura', ''),
(308, 'Malsha, Ape gama', 'Battaramulla', ''),
(309, 'No99, ', 'Matara', ''),
(310, 'agsrgdsrg', 'segsegs', 'egsegs'),
(311, '4', 'Kosagama', 'Mawathagama'),
(312, '6 ', 'Araliya Mawatha', 'Badulla'),
(313, '69', 'Walisara', 'Galigamuwa'),
(314, '7A', 'Malangamuwa Road', 'Anuradhapuraa'),
(315, '89', 'Kithalagama', 'Kadeolana'),
(316, '3', 'Waligamuwa', 'Malwana'),
(317, '78', 'Nawalapitiya Road', 'Kaligamuwa'),
(318, 'change', 'line 2', ''),
(320, 'line  change', 'line 2', '');

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
-- Dumping data for table `userName`
--

INSERT INTO `userName` (`userID`, `firstName`, `lastName`) VALUES
(1, 'System', 'Administrator'),
(278, 'Kalana', 'Jinendra'),
(279, 'Seniru', 'Senaweera'),
(280, 'Aditha', 'Anusara'),
(282, 'Thasindu', 'perera'),
(283, 'Nimal', 'Perera'),
(284, 'Roshan', 'Weerasinghe'),
(285, 'Kasuni', 'Fernando'),
(286, 'Sahan', 'Silva'),
(287, 'Tharindu', 'Jayasinghe'),
(288, 'Chamara', 'Gunawardena'),
(289, 'Dilshan', 'Wijesinghe'),
(290, 'Roshan', 'Abeysekara'),
(291, 'Ishan', 'Madushan'),
(292, 'Sachini', 'Ranasinghe'),
(293, 'Lahiru', 'Bandara'),
(294, 'Udari', 'Ekanayake'),
(295, 'Ramesh', 'Karunaratne'),
(296, 'Hansini', 'DeSilva'),
(297, 'Gayan', 'Wickramasinghe'),
(298, 'Amila', 'Perera'),
(299, 'Kavindu', 'Fernando'),
(300, 'Sandaru', 'Silva'),
(301, 'Tharuka', 'Jayasuriya'),
(302, 'Nimesh', 'Gunawardena'),
(303, 'Pasan', 'Wijeratne'),
(304, 'Isanka', 'Abeysinghe'),
(305, 'Ruchira', 'Madushanka'),
(306, 'Dinuki', 'Perera'),
(307, 'Gihan', 'Senanayake'),
(308, 'Malsha', 'Rajapaksha'),
(309, 'Lahiru', 'Wickramasinghe'),
(310, 'Umesha', 'Chamindu'),
(311, 'Nimal', 'Perera'),
(312, 'Kasuni', 'Fernando'),
(313, 'Pradeep', 'Silva'),
(314, 'Ajith', 'Jayasinghe'),
(315, 'Lalith', 'Gunawardena'),
(316, 'Kalana', 'Nirawan'),
(317, 'Aditha', 'Walisara'),
(318, 'Kalana', 'test'),
(320, 'Kalana', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `userRoles`
--

CREATE TABLE `userRoles` (
  `roleID` int(11) NOT NULL,
  `roleName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userRoles`
--

INSERT INTO `userRoles` (`roleID`, `roleName`) VALUES
(0, 'Admin'),
(1, 'MP'),
(2, 'Teacher'),
(3, 'Student'),
(4, 'Parent');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absentReasons`
--
ALTER TABLE `absentReasons`
  ADD PRIMARY KEY (`reasonID`),
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
-- Indexes for table `markDrafts`
--
ALTER TABLE `markDrafts`
  ADD PRIMARY KEY (`draftID`),
  ADD UNIQUE KEY `uq_mark_draft_scope` (`teacherID`,`subjectID`,`classID`,`term`,`studentID`),
  ADD KEY `idx_mark_draft_lookup` (`teacherID`,`subjectID`,`classID`,`term`);

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
  MODIFY `reasonID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `classID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `classTimetable`
--
ALTER TABLE `classTimetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=238;

--
-- AUTO_INCREMENT for table `examTimeTable`
--
ALTER TABLE `examTimeTable`
  MODIFY `timeTableID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `leaveRequests`
--
ALTER TABLE `leaveRequests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `mpId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `markDrafts`
--
ALTER TABLE `markDrafts`
  MODIFY `draftID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `marks`
--
ALTER TABLE `marks`
  MODIFY `markID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `materialID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `parentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `periods`
--
ALTER TABLE `periods`
  MODIFY `periodID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `reliefAssignments`
--
ALTER TABLE `reliefAssignments`
  MODIFY `reliefID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `roleID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schoolDays`
--
ALTER TABLE `schoolDays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `studentAttendance`
--
ALTER TABLE `studentAttendance`
  MODIFY `attendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `studentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `subjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `teacherAttendance`
--
ALTER TABLE `teacherAttendance`
  MODIFY `attendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacherID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=321;

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
