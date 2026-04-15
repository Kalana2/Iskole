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
  $hidden = isset($entry['visibility']) ? !(bool) $entry['visibility'] : true;
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
            <div class="ann-no-timetable">
              <svg class="ann-icon-empty" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
                <line x1="1" y1="1" x2="23" y2="23" stroke-linecap="round" stroke-linejoin="round"></line>
              </svg>
              <p class="ann-body">Timetable is currently hidden for
                <?= htmlspecialchars($gradeOptions[$grade] ?? ('Grade ' . $grade)) ?></p>
              <p class="ann-subtext">Contact your administrator to make it visible</p>
            </div>
          <?php else: ?>
            <div style="margin-top:.5rem;">
              <img src="<?= htmlspecialchars($imagePath) ?>"
                alt="Exam Timetable - <?= htmlspecialchars($gradeOptions[$grade] ?? ('Grade ' . $grade)) ?>"
                style="max-width:300%; margin:0 auto; height:auto; border-radius:12px; border:1px solid rgba(0,0,0,.08);" />
            </div>
          <?php endif; ?>
        <?php else: ?>
          <div class="ann-no-timetable">
            <svg class="ann-icon-empty" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <p class="ann-body">No exam timetable uploaded yet for
              <?= htmlspecialchars($gradeOptions[$grade] ?? ('Grade ' . $grade)) ?></p>
            <p class="ann-subtext">Please check back soon or contact your administrator</p>
          </div>
        <?php endif; ?>
      <?php endif; ?>

    </article>
  </div>

  <style>
    /* Scope overrides to the Exam Time Table section only */
    section.mp-announcements[aria-labelledby="exam-tt-title"] .ann-grid {
      grid-template-columns: 1fr;
      /* Full-width cards so sections fit page size */
    }

    section.mp-announcements[aria-labelledby="exam-tt-title"] .ann-card img {
      width: 100%;
      height: auto;
      max-height: 75vh;
      /* Keep within viewport */
      object-fit: contain;
      display: block;
    }

    /* Styling for empty timetable state */
    section.mp-announcements[aria-labelledby="exam-tt-title"] .ann-no-timetable {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 3rem 2rem;
      background: linear-gradient(135deg, #f5f7fa 0%, #f0f4f9 100%);
      border-radius: 12px;
      border: 2px dashed rgba(100, 116, 139, 0.2);
      text-align: center;
      gap: 1rem;
    }

    section.mp-announcements[aria-labelledby="exam-tt-title"] .ann-icon-empty {
      width: 64px;
      height: 64px;
      color: #94a3b8;
      opacity: 0.6;
      margin-bottom: 0.5rem;
    }

    section.mp-announcements[aria-labelledby="exam-tt-title"] .ann-no-timetable .ann-body {
      margin: 0;
      font-size: 1.1rem;
      color: #334155;
      font-weight: 500;
    }

    section.mp-announcements[aria-labelledby="exam-tt-title"] .ann-subtext {
      margin: 0;
      font-size: 0.95rem;
      color: #64748b;
      line-height: 1.5;
    }

    @media (max-width: 700px) {
      section.mp-announcements[aria-labelledby="exam-tt-title"] .ann-no-timetable {
        padding: 2rem 1.5rem;
      }

      section.mp-announcements[aria-labelledby="exam-tt-title"] .ann-icon-empty {
        width: 48px;
        height: 48px;
      }

      section.mp-announcements[aria-labelledby="exam-tt-title"] .ann-no-timetable .ann-body {
        font-size: 1rem;
      }
    }
  </style>
</section>