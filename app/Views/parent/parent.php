<div class="parent-container">
    <?php
    // Provide navigation items and active label for Teacher
    $items = ['Announcements', 'Acedemics', 'Attendance', 'Time Table', 'Behavior', 'Teachers', 'Requests'];
    $active = 'Announcements';
    include_once __DIR__ . '/../templates/navigation.php';
    ?>


    <?php
    // If you have real data, set $announcements before including this file.
    // Example:
    // $announcements = [ [ 'title' => '...', 'body' => '...', 'author' => '...', 'date' => 'YYYY-MM-DD', 'tags' => ['general'], 'pinned' => true, 'unread' => true ] ];
    if ($active === 'Announcements') {
        include __DIR__ . '/../templates/announcements.php';
    }
    ?>
</div>