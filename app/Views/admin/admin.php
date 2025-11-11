<!-- Include behaviour admin styles -->
<link rel="stylesheet" href="/public/css/variables.css">
<link rel="stylesheet" href="/public/css/admin/behaviourAdmin.css">

<div class="admin-container">
    <?php
    // Provide navigation items and active label for MP
    $items = ['Announcements', 'User', 'Class & Subjects', 'Material', 'Attendance', 'Exam', 'Time Tables', 'Relief', 'TLR', 'Behaviour'];
    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'Announcements';
    $active = in_array($tab, $items) ? $tab : 'Announcements';
    include_once __DIR__ . '/../templates/navigation.php';
    ?>

    <?php

    // If you have real data, set $announcements before including this file.
    // Example:
    // $announcements = [ [ 'title' => '...', 'body' => '...', 'author' => '...', 'date' => 'YYYY-MM-DD', 'tags' => ['general'], 'pinned' => true, 'unread' => true ] ];
    switch ($active) {
        case 'User':
            include __DIR__ . '/management.php';
            break;
        case 'Material':
            include __DIR__ . '/material.php';
            break;
        case 'Attendance':
            include __DIR__ . '/studentAttendance.php';
            break;
        case 'Exam':
            include __DIR__ . '/exam.php';
            break;
        case 'Time Tables':
            include __DIR__ . '/timeTable.php';
            break;
        case 'Relief':
            include __DIR__ . '/relief.php';
            break;
        case 'Class & Subjects':
            include __DIR__ . '/classAndSubjects.php';
            break;
        case 'TLR':
            include __DIR__ . '/teacherLeaveReq.php';
            break;
        case 'Behaviour':
            include __DIR__ . '/behaviourReport.php';
            break;
        default:
            include __DIR__ . '/announcements.php';
            break;
    }
    ?>
</div>