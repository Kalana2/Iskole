<?php
// Exam timetable upload & display template
require_once __DIR__ . '/../../Model/ExamTimeTableModel.php';

$msg = $_SESSION['exam_tt_msg'] ?? null;
unset($_SESSION['exam_tt_msg']);

// Grade selection
$gradeOptions = ['6' => 'Grade 6', '7' => 'Grade 7', '8' => 'Grade 8', '9' => 'Grade 9'];
$selectedGrade = isset($_GET['grade']) ? preg_replace('/[^0-9A-Za-z_-]/', '', $_GET['grade']) : '6';
if (!isset($gradeOptions[$selectedGrade])) {
  $selectedGrade = array_key_first($gradeOptions);
}

// Read from database
$model = new ExamTimeTableModel();
$entry = $model->getByGrade($selectedGrade);
$imagePath = $entry['file'] ?? null;
$hidden = isset($entry['visibility']) ? !(bool)$entry['visibility'] : false; // visibility: 1=visible, 0=hidden
?>
<link rel="stylesheet" href="/css/announcements/announcements.css">

<section class="mp-announcements theme-light" aria-labelledby="exam-tt-title">
  <div class="ann-header">
    <div class="ann-title-wrap">
      <h2 id="exam-tt-title">Exam Time Table</h2>
      <p class="ann-subtitle">Upload timetable image and control visibility per grade</p>
    </div>
    <div class="ann-actions">
      <div style="display:flex; align-items:center; gap:.5rem;">
        <label for="grade" class="ann-subtitle" style="margin:0;">Grade</label>
        <select id="grade" name="grade" onchange="window.location.href='/index.php?url=Admin&tab=Exam Time Table&grade=' + this.value" class="tab-select" style="padding:.5rem .75rem; border-radius:10px; border:1px solid rgba(0,0,0,.15); background:#fff;">
          <?php foreach ($gradeOptions as $value => $label): ?>
            <option value="<?= htmlspecialchars($value) ?>" <?= $value == $selectedGrade ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
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
    <article class="ann-card" role="listitem" aria-label="Upload or toggle timetable">
      <div class="ann-card-header">
        <div class="ann-badges">
          <span class="badge"><?= htmlspecialchars($gradeOptions[$selectedGrade]) ?></span>
          <?php if ($hidden): ?>
            <span class="badge" style="background: rgba(251, 146, 60, 0.2); border-color: rgba(251, 146, 60, 0.4); color: #c2410c;">Hidden</span>
          <?php else: ?>
            <span class="badge" style="background: rgba(45, 212, 191, 0.2); border-color: rgba(45, 212, 191, 0.4); color: #047857;">Visible</span>
          <?php endif; ?>
        </div>
      </div>

      <h3 class="ann-title-text" style="margin-top:.25rem;">Manage Exam Timetable</h3>
      <p class="ann-body">Upload or replace the timetable image, and toggle visibility for the selected grade.</p>

      <!-- Upload section -->
      <form method="POST" action="/index.php?url=ExamTimeTable/upload" enctype="multipart/form-data" style="margin-top:.5rem;">
        <input type="hidden" name="grade" value="<?= htmlspecialchars($selectedGrade) ?>" />
        <div class="upload-form" style="display:flex; gap:.6rem; align-items:center; flex-wrap:wrap;">
          <input type="file" name="exam_image" accept="image/*" style="padding:.55rem .75rem; border:1px solid rgba(0,0,0,.15); border-radius:10px; background:#fff;" />
          <button type="submit" name="action" value="upload" class="btn">Upload</button>
        </div>
      </form>

      <!-- Visibility toggle -->
      <form method="POST" action="/index.php?url=ExamTimeTable/upload" style="margin-top:.6rem;">
        <input type="hidden" name="grade" value="<?= htmlspecialchars($selectedGrade) ?>" />
        <input type="hidden" name="hidden" value="<?= $hidden ? '0' : '1' ?>" />
        <div class="toggle-form" style="display:flex; gap:.5rem;">
          <button type="submit" name="action" value="toggle" class="btn <?= $hidden ? '' : 'ghost' ?>"><?= $hidden ? 'Show Timetable' : 'Hide Timetable' ?></button>
        </div>
      </form>
    </article>

    <article class="ann-card" role="listitem" aria-label="Current timetable preview">
      <div class="ann-card-header">
        <div class="ann-badges">
          <span class="badge">Preview</span>
          <span class="badge"><?= htmlspecialchars($gradeOptions[$selectedGrade]) ?></span>
        </div>
      </div>

      <?php if ($imagePath): ?>
        <div style="margin-top:.5rem;">
          <?php if ($hidden): ?>
            <p class="ann-body" style="margin-bottom:.5rem; padding:.5rem; background:rgba(251, 146, 60, 0.1); border-radius:8px; color:#c2410c;">
              ⚠️ This timetable is currently hidden from students
            </p>
          <?php endif; ?>
          <img src="<?= htmlspecialchars($imagePath) ?>" alt="Exam Timetable - <?= htmlspecialchars($gradeOptions[$selectedGrade]) ?>" style="max-width:100%; height:auto; border-radius:12px; border:1px solid rgba(0,0,0,.08);" />
        </div>
      <?php else: ?>
        <p class="ann-body" style="margin-top:.25rem;">No exam timetable uploaded yet for <?= htmlspecialchars($gradeOptions[$selectedGrade]) ?>.</p>
      <?php endif; ?>
    </article>
  </div>
</section>

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

  /* Make upload/toggle areas adapt to width */
  section.mp-announcements[aria-labelledby="exam-tt-title"] .upload-form {
    display: grid !important;
    grid-template-columns: 1fr auto;
    /* file input grows, button hugs */
    gap: 0.6rem;
  }

  section.mp-announcements[aria-labelledby="exam-tt-title"] .upload-form input[type="file"] {
    min-width: 0;
    /* allow shrinking */
  }

  section.mp-announcements[aria-labelledby="exam-tt-title"] .toggle-form {
    display: flex;
    flex-wrap: wrap;
  }

  @media (max-width: 700px) {
    section.mp-announcements[aria-labelledby="exam-tt-title"] .upload-form {
      grid-template-columns: 1fr;
      /* stack on small screens */
    }

    section.mp-announcements[aria-labelledby="exam-tt-title"] .toggle-form {
      flex-direction: column;
    }

    section.mp-announcements[aria-labelledby="exam-tt-title"] .toggle-form .btn {
      width: 100%;
    }
  }
</style>
