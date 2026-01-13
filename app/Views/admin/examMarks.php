<section class="exam-marks management">
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__ . '/../../Model/MarkEntryModel.php';
    $model = new MarkEntryModel();
    $teacherId = $_SESSION['user_id'] ?? null;
    $teacherInfo = $model->getTeacherInfo($teacherId);
    $grades = $model->getGrades();
    $classes = $model->getClasses();
    $terms = $model->getTerms();
    //$examTypes = $model->getExamTypes();
    $selectedGrade = '';
    $selectedClass = '';
    $selectedTerm = '';
    $selectedExamType = '';
    $students = [];
    $message = null;
    include_once __DIR__ . '/../templates/markEntry.php';
    ?>
</section>