<div class="student-container">
    <?php
    // Provide navigation items and active label for Student
    $items = ['Announcements', 'My Marks', 'Attendance', 'Time Table', 'Materials'];
    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'Announcements';
    $active = in_array($tab, $items) ? $tab : 'Announcements';
    include_once __DIR__ . '/../templates/navigation.php';
    ?>

    <?php
    // If you have real data, set $announcements before including this file.
    // Example:
    // $announcements = [ [ 'title' => '...', 'body' => '...', 'author' => '...', 'date' => 'YYYY-MM-DD', 'tags' => ['general'], 'pinned' => true, 'unread' => true ] ];
    if ($active === 'Announcements') {
        include __DIR__ . '/studentAnnouncements.php';
    } else if ($active === 'My Marks') {
        // Student marks/report placeholder
        include __DIR__ . '/studentMarks.php';
    } else if ($active === 'Attendance') {
        // Use report placeholder for attendance view until a dedicated view exists
        include __DIR__ . '/studentAttendance.php';
    } else if ($active === 'Time Table') {
        // No dedicated timetable template — use report placeholder for now
        include __DIR__ . '/studentTimeTable.php';
    } else if ($active === 'Materials') {
        // Materials not implemented yet — use report placeholder
        include __DIR__ . '/studentMaterials.php';
    } else {
        // Fallback to announcements
        include __DIR__ . '/studentAnnouncements.php';
    }
    ?>
</div>