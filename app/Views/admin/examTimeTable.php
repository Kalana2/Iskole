<section class="exam-marks management">
    <?php
    // debug output for 'exam_tt_msg' (flashdata first, then regular session)
    $msg = $_SESSION['exam_tt_msg'] ?? null;
    if ($msg !== null) {
        echo '<pre class="debug">exam_tt_msg debug: ' . htmlspecialchars(print_r($msg, true), ENT_QUOTES, 'UTF-8') . '</pre>';
    }
    ?>

    <?php include_once __DIR__ . '/../templates/examTimeTable.php'; ?>
</section>