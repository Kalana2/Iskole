<?php
// Get data from controller - if not set, use defaults
$studentInfo = $studentInfo ?? [
    'student_name' => 'N/A',
    'class' => 'N/A',
    'class_teacher' => 'N/A'
];

$teachers = $teachers ?? [];

// Statistics
$stats = [
    'total_teachers' => count($teachers),
    'class_teacher' => $studentInfo['class_teacher'] ?? 'N/A',
    'subjects' => count(array_unique(array_column($teachers, 'subject')))
];
?>

<link rel="stylesheet" href="/css/parentContact/parentContact.css">

<div class="parent-contact-section">
    <div class="contact-container">
        <!-- Header -->
        <div class="contact-header">
            <div class="header-content">
                <div>
                    <h1 class="header-title">
                        <i class="fas fa-address-book"></i>
                        Teachers Directory
                    </h1>
                    <p class="header-subtitle">Contact information for <?php echo $studentInfo['student_name']; ?>'s
                        teachers - <?php echo $studentInfo['class']; ?></p>
                </div>
                <div class="student-info-badge">
                    <div class="info-item">
                        <span class="info-label">Class Teacher</span>
                        <span class="info-value"><?php echo $studentInfo['class_teacher']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Total Teachers</span>
                        <span class="info-value"><?php echo $stats['total_teachers']; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="teacherSearch" placeholder="Search by teacher name or subject..." />
            </div>
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">
                    <i class="fas fa-list"></i> All Teachers
                </button>
                <button class="filter-btn" data-filter="class-teacher">
                    <i class="fas fa-star"></i> Class Teacher
                </button>
            </div>
        </div>

        <!-- Teachers Grid -->
        <div class="teachers-grid" id="teachersGrid">
            <?php if (!empty($teachers)): ?>
                <?php foreach ($teachers as $teacher): ?>
                    <div class="teacher-card <?php echo $teacher['is_class_teacher'] ? 'class-teacher-card' : ''; ?>"
                        data-subject="<?php echo strtolower($teacher['subject']); ?>"
                        data-name="<?php echo strtolower($teacher['name']); ?>">

                        <?php if ($teacher['is_class_teacher']): ?>
                            <div class="class-teacher-badge">
                                <i class="fas fa-star"></i>
                                <span>Class Teacher</span>
                            </div>
                        <?php endif; ?>

                        <div class="teacher-avatar">
                            <i class="fas fa-user-tie"></i>
                        </div>

                        <div class="teacher-main-info">
                            <h3 class="teacher-name"><?php echo htmlspecialchars($teacher['name']); ?></h3>
                            <div class="subject-badge"><?php echo htmlspecialchars($teacher['subject']); ?></div>
                        </div>

                        <!-- specialization and availability removed per UI update -->

                        <div class="contact-methods">
                            <a href="mailto:<?php echo htmlspecialchars($teacher['email']); ?>"
                                class="contact-method email-method" title="Send Email">
                                <i class="fas fa-envelope"></i>
                                <span><?php echo htmlspecialchars($teacher['email']); ?></span>
                            </a>

                            <a href="tel:<?php echo htmlspecialchars($teacher['phone']); ?>" class="contact-method phone-method"
                                title="Call">
                                <i class="fas fa-phone"></i>
                                <span><?php echo htmlspecialchars($teacher['phone']); ?></span>
                            </a>
                        </div>

                        <!-- message and schedule buttons removed per request -->
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-results" style="display: flex;">
                    <i class="fas fa-info-circle"></i>
                    <h3>No Teachers Found</h3>
                    <p>No teacher information is available for this class at the moment.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- No Results Message -->
        <div class="no-results" id="noResults" style="display: none;">
            <i class="fas fa-search"></i>
            <h3>No Teachers Found</h3>
            <p>Try adjusting your search or filter criteria</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('teacherSearch');
        const teacherCards = document.querySelectorAll('.teacher-card');
        const filterButtons = document.querySelectorAll('.filter-btn');
        const noResults = document.getElementById('noResults');
        const teachersGrid = document.getElementById('teachersGrid');

        let currentFilter = 'all';

        // Search functionality
        searchInput.addEventListener('input', function (e) {
            const searchTerm = e.target.value.toLowerCase();
            filterTeachers(searchTerm, currentFilter);
        });

        // Filter functionality
        filterButtons.forEach(button => {
            button.addEventListener('click', function () {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.getAttribute('data-filter');
                const searchTerm = searchInput.value.toLowerCase();
                filterTeachers(searchTerm, currentFilter);
            });
        });

        function filterTeachers(searchTerm, filter) {
            let visibleCount = 0;

            teacherCards.forEach(card => {
                const name = card.getAttribute('data-name');
                const subject = card.getAttribute('data-subject');
                const isClassTeacher = card.classList.contains('class-teacher-card');

                let matchesSearch = name.includes(searchTerm) || subject.includes(searchTerm);
                let matchesFilter = filter === 'all' || (filter === 'class-teacher' && isClassTeacher);

                if (matchesSearch && matchesFilter) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show/hide no results message
            if (visibleCount === 0) {
                noResults.style.display = 'flex';
                teachersGrid.style.display = 'none';
            } else {
                noResults.style.display = 'none';
                teachersGrid.style.display = 'grid';
            }
        }
    });

    // Messaging and scheduling actions removed from UI; contact via email/phone instead.
</script>