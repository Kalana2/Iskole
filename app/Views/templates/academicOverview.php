<?php
// filepath: /home/snake/Projects/Iskole/app/Views/templates/academicOverview.php
?>
<link rel="stylesheet" href="/css/academicOverview/academicOverview.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<section class="mp-academic" aria-labelledby="ao-title">
    <header class="mgmt-header">
        <div class="title-wrap">
            <h2 id="ao-title">Academic Overview</h2>
            <p class="subtitle">School-wide academic statistics</p>
        </div>
    </header>

    <!-- Quick Grade Navigation -->
    <div class="grade-nav">
        <button class="grade-nav-btn active" onclick="showGrade('grade-6')">Grade 6</button>
        <button class="grade-nav-btn" onclick="showGrade('grade-7')">Grade 7</button>
        <button class="grade-nav-btn" onclick="showGrade('grade-8')">Grade 8</button>
        <button class="grade-nav-btn" onclick="showGrade('grade-9')">Grade 9</button>



    </div>

    <!-- Term Selector -->
    <div class="term-select" style="margin-top:12px;">
        <label for="termSelect" class="visually-hidden">Select term</label>
        <label style="font-weight:600; margin-right:8px;">Term:</label>
        <select id="termSelect" aria-label="Select term" onchange="onTermChange(this.value)">
            <option value="term-1">Term 1</option>
            <option value="term-2">Term 2</option>
            <option value="term-3">Term 3</option>
        </select>
    </div>

    <?php
    // Sample data - Replace with actual data from database
    $gradeData = [
        'grade-9' => [
            'pass' => 91.3,
            'fail' => 8.7,
            'subjects' => [
                'Mathematics' => 85.5,
                'Science' => 78.3,
                'English' => 82.1,
                'History' => 76.8,
                'Geography' => 79.4,
                'Religion' => 88.2,
                'Tamil' => 74.6,
                'Aesthetics' => 91.5,
                'Physical Education' => 93.2,
                'Technical Skills' => 80.7
            ]
        ],
        'grade-8' => [
            'pass' => 98.7,
            'fail' => 1.3,
            'subjects' => [
                'Mathematics' => 88.2,
                'Science' => 82.1,
                'English' => 85.5,
                'History' => 79.3,
                'Geography' => 81.7,
                'Religion' => 90.4,
                'Tamil' => 77.8,
                'Aesthetics' => 93.1,
                'Physical Education' => 95.3,
                'Technical Skills' => 83.5
            ]
        ],
        'grade-7' => [
            'pass' => 96.6,
            'fail' => 3.4,
            'subjects' => [
                'Mathematics' => 82.7,
                'Science' => 80.5,
                'English' => 83.9,
                'History' => 78.1,
                'Geography' => 80.2,
                'Religion' => 87.6,
                'Tamil' => 76.3,
                'Aesthetics' => 90.8,
                'Physical Education' => 94.1,
                'Technical Skills' => 81.4
            ]
        ],
        'grade-6' => [
            'pass' => 89.3,
            'fail' => 10.7,
            'subjects' => [
                'Mathematics' => 79.8,
                'Science' => 75.4,
                'English' => 80.6,
                'History' => 74.9,
                'Geography' => 77.3,
                'Religion' => 85.1,
                'Tamil' => 73.2,
                'Aesthetics' => 88.9,
                'Physical Education' => 92.4,
                'Technical Skills' => 78.6
            ]
        ]
    ];
    ?>

    <!-- Grade Content Sections -->
    <?php foreach ($gradeData as $gradeId => $data): ?>
        <div class="grade-section" id="<?php echo $gradeId; ?>" style="display: none;">
            <div class="card">
                <div class="charts-grid">
                    <!-- Pass/Fail Rate Chart (Top) -->
                    <div class="chart-container chart-top">
                        <h3>Pass/Fail Rate</h3>
                        <p class="chart-subtitle">Students scoring above 30% pass, below 30% fail</p>
                        <div class="chart-wrapper">
                            <canvas id="<?php echo $gradeId; ?>-pass-fail-chart"
                                data-pass="<?php echo $data['pass']; ?>"
                                data-fail="<?php echo $data['fail']; ?>"></canvas>
                        </div>
                        <div class="chart-stats">
                            <div class="stat-item pass">
                                <span class="stat-label">Pass Rate</span>
                                <span class="stat-value"><?php echo $data['pass']; ?>%</span>
                            </div>
                            <div class="stat-item fail">
                                <span class="stat-label">Fail Rate</span>
                                <span class="stat-value"><?php echo $data['fail']; ?>%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Subject Average Chart (Bottom) -->
                    <div class="chart-container chart-bottom">
                        <h3>Subject Performance</h3>
                        <p class="chart-subtitle">Average scores across all subjects</p>
                        <div class="chart-wrapper">
                            <canvas id="<?php echo $gradeId; ?>-subjects-chart"
                                data-subjects='<?php echo json_encode($data['subjects']); ?>'></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Scroll to Top Button -->
    <button class="scroll-to-top" id="scrollToTopBtn" aria-label="Scroll to top"></button>
</section>

<script>
    // Term selection persistence and event emission
    (function() {
        function emit(term) {
            try {
                document.dispatchEvent(new CustomEvent('termChange', {
                    detail: {
                        term: term
                    }
                }));
            } catch (e) {
                // older browsers fallback
                var evt = document.createEvent('CustomEvent');
                evt.initCustomEvent('termChange', true, true, {
                    term: term
                });
                document.dispatchEvent(evt);
            }
        }

        window.onTermChange = function(term) {
            try {
                localStorage.setItem('selectedTerm', term);
            } catch (e) {}
            var container = document.querySelector('.mp-academic');
            if (container) container.dataset.term = term;
            emit(term);
            // small visual feedback (can be styled in CSS)
            console.log('Term changed to', term);
        };

        document.addEventListener('DOMContentLoaded', function() {
            var select = document.getElementById('termSelect');
            if (!select) return;
            var saved = 'term-1';
            try {
                saved = localStorage.getItem('selectedTerm') || saved;
            } catch (e) {}
            select.value = saved;
            var container = document.querySelector('.mp-academic');
            if (container) container.dataset.term = select.value;
            // emit initial term for other scripts that may need it
            emit(select.value);
        });
    })();
</script>

<script src="/js/academicOverview/academicOverview.js"></script>