<?php
// filepath: /d:/Semester 4/SCS2202 - Group Project/Iskole/app/Views/teacher/uploadedMaterials.php

// Sample data for demonstration (remove when backend is implemented)
$sampleMaterials = [
    [
        'materialID' => 1,
        'title' => 'Introduction to Algebra',
        'description' => 'Basic algebraic concepts including variables, equations, and problem-solving techniques. This worksheet covers linear equations and simple word problems.',
        'grade' => '6',
        'class' => 'A',
        'subjectName' => 'Maths',
        'subjectID' => 1,
        'date' => '2025-11-01',
        'visibility' => 1,
        'fileName' => 'algebra_basics.pdf'
    ],
    [
        'materialID' => 2,
        'title' => 'Cell Structure and Functions',
        'description' => 'Detailed study material on plant and animal cells, including diagrams of cell organelles and their functions. Includes practice questions.',
        'grade' => '7',
        'class' => 'B',
        'subjectName' => 'Science',
        'subjectID' => 2,
        'date' => '2025-10-28',
        'visibility' => 1,
        'fileName' => 'cell_biology.pdf'
    ],
    [
        'materialID' => 3,
        'title' => 'Grammar Essentials - Tenses',
        'description' => 'Comprehensive guide to English tenses with examples and exercises. Covers present, past, and future tenses with common usage patterns.',
        'grade' => '8',
        'class' => 'A',
        'subjectName' => 'English',
        'subjectID' => 3,
        'date' => '2025-10-25',
        'visibility' => 0,
        'fileName' => 'tenses_worksheet.pdf'
    ],
    [
        'materialID' => 4,
        'title' => 'World War II - Key Events',
        'description' => 'Timeline and analysis of major events during World War II. Includes maps, key figures, and the impact on global history.',
        'grade' => '9',
        'class' => 'A',
        'subjectName' => 'History',
        'subjectID' => 4,
        'date' => '2025-10-20',
        'visibility' => 1,
        'fileName' => 'wwii_notes.pdf'
    ],
    [
        'materialID' => 5,
        'title' => 'Geometric Shapes and Properties',
        'description' => 'Study guide on triangles, quadrilaterals, and circles. Includes formulas for area, perimeter, and angle calculations.',
        'grade' => '6',
        'class' => 'B',
        'subjectName' => 'Maths',
        'subjectID' => 1,
        'date' => '2025-10-15',
        'visibility' => 1,
        'fileName' => 'geometry_basics.pdf'
    ],
    [
        'materialID' => 6,
        'title' => 'Climate Zones and Weather Patterns',
        'description' => 'Overview of different climate zones around the world, factors affecting climate, and weather prediction basics.',
        'grade' => '7',
        'class' => 'A',
        'subjectName' => 'Geography',
        'subjectID' => 5,
        'date' => '2025-10-10',
        'visibility' => 0,
        'fileName' => 'climate_study.pdf'
    ],
];

// Use sample data if backend materials not available
if (!isset($materials) || empty($materials)) {
    $materials = $sampleMaterials;
}
?>
<link rel="stylesheet" href="/css/announcements/announcements.css">
<link rel="stylesheet" href="/css/materials/teacherMaterials.css">

