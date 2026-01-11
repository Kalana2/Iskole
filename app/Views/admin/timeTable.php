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
if (!isset($days)) { $days = []; }
if (!isset($periods)) { $periods = []; }
if (!isset($timetable)) { $timetable = []; }

// When this view is included from the Admin tab system, it doesn't go through
// TimeTableController::index(), so we populate the dropdown data here.
if (empty($grades) || empty($subjects)) {
	require_once __DIR__ . '/../../Model/TimeTableModel.php';
	$model = new TimeTableModel();
	if (empty($grades)) {
		$grades = $model->getGrades();
	}
	if (empty($subjects)) {
		$subjects = $model->getSubjects();
	}
}

include_once __DIR__ . '/../templates/ClassTimeTable.php';
?>
