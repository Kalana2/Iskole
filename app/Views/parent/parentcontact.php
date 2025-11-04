<?php
// Sample teacher contact data
$studentInfo = [
    'student_name' => 'Kasun Perera',
    'class' => 'Grade 10-A',
    'class_teacher' => 'Mrs. Samanthi Fernando'
];

$teachers = [
    [
        'name' => 'Mr. Ravindu Silva',
        'subject' => 'Mathematics',
        'room' => '203',
        'email' => 'ravindu.silva@iskole.lk',
        'phone' => '+94 77 234 5601',
        'availability' => 'Mon-Fri: 8:00 AM - 2:00 PM',
        'specialization' => 'Pure Mathematics',
        'experience' => '12 years',
        'is_class_teacher' => false
    ],
    [
        'name' => 'Mrs. Samanthi Fernando',
        'subject' => 'English Language',
        'room' => '105',
        'email' => 'samanthi.fernando@iskole.lk',
        'phone' => '+94 71 345 6702',
        'availability' => 'Mon-Fri: 7:30 AM - 1:30 PM',
        'specialization' => 'English Literature & Grammar',
        'experience' => '15 years',
        'is_class_teacher' => true
    ],
    [
        'name' => 'Mrs. Nilmini Perera',
        'subject' => 'Science',
        'room' => 'Lab-1',
        'email' => 'nilmini.perera@iskole.lk',
        'phone' => '+94 76 456 7803',
        'availability' => 'Mon-Fri: 8:00 AM - 2:00 PM',
        'specialization' => 'Physics & Chemistry',
        'experience' => '10 years',
        'is_class_teacher' => false
    ],
    [
        'name' => 'Mrs. Kumari Wijesinghe',
        'subject' => 'Geography',
        'room' => '201',
        'email' => 'kumari.wijesinghe@iskole.lk',
        'phone' => '+94 75 567 8904',
        'availability' => 'Mon-Fri: 7:45 AM - 1:45 PM',
        'specialization' => 'Human & Physical Geography',
        'experience' => '8 years',
        'is_class_teacher' => false
    ],
    [
        'name' => 'Mr. Prasad Gunawardena',
        'subject' => 'History',
        'room' => '204',
        'email' => 'prasad.gunawardena@iskole.lk',
        'phone' => '+94 77 678 9105',
        'availability' => 'Mon-Fri: 8:00 AM - 2:00 PM',
        'specialization' => 'Sri Lankan & World History',
        'experience' => '14 years',
        'is_class_teacher' => false
    ],
    [
        'name' => 'Mrs. Nadeeka Kumari',
        'subject' => 'Sinhala Language',
        'room' => '112',
        'email' => 'nadeeka.kumari@iskole.lk',
        'phone' => '+94 71 789 0206',
        'availability' => 'Mon-Fri: 7:30 AM - 1:30 PM',
        'specialization' => 'Sinhala Literature',
        'experience' => '11 years',
        'is_class_teacher' => false
    ],
    [
        'name' => 'Mrs. Priya Rajendran',
        'subject' => 'Tamil Language',
        'room' => '110',
        'email' => 'priya.rajendran@iskole.lk',
        'phone' => '+94 76 890 1307',
        'availability' => 'Mon-Fri: 8:00 AM - 2:00 PM',
        'specialization' => 'Tamil Literature & Grammar',
        'experience' => '9 years',
        'is_class_teacher' => false
    ],
    [
        'name' => 'Mr. Chandana Samaraweera',
        'subject' => 'Religion (Buddhism)',
        'room' => '115',
        'email' => 'chandana.samaraweera@iskole.lk',
        'phone' => '+94 75 901 2408',
        'availability' => 'Mon-Fri: 8:00 AM - 2:00 PM',
        'specialization' => 'Buddhist Philosophy',
        'experience' => '13 years',
        'is_class_teacher' => false
    ],
    [
        'name' => 'Mrs. Thilini Bandara',
        'subject' => 'P.T.S.',
        'room' => '307',
        'email' => 'thilini.bandara@iskole.lk',
        'phone' => '+94 77 012 3509',
        'availability' => 'Mon-Fri: 7:45 AM - 1:45 PM',
        'specialization' => 'Practical & Technical Skills',
        'experience' => '7 years',
        'is_class_teacher' => false
    ],
    [
        'name' => 'Mr. Asanka De Silva',
        'subject' => 'ICT',
        'room' => 'Comp-Lab',
        'email' => 'asanka.desilva@iskole.lk',
        'phone' => '+94 71 123 4610',
        'availability' => 'Mon-Fri: 8:00 AM - 2:00 PM',
        'specialization' => 'Computer Science & Programming',
        'experience' => '6 years',
        'is_class_teacher' => false
    ],
    [
        'name' => 'Ms. Dilini Rodrigo',
        'subject' => 'Aesthetics',
        'room' => 'Art Room',
        'email' => 'dilini.rodrigo@iskole.lk',
        'phone' => '+94 76 234 5711',
        'availability' => 'Mon-Fri: 8:00 AM - 2:00 PM',
        'specialization' => 'Art & Music',
        'experience' => '10 years',
        'is_class_teacher' => false
    ],
    [
        'name' => 'Mr. Mahinda Rathnayake',
        'subject' => 'Citizenship Education',
        'room' => '308',
        'email' => 'mahinda.rathnayake@iskole.lk',
        'phone' => '+94 75 345 6812',
        'availability' => 'Mon-Fri: 7:30 AM - 1:30 PM',
        'specialization' => 'Civic Education & Social Studies',
        'experience' => '16 years',
        'is_class_teacher' => false
    ]
];

