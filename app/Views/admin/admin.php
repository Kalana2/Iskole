<div class="admin-container">
    <?php
    // Provide navigation items and active label for MP
    $items = ['Announcements', 'Management', 'Material', 'Student Attendance', 'Teacher Attendance', 'Exam Marks', 'Time Tables', 'Relief', 'Class & Subjects', 'Exam Time Table', 'TLR', 'Behaviour'];
    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'Announcements';
    $active = in_array($tab, $items) ? $tab : 'Announcements';
    include_once __DIR__ . '/../templates/navigation.php';
    ?>

    <?php

    // If you have real data, set $announcements before including this file.
    // Example:
    // $announcements = [ [ 'title' => '...', 'body' => '...', 'author' => '...', 'date' => 'YYYY-MM-DD', 'tags' => ['general'], 'pinned' => true, 'unread' => true ] ];
    if ($active === 'Management') {
        include __DIR__ . '/management.php';
    } elseif ($active === 'Material') {
        include __DIR__ . '/material.php';
    } elseif ($active === 'Student Attendance') {
        include __DIR__ . '/studentAttendance.php';
    } elseif ($active === 'Teacher Attendance') {
        include __DIR__ . '/teacherAttendance.php';
    } elseif ($active === 'Exam Marks') {
        include __DIR__ . '/examMarks.php';
    } elseif ($active === 'Time Tables') {
        include __DIR__ . '/timeTable.php';
    } elseif ($active === 'Relief') {
        include __DIR__ . '/relief.php';
    } elseif ($active === 'Class & Subjects') {
        include __DIR__ . '/classAndSubjects.php';
    } elseif ($active === 'Exam Time Table') {
        include __DIR__ . '/examTimeTable.php';
    } elseif ($active === 'Teacher Leave Requests') {
        include __DIR__ . '/teacherLeaveReq.php';
    } elseif ($active === 'Behaviour') {
        include __DIR__ . '/behaviourReport.php';
    } else {
        include __DIR__ . '/announcements.php';
    }
    ?>
</div>