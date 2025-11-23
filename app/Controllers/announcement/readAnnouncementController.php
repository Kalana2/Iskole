<?php include_once __DIR__ . '/../../Model/AnnouncementModel.php';
$model = new AnnouncementModel();

// Get current user's role to filter announcements
$currentUserRole = $_SESSION['userRole'] ?? null;
$currentUserId = $_SESSION['user_id'] ?? null;

// Get all announcements with author information
$result = $model->getAllAnnouncements();

// Process the announcements to include proper author names and filter by role
$announcements = [];
if ($result) {
    foreach ($result as $announcement) {
        // Check if current user should see this announcement based on role flags
        $shouldShow = false;

        if ($currentUserRole == 0 && $announcement['admin'] == 1) { // Admin
            $shouldShow = true;
        } elseif ($currentUserRole == 1 && $announcement['mp'] == 1) { // MP
            $shouldShow = true;
        } elseif ($currentUserRole == 2 && $announcement['teacher'] == 1) { // Teacher
            $shouldShow = true;
        } elseif ($currentUserRole == 3 && $announcement['parent'] == 1) { // Parent
            $shouldShow = true;
        } elseif ($currentUserRole == 4 && $announcement['student'] == 1) { // Student
            $shouldShow = true;
        } elseif ($currentUserId == $announcement['published_by']) {
            // Always show announcements published by the current user
            $shouldShow = true;
        }

        if ($shouldShow) {
            // Format the announcement data for the template
            $announcements[] = [
                'id' => $announcement['announcement_id'],
                'title' => $announcement['title'],
                'body' => $announcement['content'],
                'author' => $announcement['roleName'] ?? 'Unknown',
                'author_id' => $announcement['published_by'],
                'date' => date('M j, Y', strtotime($announcement['created_at'])),
                'audience' => getAudienceString($announcement)
            ];
        }
    }
}

// Helper function to determine audience string from flags
function getAudienceString($announcement)
{
    $audiences = [];
    if ($announcement['admin'] == 1) $audiences[] = 'admin';
    if ($announcement['mp'] == 1) $audiences[] = 'mp';
    if ($announcement['teacher'] == 1) $audiences[] = 'teacher';
    if ($announcement['parent'] == 1) $audiences[] = 'parent';
    if ($announcement['student'] == 1) $audiences[] = 'student';

    if (count($audiences) >= 4) {
        return 'all';
    }
    return implode(',', $audiences);
}
