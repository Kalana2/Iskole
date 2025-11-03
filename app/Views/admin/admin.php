<div class="admin-container">
    <?php
    // Provide navigation items and active label for MP
    $items = ['Announcements', 'Management'];
    $active = 'Announcements';
    include_once __DIR__ . '/../templates/navigation.php';
    ?>

    <?php
    // If you have real data, set $announcements before including this file.
    // Example:
    // $announcements = [ [ 'title' => '...', 'body' => '...', 'author' => '...', 'date' => 'YYYY-MM-DD', 'tags' => ['general'], 'pinned' => true, 'unread' => true ] ];
    if ($active === 'Management') {
        include __DIR__ . '/management.php';
    } else {
        include __DIR__ . '/../templates/announcements.php';
    }
    ?>
</div>