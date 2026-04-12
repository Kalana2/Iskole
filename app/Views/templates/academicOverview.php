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

    <div class="filter-container" aria-label="Academic filters">
        <form class="filter-form" id="aoFilterForm">
            <div class="filter-grid">
                <div class="form-group">
                    <label for="aoGradeSelect" class="form-label">Grade</label>
                    <select id="aoGradeSelect" class="form-select" aria-label="Select grade">
                        <option value="">Loading grades...</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="aoClassSelect" class="form-label">Class</label>
                    <select id="aoClassSelect" class="form-select" aria-label="Select class">
                        <option value="">Select grade first</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="aoTermSelect" class="form-label">Term</label>
                    <select id="aoTermSelect" class="form-select" aria-label="Select term">
                        <option value="">Loading terms...</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Grade Content Sections (rendered by JS from DB grades) -->
    <div id="aoGradeSections"></div>

    <!-- Scroll to Top Button -->
    <button class="scroll-to-top" id="scrollToTopBtn" aria-label="Scroll to top"></button>
</section>

<script src="/js/academicOverview/academicOverview.js"></script>