<?php
include_once __DIR__ . '/../../Model/TeacherModel.php';
require_once __DIR__ . '/../../Model/ExamTimeTableModel.php';

$teacherModel = new TeacherModel();
$gradeData = $teacherModel->getGradeByUserID($_SESSION['user_id']);
$grade = $gradeData ? $gradeData['grade'] : null;

// messages
$msg = $_SESSION['exam_tt_msg'] ?? null;
unset($_SESSION['exam_tt_msg']);

// grade options (same as student)
$gradeOptions = ['6' => 'Grade 6', '7' => 'Grade 7', '8' => 'Grade 8', '9' => 'Grade 9'];
$selectedGrade = $grade;

if (!isset($gradeOptions[$selectedGrade])) {
  $selectedGrade = array_key_first($gradeOptions);
}

// timetable
$examModel = new ExamTimeTableModel();
$entry = $examModel->getByGrade($selectedGrade);

$imagePath = $entry['file'] ?? null;
$hidden = isset($entry['visibility']) ? !(bool)$entry['visibility'] : true;
?>

<link rel="stylesheet" href="/css/announcements/announcements.css">

<section class="mp-announcements theme-light" aria-labelledby="exam-tt-title">
  <div class="ann-header">
    <div class="ann-title-wrap">
      <h2 id="exam-tt-title">Exam Time Table</h2>
      <p class="ann-subtitle">View timetable image</p>
    </div>
  </div>

  <?php if ($msg): ?>
    <div class="ann-grid">
      <article class="ann-card" role="alert">
        <?= htmlspecialchars($msg) ?>
      </article>
    </div>
  <?php endif; ?>

  <div class="ann-grid" role="list">
    <article class="ann-card" role="listitem" aria-label="Current timetable preview">
      <div class="ann-card-header">
        <div class="ann-badges">
          <span class="badge">Preview</span>
          <span class="badge"><?= htmlspecialchars($gradeOptions[$selectedGrade]) ?></span>
        </div>
      </div>

      <?php if ($imagePath): ?>
        <?php if ($hidden): ?>
          <p class="ann-body" style="margin-top:.25rem;">
            Timetable is currently hidden for <?= htmlspecialchars($gradeOptions[$selectedGrade]) ?>.
          </p>
        <?php else: ?>
          <div style="margin-top:.5rem;">
            <img src="<?= htmlspecialchars($imagePath) ?>"
              alt="Exam Timetable - <?= htmlspecialchars($gradeOptions[$selectedGrade]) ?>"
              style="max-width:300%;margin: 0 auto;  height:auto; border-radius:12px; border:1px solid rgba(0,0,0,.08);" />
          </div>
        <?php endif; ?>
      <?php else: ?>
        <p class="ann-body" style="margin-top:.25rem;">
          No exam timetable uploaded yet for <?= htmlspecialchars($gradeOptions[$selectedGrade]) ?>.
        </p>
      <?php endif; ?>
    </article>
  </div>
</section>


