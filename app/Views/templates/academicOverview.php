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

    <!-- Quick Grade Navigation (DB-driven) -->
    <div class="grade-nav" id="aoGradeNav" aria-label="Grade selector">
        <span class="ao-loading">Loading grades…</span>
    </div>

    <!-- Class Navigation (DB-driven by selected grade) -->
    <div class="class-nav" id="aoClassNav" aria-label="Class selector">
        <span class="ao-loading">Select a grade to load classes…</span>
    </div>

    <!-- Term Navigation (Term 1 / Term 2 / Term 3) -->
    <div class="term-nav" id="aoTermNav" aria-label="Term selector"></div>

    <!-- Grade Content Sections (rendered by JS from DB grades) -->
    <div id="aoGradeSections"></div>

    <!-- Scroll to Top Button -->
    <button class="scroll-to-top" id="scrollToTopBtn" aria-label="Scroll to top"></button>
</section>

<script src="/js/academicOverview/academicOverview.js"></script>