<div class="parent-container">
    <?php
    // Provide navigation items and active label for Parent
    $items = ['Announcements', 'Acedemics', 'Attendance', 'Time Table', 'Behavior', 'Teachers', 'Requests'];
    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'Announcements';
    $active = in_array($tab, $items) ? $tab : 'Announcements';
    include_once __DIR__ . '/../templates/navigation.php';
    ?>


    <?php
    // If you have real data, set $announcements before including this file.
    // Example:
    // $announcements = [ [ 'title' => '...', 'body' => '...', 'author' => '...', 'date' => 'YYYY-MM-DD', 'tags' => ['general'], 'pinned' => true, 'unread' => true ] ];
    if ($active === 'Announcements') {
        include __DIR__ . '/parentannouncements.php';
    } else if ($active === 'Acedemics') {
        // Shared academic overview template
        include __DIR__ . '/parentacademicOverview.php';
    } else if ($active === 'Attendance') {
        // No dedicated attendance view for parent yet — use a generic report placeholder
        include __DIR__ . '/parentreport.php';
    } else if ($active === 'Time Table') {
        // No dedicated timetable template — use report placeholder for now
        include __DIR__ . '/parenttimetable.php';
    } else if ($active === 'Behavior') {
        // No dedicated behavior view yet — use report placeholder
        include __DIR__ . '/parentbehavior.php';
    } else if ($active === 'Teachers') {
        // Use shared user directory to list teachers
        include __DIR__ . '/parentcontact.php';
    } else if ($active === 'Requests') {
        include __DIR__ . '/parentrequests.php';
    } else {
        // Fallback
        include __DIR__ . '/parentannouncements.php';
    }
    ?>
</div>