// Statistics
$stats = [
    'total_teachers' => count($teachers),
    'class_teacher' => array_filter($teachers, fn($t) => $t['is_class_teacher'])[1]['name'] ?? 'N/A',
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
                    <p class="header-subtitle">Contact information for <?php echo $studentInfo['student_name']; ?>'s teachers - <?php echo $studentInfo['class']; ?></p>
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
                        <h3 class="teacher-name"><?php echo $teacher['name']; ?></h3>
                        <div class="subject-badge"><?php echo $teacher['subject']; ?></div>
                        <div class="teacher-meta">
                            <span class="meta-item">
                                <i class="fas fa-briefcase"></i>
                                <?php echo $teacher['experience']; ?> experience
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-door-open"></i>
                                Room <?php echo $teacher['room']; ?>
                            </span>
                        </div>
                    </div>

                    <div class="teacher-details">
                        <div class="specialization">
                            <i class="fas fa-graduation-cap"></i>
                            <span><?php echo $teacher['specialization']; ?></span>
                        </div>

                        <div class="availability">
                            <i class="fas fa-clock"></i>
                            <span><?php echo $teacher['availability']; ?></span>
                        </div>
                    </div>

                    <div class="contact-methods">
                        <a href="mailto:<?php echo $teacher['email']; ?>" class="contact-method email-method" title="Send Email">
                            <i class="fas fa-envelope"></i>
                            <span><?php echo $teacher['email']; ?></span>
                        </a>

                        <a href="tel:<?php echo $teacher['phone']; ?>" class="contact-method phone-method" title="Call">
                            <i class="fas fa-phone"></i>
                            <span><?php echo $teacher['phone']; ?></span>
                        </a>
                    </div>

                    <div class="card-actions">
                        <button class="action-btn primary-btn" onclick="sendMessage('<?php echo $teacher['name']; ?>')">
                            <i class="fas fa-paper-plane"></i>
                            Send Message
                        </button>
                        <button class="action-btn secondary-btn" onclick="scheduleMeeting('<?php echo $teacher['name']; ?>')">
                            <i class="fas fa-calendar-plus"></i>
                            Schedule Meeting
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- No Results Message -->
        <div class="no-results" id="noResults" style="display: none;">
            <i class="fas fa-search"></i>
            <h3>No Teachers Found</h3>
            <p>Try adjusting your search or filter criteria</p>
        </div>

        <!-- Help Section -->
        <div class="help-section">
            <div class="help-card">
                <i class="fas fa-info-circle"></i>
                <div>
                    <h4>Need Assistance?</h4>
                    <p>For urgent matters, please contact the school office at <strong>+94 11 234 5678</strong> or email <strong>office@iskole.lk</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('teacherSearch');
        const teacherCards = document.querySelectorAll('.teacher-card');
        const filterButtons = document.querySelectorAll('.filter-btn');
        const noResults = document.getElementById('noResults');
        const teachersGrid = document.getElementById('teachersGrid');

        let currentFilter = 'all';

        // Search functionality
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            filterTeachers(searchTerm, currentFilter);
        });

        // Filter functionality
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
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

    function sendMessage(teacherName) {
        alert('Opening messaging interface for ' + teacherName + '...\n\nThis feature will allow you to send direct messages to the teacher.');
    }

    function scheduleMeeting(teacherName) {
        alert('Opening meeting scheduler for ' + teacherName + '...\n\nThis feature will allow you to request a meeting with the teacher.');
    }
</script>