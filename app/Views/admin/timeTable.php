<?php
// student class time table management
// teachers timetable management

// Ensure all template variables are defined with safe defaults
if (!isset($grades)) { $grades = []; }
if (!isset($classes)) { $classes = []; }
if (!isset($subjects)) { $subjects = []; }
if (!isset($teachersMapping)) { $teachersMapping = []; }
if (!isset($selectedGrade)) { $selectedGrade = ''; }
if (!isset($selectedClass)) { $selectedClass = ''; }

include_once __DIR__ . '/../templates/CreatetimeTable.php';
?>
