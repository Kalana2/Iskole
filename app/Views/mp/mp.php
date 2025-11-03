<div class="mp-container">
    <?php
    // Provide navigation items and active label for MP
    $items = ['Announcements', 'Academic', 'Requests', 'Management', 'Report'];
    $active = 'Announcements';
    include_once __DIR__ . '/../templates/navigation.php';
    ?>

    <?php
    // If you have real data, set $announcements before including this file.
    // Example:
    // $announcements = [ [ 'title' => '...', 'body' => '...', 'author' => '...', 'date' => 'YYYY-MM-DD', 'tags' => ['general'], 'pinned' => true, 'unread' => true ] ];
    include __DIR__ . '/announcemets.php';
    ?>
</div>