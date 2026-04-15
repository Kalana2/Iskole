#!/usr/bin/env python3

file_path = r'd:\Semester 4\SCS2202 - Group Project\Iskole\app\Views\teacher\examTimeTable.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Replace hidden timetable section
old_hidden = '''        <?php if ($hidden): ?>
          <p class="ann-body" style="margin-top:.25rem;">
            Timetable is currently hidden for <?= htmlspecialchars($gradeOptions[$selectedGrade]) ?>.
          </p>'''

new_hidden = '''        <?php if ($hidden): ?>
          <div class="ann-no-timetable">
            <svg class="ann-icon-empty" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
              <circle cx="12" cy="12" r="3"></circle>
              <line x1="1" y1="1" x2="23" y2="23" stroke-linecap="round" stroke-linejoin="round"></line>
            </svg>
            <p class="ann-body">Timetable is currently hidden for <?= htmlspecialchars($gradeOptions[$selectedGrade]) ?></p>
            <p class="ann-subtext">Contact your administrator to make it visible</p>
          </div>'''

content = content.replace(old_hidden, new_hidden)

# Replace no timetable section
old_no_tt = '''      <?php else: ?>
        <p class="ann-body" style="margin-top:.25rem;">
          No exam timetable uploaded yet for <?= htmlspecialchars($gradeOptions[$selectedGrade]) ?>.
        </p>'''

new_no_tt = '''      <?php else: ?>
        <div class="ann-no-timetable">
          <svg class="ann-icon-empty" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
          </svg>
          <p class="ann-body">No exam timetable uploaded yet for <?= htmlspecialchars($gradeOptions[$selectedGrade]) ?></p>
          <p class="ann-subtext">Please check back soon or contact your administrator</p>
        </div>'''

content = content.replace(old_no_tt, new_no_tt)

# Add CSS styles before closing section tag
css_block = '''
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
</style>'''

content = content.replace('</section>', css_block + '\n</section>')

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print('File updated successfully!')
