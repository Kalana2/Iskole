<?php
// Exam timetable upload & display template
$msg = $_SESSION['exam_tt_msg'] ?? null;
unset($_SESSION['exam_tt_msg']);
$metaFile = __DIR__ . '/../../../public/assets/exam_timetable.json';
$meta = [];
if (file_exists($metaFile)) {
    $raw = file_get_contents($metaFile);
    $meta = json_decode($raw, true) ?: [];
}

// Grade selection
$gradeOptions = ['6' => 'Grade 6', '7' => 'Grade 7', '8' => 'Grade 8', '9' => 'Grade 9'];
$selectedGrade = isset($_GET['grade']) ? preg_replace('/[^0-9A-Za-z_-]/', '', $_GET['grade']) : '10';
if (!isset($gradeOptions[$selectedGrade])) { $selectedGrade = array_key_first($gradeOptions); }

// Read per-grade meta with legacy fallback
$entry = null;
if (isset($meta['file'])) {
    // legacy flat format
    $entry = $meta;
} elseif (isset($meta[$selectedGrade])) {
    $entry = $meta[$selectedGrade];
}
$imagePath = $entry['file'] ?? null;
$hidden = isset($entry['hidden']) ? (bool)$entry['hidden'] : false;
?>
<link rel="stylesheet" href="/css/announcements/announcements.css">
<section class="mp-announcements theme-light" aria-labelledby="exam-tt-title">
  <div class="ann-header">
    <div class="ann-title-wrap">
      <h2 id="exam-tt-title">Exam Time Table</h2>
      <p class="ann-subtitle">View timetable image</p>
    </div>
    <div class="ann-actions">
      <form method="get" action="/teacher" style="display:flex; align-items:center; gap:.5rem;">
        <input type="hidden" name="tab" value="Exam Time Table" />
        <label for="grade" class="ann-subtitle" style="margin:0;">Grade</label>
        <select id="grade" name="grade" onchange="this.form.submit()" class="tab-select" style="padding:.5rem .75rem; border-radius:10px; border:1px solid rgba(0,0,0,.15); background:#fff;">
          <?php foreach ($gradeOptions as $value => $label): ?>
            <option value="<?= htmlspecialchars($value) ?>" <?= $value == $selectedGrade ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
          <?php endforeach; ?>
        </select>
      </form>
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
          <p class="ann-body" style="margin-top:.25rem;">Timetable is currently hidden for <?= htmlspecialchars($gradeOptions[$selectedGrade]) ?>.</p>
        <?php else: ?>
          <div style="margin-top:.5rem;">
            <img src="<?= htmlspecialchars($imagePath) ?>" alt="Exam Timetable - <?= htmlspecialchars($gradeOptions[$selectedGrade]) ?>" style="max-width:100%; height:auto; border-radius:12px; border:1px solid rgba(0,0,0,.08);" />
          </div>
        <?php endif; ?>
      <?php else: ?>
        <p class="ann-body" style="margin-top:.25rem;">No exam timetable uploaded yet for <?= htmlspecialchars($gradeOptions[$selectedGrade]) ?>.</p>
      <?php endif; ?>
    </article>
  </div>
</section>

<style>
/* Scope overrides to the Exam Time Table section only */
section.mp-announcements[aria-labelledby="exam-tt-title"] .ann-grid {
  grid-template-columns: 1fr; /* Full-width cards so sections fit page size */
}
section.mp-announcements[aria-labelledby="exam-tt-title"] .ann-card img {
  width: 100%;
  height: auto;
  max-height: 75vh; /* Keep within viewport */
  object-fit: contain;
  display: block;
}
/* Make upload/toggle areas adapt to width */
section.mp-announcements[aria-labelledby="exam-tt-title"] .upload-form {
  display: grid !important;
  grid-template-columns: 1fr auto; /* file input grows, button hugs */
  gap: 0.6rem;
}
section.mp-announcements[aria-labelledby="exam-tt-title"] .upload-form input[type="file"] {
  min-width: 0; /* allow shrinking */
}
section.mp-announcements[aria-labelledby="exam-tt-title"] .toggle-form {
  display: flex;
  flex-wrap: wrap;
}
@media (max-width: 700px) {
  section.mp-announcements[aria-labelledby="exam-tt-title"] .upload-form {
    grid-template-columns: 1fr; /* stack on small screens */
  }
  section.mp-announcements[aria-labelledby="exam-tt-title"] .toggle-form {
    flex-direction: column;
  }
  section.mp-announcements[aria-labelledby="exam-tt-title"] .toggle-form .btn {
    width: 100%;
  }
}
</style>
