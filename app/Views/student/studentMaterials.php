<?php


// Sample data for demonstration (remove when backend is implemented)
$sampleMaterials = [
    [
        'materialID' => 1,
        'title' => 'Introduction to Algebra',
        'description' => 'Basic algebraic concepts including variables, equations, and problem-solving techniques. This worksheet covers linear equations and simple word problems.',
        'subjectName' => 'Maths',
        'fName' => 'John',
        'lName' => 'Smith',
        'date' => '2025-11-01'
    ],
    [
        'materialID' => 2,
        'title' => 'Cell Structure and Functions',
        'description' => 'Detailed study material on plant and animal cells, including diagrams of cell organelles and their functions. Includes practice questions.',
        'subjectName' => 'Science',
        'fName' => 'Sarah',
        'lName' => 'Johnson',
        'date' => '2025-10-28'
    ],
    [
        'materialID' => 3,
        'title' => 'Grammar Essentials - Tenses',
        'description' => 'Comprehensive guide to English tenses with examples and exercises. Covers present, past, and future tenses with common usage patterns.',
        'subjectName' => 'English',
        'fName' => 'Michael',
        'lName' => 'Brown',
        'date' => '2025-10-25'
    ],
    [
        'materialID' => 4,
        'title' => 'World War II - Key Events',
        'description' => 'Timeline and analysis of major events during World War II. Includes maps, key figures, and the impact on global history.',
        'subjectName' => 'History',
        'fName' => 'Emily',
        'lName' => 'Davis',
        'date' => '2025-10-20'
    ],
    [
        'materialID' => 5,
        'title' => 'Geometric Shapes and Properties',
        'description' => 'Study guide on triangles, quadrilaterals, and circles. Includes formulas for area, perimeter, and angle calculations.',
        'subjectName' => 'Maths',
        'fName' => 'John',
        'lName' => 'Smith',
        'date' => '2025-10-15'
    ],
    [
        'materialID' => 6,
        'title' => 'Climate Zones and Weather Patterns',
        'description' => 'Overview of different climate zones around the world, factors affecting climate, and weather prediction basics.',
        'subjectName' => 'Geography',
        'fName' => 'David',
        'lName' => 'Wilson',
        'date' => '2025-10-10'
    ],
    [
        'materialID' => 7,
        'title' => 'Chemical Reactions and Equations',
        'description' => 'Introduction to chemical reactions, balancing equations, and understanding different types of chemical changes.',
        'subjectName' => 'Science',
        'fName' => 'Sarah',
        'lName' => 'Johnson',
        'date' => '2025-10-05'
    ],
    [
        'materialID' => 8,
        'title' => 'Essay Writing Techniques',
        'description' => 'Learn how to structure essays, develop arguments, and improve your writing style. Includes sample essays and writing prompts.',
        'subjectName' => 'English',
        'fName' => 'Michael',
        'lName' => 'Brown',
        'date' => '2025-09-30'
    ],
    [
        'materialID' => 9,
        'title' => 'Ancient Civilizations',
        'description' => 'Explore the rise and fall of ancient civilizations including Egypt, Greece, and Rome. Learn about their contributions to modern society.',
        'subjectName' => 'History',
        'fName' => 'Emily',
        'lName' => 'Davis',
        'date' => '2025-09-25'
    ],
    [
        'materialID' => 10,
        'title' => 'Pythagoras Theorem Applications',
        'description' => 'Practical applications of Pythagoras theorem in solving real-world problems. Includes step-by-step examples and practice exercises.',
        'subjectName' => 'Maths',
        'fName' => 'John',
        'lName' => 'Smith',
        'date' => '2025-09-20'
    ],
];

// Use sample data if backend materials not available
if (empty($materials)) {
    $materials = $sampleMaterials;
}
?>
<link rel="stylesheet" href="/css/announcements/announcements.css">
<link rel="stylesheet" href="/css/materials/teacherMaterials.css">

<section class="mp-announcements theme-light" aria-labelledby="materials-title">
    <div class="ann-header">
        <div class="ann-title-wrap">
            <h2 id="materials-title">Study Materials</h2>
            <p class="ann-subtitle">Access lesson plans, worksheets, and assignments</p>
        </div>
        <div class="ann-actions">
            <div class="chip-group" role="tablist" aria-label="Material filters">
                <button class="chip active" role="tab" aria-selected="true" data-filter="all">All</button>
                <button class="chip" role="tab" aria-selected="false" data-filter="maths">Maths</button>
                <button class="chip" role="tab" aria-selected="false" data-filter="science">Science</button>
                <button class="chip" role="tab" aria-selected="false" data-filter="english">English</button>
                <button class="chip" role="tab" aria-selected="false" data-filter="history">History</button>
                <button class="chip" role="tab" aria-selected="false" data-filter="other">Other</button>
            </div>
        </div>
    </div>

    <?php if (empty($materials)): ?>
        <div class="ann-grid" role="list">
            <p style="grid-column: 1 / -1; text-align: center; padding: 2rem; color: var(--text-secondary);">No materials available for your class.</p>
        </div>
    <?php else: ?>
        <div class="ann-grid" role="list">
            <?php foreach ($materials as $material): ?>
                <?php
                $subjectClass = strtolower(str_replace(' ', '-', $material['subjectName']));
                ?>
                <article role="listitem" class="ann-card subject-<?php echo htmlspecialchars($subjectClass); ?>" tabindex="0"
                    aria-label="Material: <?php echo htmlspecialchars($material['title']); ?>">
                    <div class="ann-card-header">
                        <div class="ann-badges">
                            <span class="badge"><?php echo htmlspecialchars($material['subjectName']); ?></span>
                        </div>
                        <time class="ann-date" datetime="<?php echo htmlspecialchars($material['date']); ?>">
                            <?php echo htmlspecialchars($material['date']); ?>
                        </time>
                    </div>

                    <h3 class="ann-title-text"><?php echo htmlspecialchars($material['title']); ?></h3>
                    <p class="ann-body"><?php echo htmlspecialchars($material['description']); ?></p>

                    <div class="ann-meta">
                        <span class="author">By <?php echo htmlspecialchars($material['fName'] . " " . $material['lName']); ?></span>
                    </div>

                    <div class="ann-actions-row">
                        <div class="spacer"></div>
                        <form method="POST" action="../../app/Controllers/materialController.php" target="_blank" style="display: inline;">
                            <input type="hidden" name="download" value="1">
                            <input type="hidden" name="materialID" value="<?= htmlspecialchars($material['materialID']); ?>">
                            <button type="submit" class="btn">Download</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
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
                let show = true;

                if (key === 'all') {
                    show = true;
                } else if (key === 'maths') {
                    show = card.classList.contains('subject-maths');
                } else if (key === 'science') {
                    show = card.classList.contains('subject-science');
                } else if (key === 'english') {
                    show = card.classList.contains('subject-english');
                } else if (key === 'history') {
                    show = card.classList.contains('subject-history');
                } else if (key === 'other') {
                    show = !card.classList.contains('subject-maths') &&
                        !card.classList.contains('subject-science') &&
                        !card.classList.contains('subject-english') &&
                        !card.classList.contains('subject-history');
                } else {
                    show = true;
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