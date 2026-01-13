<?php include_once __DIR__ . '/../../Model/AnnouncementModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['announcementTitle']) && isset($_POST['announcementMessage']) && isset($_POST['roles'])) {
            $announcementTitle = trim($_POST['announcementTitle']);
            $content = trim($_POST['announcementMessage']);
            $audienceGroup = $_POST['roles'] ?? [];

            if (empty($announcementTitle) || empty($content) || empty($audienceGroup)) {
                // Handle validation error
                throw new Exception("Some empty fields detected.");
            }
        } else {
            // Handle missing fields
            throw new Exception("Form data is incomplete.");
        }
    } catch (Exception $e) {
        // Handle exception
        echo "Error: " . $e->getMessage();
        exit;
    }



    // additional data from session
    $published_by = $_SESSION['user_id'];
    $role = $_SESSION['userRole'];

    $announcementModel = new AnnouncementModel();
    try {
        $result = $announcementModel->addAnnouncement([
            'title' => $announcementTitle,
            'content'           => $content,
            'published_by'      => $published_by,
            'role'              => $role,

            // flags for filtering
            'admin'   => in_array('admin', $audienceGroup, true) ? 1 : 0,
            'mp'      => in_array('mp', $audienceGroup, true) ? 1 : 0,
            'teacher' => in_array('teacher', $audienceGroup, true) ? 1 : 0,
            'parent'  => in_array('parent', $audienceGroup, true) ? 1 : 0,
            'student' => in_array('student', $audienceGroup, true) ? 1 : 0,
        ]);


        if ($result) {
            $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            echo "<script>
                    window.location.replace('{$currentPath}?tab=Announcements');
                </script>";
            exit;
        } else {
            $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            echo "<script>
                    alert('Failed to publish announcement. Please try again.');
                    window.location.replace('{$currentPath}?tab=Announcements');
                </script>";
            exit;
        }
    } catch (Exception $e) {
        // Handle exception
        echo "Error: " . $e->getMessage();
        exit;
    }
}
