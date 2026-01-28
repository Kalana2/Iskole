<div class="admin-container">
    <?php
    // Provide navigation items and active label for MP
    $items = ['Announcements', 'Management', 'Attendance', 'Time Tables', 'Class & Subjects', 'Exam Time Table', "Relief"];
    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'Announcements';
    $active = in_array($tab, $items) ? $tab : 'Announcements';
    include_once __DIR__ . '/../templates/navigation.php';
    ?>

    <?php

    // If you have real data, set $announcements before including this file.
    // Example:
    // $announcements = [ [ 'title' => '...', 'body' => '...', 'author' => '...', 'date' => 'YYYY-MM-DD', 'tags' => ['general'], 'pinned' => true, 'unread' => true ] ];
    switch ($active) {
        case 'Management':
            include __DIR__ . '/management.php';
            break;
        case 'Material':
            include __DIR__ . '/material.php';
            break;
        case 'Attendance':
            include __DIR__ . '/studentAttendance.php';
            include __DIR__ . '/teacherAttendance.php';
            break;
        case 'Exam Marks':
            include __DIR__ . '/examMarks.php';
            break;
        case 'Time Tables':
            include __DIR__ . '/timeTable.php';
            break;
        case 'Relief':
            include __DIR__ . '/relief.php';
            break;
        case 'Class & Subjects':
            include __DIR__ . '/classSubjects.php';
            break;
        case 'Exam Time Table':
            include __DIR__ . '/examTimeTable.php';
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