<section class="mp-announcements theme-light" aria-labelledby="materials-title">
    <div class="ann-header">
        <div class="ann-title-wrap">
            <h2 id="materials-title">Uploaded Materials</h2>
            <p class="ann-subtitle">Materials uploaded by you</p>
        </div>
        <div class="ann-actions">
            <div class="chip-group" role="tablist" aria-label="Material filters">
                <button class="chip active" role="tab" aria-selected="true" data-filter="all">All</button>
                <button class="chip" role="tab" aria-selected="false" data-filter="visible">Visible</button>
                <button class="chip" role="tab" aria-selected="false" data-filter="hidden">Hidden</button>
                <button class="chip" role="tab" aria-selected="false" data-filter="grade-6">Grade 6</button>
                <button class="chip" role="tab" aria-selected="false" data-filter="grade-7">Grade 7</button>
                <button class="chip" role="tab" aria-selected="false" data-filter="grade-8">Grade 8</button>
                <button class="chip" role="tab" aria-selected="false" data-filter="grade-9">Grade 9</button>
            </div>
        </div>
    </div>

    <div class="ann-grid" role="list">
        <?php foreach ($materials as $material): ?>
            <?php
            $classes = ['ann-card'];
            $classes[] = 'grade-' . htmlspecialchars($material['grade']);
            if ($material['visibility'] == 1) {
                $classes[] = 'is-visible';
            } else {
                $classes[] = 'is-hidden';
            }
            ?>
            <article role="listitem" class="<?php echo implode(' ', $classes); ?>" tabindex="0"
                aria-label="Material: <?php echo htmlspecialchars($material['title']); ?>">
                <div class="ann-card-header">
                    <div class="ann-badges">
                        <span class="badge"><?php echo htmlspecialchars($material['grade']); ?> <?php echo htmlspecialchars($material['class']); ?></span>
                        <span class="badge"><?php echo htmlspecialchars($material['subjectName']); ?></span>
                        <?php if ($material['visibility'] == 1): ?>
                            <span class="badge" style="background: rgba(45, 212, 191, 0.2); border-color: rgba(45, 212, 191, 0.4); color: #047857;">Visible</span>
                        <?php else: ?>
                            <span class="badge" style="background: rgba(251, 146, 60, 0.2); border-color: rgba(251, 146, 60, 0.4); color: #c2410c;">Hidden</span>
                        <?php endif; ?>
                    </div>
                    <time class="ann-date" datetime="<?php echo htmlspecialchars($material['date']); ?>">
                        <?php echo htmlspecialchars($material['date']); ?>
                    </time>
                </div>

                <h3 class="ann-title-text"><?php echo htmlspecialchars($material['title']); ?></h3>
                <p class="ann-body"><?php echo htmlspecialchars($material['description']); ?></p>

                <div class="ann-meta">
                    <span class="author">Class: <?php echo htmlspecialchars($material['grade']); ?><?php echo htmlspecialchars($material['class']); ?> | <?php echo htmlspecialchars($material['subjectName']); ?></span>
                </div>

                <div class="ann-actions-row">
                    <button class="btn ghost" type="button" onclick="openEditModal(<?= htmlspecialchars(json_encode($material)) ?>)">Edit</button>
                    <div class="spacer"></div>
                    <?php if ($material['visibility'] == 1): ?>
                        <button type="button" class="btn ghost" onclick="alert('Hide feature will be implemented with backend')">Hide</button>
                    <?php else: ?>
                        <button type="button" class="btn" onclick="alert('Show feature will be implemented with backend')">Show</button>
                    <?php endif; ?>
                    <button type="button" class="btn ghost" style="color: #dc2626; border-color: rgba(220, 38, 38, 0.3);" onclick="if(confirm('Are you sure you want to delete this material?')) alert('Delete feature will be implemented with backend')">Delete</button>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>


<script>
    (function() {
        const container = document.currentScript.previousElementSibling; // section.mp-announcements
        if (!container) return;
        const grid = container.querySelector('.ann-grid');
        const chips = container.querySelectorAll('.chip-group .chip');

        const applyFilter = (key) => {
            const cards = grid.querySelectorAll('.ann-card');
            cards.forEach(card => {
                const isVisible = card.classList.contains('is-visible');
                const isHidden = card.classList.contains('is-hidden');
                let show = true;

                switch (key) {
                    case 'all':
                        show = true;
                        break;
                    case 'visible':
                        show = isVisible;
                        break;
                    case 'hidden':
                        show = isHidden;
                        break;
                    case 'grade-6':
                        show = card.classList.contains('grade-6');
                        break;
                    case 'grade-7':
                        show = card.classList.contains('grade-7');
                        break;
                    case 'grade-8':
                        show = card.classList.contains('grade-8');
                        break;
                    case 'grade-9':
                        show = card.classList.contains('grade-9');
                        break;
                    default:
                        show = true;
                        break;
                }
                card.style.display = show ? '' : 'none';
            });
        };

        chips.forEach(chip => {
            chip.addEventListener('click', () => {
                chips.forEach(c => {
                    c.classList.remove('active');
                    c.setAttribute('aria-selected', 'false');
                });
                chip.classList.add('active');
                chip.setAttribute('aria-selected', 'true');
                applyFilter(chip.dataset.filter);
            });
        });
    })();
