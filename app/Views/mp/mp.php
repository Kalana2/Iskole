<div class="mp-container">
    <?php
    // Provide navigation items and active label for MP
    $items = ['Announcements', 'Academic', 'Requests', 'Management', 'Attendance', 'Relief', 'Assign Class Teacher'];
    $tab = $tab ?? $_GET['tab'] ?? 'Announcements';
    $active = in_array($tab, $items) ? $tab : 'Announcements';
    include_once __DIR__ . '/../templates/navigation.php';
    ?>

    <?php
    // If you have real data, set $announcements before including this file.
    // Example:
    // $announcements = [ [ 'title' => '...', 'body' => '...', 'author' => '...', 'date' => 'YYYY-MM-DD', 'tags' => ['general'], 'pinned' => true, 'unread' => true ] ];
    if ($active === 'Management') {
        include __DIR__ . '/management.php';
    } else if ($active === 'Academic') {
        include __DIR__ . '/academic.php';
    } else if ($active === 'Requests') {
        include __DIR__ . '/requests.php';
    } else if ($active === 'Attendance') {
        include __DIR__ . '/teacherAttendance.php';
    } else if ($active === 'Relief') {
        include __DIR__ . '/relief.php';
    } else if ($active === 'Assign Class Teacher') {
        include __DIR__ . '/assignClassTeacher.php';
    } else {
        include __DIR__ . '/../../Controllers/announcement/addAnnouncementController.php';
        include __DIR__ . '/../templates/announcements.php';
        include __DIR__ . '/../templates/createAnnouncement.php';
    }
    ?>
</div>