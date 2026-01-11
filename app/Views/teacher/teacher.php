<div class="teacher-container">
    <?php
    // Provide navigation items and active label for Teacher
    $items = ['Announcements', 'Attendance', 'Materials', 'Reports', 'Leave', 'Student Absence', 'Relief', 'Time Table', 'Mark Entry', 'Exam Time Table'];
    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'Announcements';
    $active = in_array($tab, $items) ? $tab : 'Announcements';

    include_once __DIR__ . '/../templates/navigation.php';
    ?>

    <?php
    // If you have real data, set $announcements before including this file.
    // Example:
    // $announcements = [ [ 'title' => '...', 'body' => '...', 'author' => '...', 'date' => 'YYYY-MM-DD', 'tags' => ['general'], 'pinned' => true, 'unread' => true ] ];
    if ($active === 'Materials') {
        include __DIR__ . '/materials.php';
    } else if ($active === 'Leave') {
        include __DIR__ . '/leave.php';
    } else if ($active === 'Relief') {
        include __DIR__ . '/relief.php';
    } else if ($active === 'Reports') {
        include __DIR__ . '/report.php';
    } else if ($active === 'Time Table') {
        include __DIR__ . '/timetable.php';
    } else if ($active === 'Mark Entry') {
        include __DIR__ . '/examMarks.php';
    } else if ($active === 'Attendance') {
        include __DIR__ . '/attendance.php';
    } else if ($active === 'Student Absence') {
        include __DIR__ . '/studentAbsence.php';
    } else if ($active === 'Exam Time Table') {
        include __DIR__ . '/examTimeTable.php';
    } 
    else {
        include __DIR__ . '/announcements.php';
    }
    ?>
</div>