</script>

<!-- Edit Material Modal -->
<div id="editMaterialModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <div class="upload-section">
            <div class="heading">
                <h1>Edit Teaching Material</h1>
                <p>Update your lesson plans and worksheets</p>
            </div>
            <form id="editMaterialForm" onsubmit="handleFormSubmit(event)">
                <input type="hidden" name="materialID" id="edit-materialID">

                <div class="form-filter-tabs">
                    <div class="grade-tab">
                        <label for="edit-grade" class="tab-label">Select Grade:</label>
                        <select name="grade" id="edit-grade" class="tab-select" required>
                            <option value="null"></option>
                            <option value="6" class="mark-tabs-option">06</option>
                            <option value="7" class="mark-tabs-option">07</option>
                            <option value="8" class="mark-tabs-option">08</option>
                            <option value="9" class="mark-tabs-option">09</option>
                        </select>
                    </div>

                    <div class="class-tab">
                        <label for="edit-class" class="tab-label">Select Class:</label>
                        <select name="class" id="edit-class" class="tab-select" required>
                            <option value="null"></option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                        </select>
                    </div>

                    <div class="subject-tab">
                        <label for="edit-subject" class="tab-label">Select subject:</label>
                        <select name="subject" id="edit-subject" class="tab-select" required>
                            <option value="null"></option>
                            <option value="1">Maths</option>
                            <option value="2">Science</option>
                            <option value="3">English</option>
                            <option value="4">History</option>
                            <option value="5">Geography</option>
                            <option value="6">Aesthetics</option>
                            <option value="7">PTS</option>
                            <option value="8">Religion</option>
                            <option value="9">Health and Physical Education</option>
                            <option value="10">Tamil</option>
                            <option value="11">Citizenship Education</option>
                            <option value="12">Sinhala</option>
                        </select>
                    </div>
                </div>

                <div class="uploadform-elements">
                    <div>
                        <label for="edit-material-title" class="material-label">Material Title:</label>
                        <input type="text" id="edit-material-title" name="title" class="material-input" placeholder="Enter title" required />
                    </div>

                    <div>
                        <label for="edit-material-description" class="material-label">Description:</label>
                        <textarea id="edit-material-description" name="description" class="material-textarea" placeholder="Write a brief description..." rows="4" required></textarea>
                    </div>

                    <div>
                        <label for="edit-file-upload" class="material-label">Upload New File (Optional):</label>
                        <input type="file" id="edit-file-upload" name="file" class="material-file-input" />
                        <p id="current-file-name" class="material-content" style="margin-top: 5px;"></p>
                    </div>
                </div>

                <div class="submit-btn">
                    <button type="button" class="cancel-btn" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="publish-material-btn">Update Material</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditModal(material) {
        document.getElementById('editMaterialModal').style.display = 'block';
        document.getElementById('edit-materialID').value = material.materialID;
        document.getElementById('edit-grade').value = material.grade;
        document.getElementById('edit-class').value = material.class;
        document.getElementById('edit-subject').value = material.subjectID;
        document.getElementById('edit-material-title').value = material.title;
        document.getElementById('edit-material-description').value = material.description;

        if (material.fileName) {
            document.getElementById('current-file-name').textContent = 'Current file: ' + material.fileName;
        } else {
            document.getElementById('current-file-name').textContent = '';
        }
    }

    function closeEditModal() {
        document.getElementById('editMaterialModal').style.display = 'none';
    }

    function handleFormSubmit(event) {
        event.preventDefault();
        alert('Material update will be implemented with backend');
        closeEditModal();
    }

    // Close modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById('editMaterialModal');
        if (event.target == modal) {
            closeEditModal();
        }
    }

    // Close modal on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeEditModal();
        }
    });
</script>