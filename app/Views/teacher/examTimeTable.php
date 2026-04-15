<?php
include_once __DIR__ . '/../../Model/TeacherModel.php';
require_once __DIR__ . '/../../Model/ExamTimeTableModel.php';

$teacherModel = new TeacherModel();
$teacherData = $teacherModel->getGradeByUserID($_SESSION['user_id'] ?? 0);

// class teacher da kiyala balanna
$classID = $teacherData['classID'] ?? null;
$grade = $teacherData['grade'] ?? null;

// messages
$msg = $_SESSION['exam_tt_msg'] ?? null;
unset($_SESSION['exam_tt_msg']);

// grade options
$gradeOptions = ['6' => 'Grade 6', '7' => 'Grade 7', '8' => 'Grade 8', '9' => 'Grade 9'];

// timetable default values
$entry = null;
$imagePath = null;
$hidden = true;

// class teacher kenek nam witharai timetable load karanne
if (!empty($classID) && !empty($grade) && isset($gradeOptions[$grade])) {
    $examModel = new ExamTimeTableModel();
    $entry = $examModel->getByGrade($grade);

    $imagePath = $entry['file'] ?? null;
    $hidden = isset($entry['visibility']) ? !(bool)$entry['visibility'] : true;
}
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

      <?php if (empty($classID)): ?>
        <p class="ann-body" style="margin-top:.25rem;">
          Exam Time Table available only for class teachers.
        </p>

      <?php else: ?>
        <div class="ann-card-header">
          <div class="ann-badges">
            <span class="badge">
              <?= htmlspecialchars($gradeOptions[$grade] ?? ('Grade ' . $grade)) ?>
            </span>
          </div>
        </div>

        <?php if ($imagePath): ?>
          <?php if ($hidden): ?>
            <p class="ann-body" style="margin-top:.25rem;">
              Timetable is currently hidden for <?= htmlspecialchars($gradeOptions[$grade] ?? ('Grade ' . $grade)) ?>.
            </p>
          <?php else: ?>
            <div style="margin-top:.5rem;">
              <img src="<?= htmlspecialchars($imagePath) ?>"
                   alt="Exam Timetable - <?= htmlspecialchars($gradeOptions[$grade] ?? ('Grade ' . $grade)) ?>"
                   style="max-width:300%; margin:0 auto; height:auto; border-radius:12px; border:1px solid rgba(0,0,0,.08);" />
            </div>
          <?php endif; ?>
        <?php else: ?>
          <p class="ann-body" style="margin-top:.25rem;">
            No exam timetable uploaded yet for <?= htmlspecialchars($gradeOptions[$grade] ?? ('Grade ' . $grade)) ?>.
          </p>
        <?php endif; ?>
      <?php endif; ?>

    </article>
  </div>
</section>