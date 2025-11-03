<div class="mp-container">
    <?php
    // Provide navigation items and active label for MP
    $items = ['Announcements', 'Academic', 'Requests', 'Management', 'Report'];
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
    } else {
        include __DIR__ . '/../templates/announcements.php';
        include __DIR__ . '/../templates/createAnnouncement.php';
    }
    ?>
</div>