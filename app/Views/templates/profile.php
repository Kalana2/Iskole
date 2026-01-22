<link rel="stylesheet" href="/css/profile/profile.css">
<section class="profile-clean" aria-labelledby="profile-title">

    <header class="profile-header">
        <button onclick="history.back()" class="back-btn" title="Go back to previous page">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            <span>Back</span>
        </button>
        <div class="header-content">
            <h1 id="profile-title">Profile</h1>
            <p class="subtitle">Your personal information</p>
        </div>
    </header>

    <?php
    // Your existing PHP logic (unchanged - just cleaner variable names)
    $user = $user ?? [];
    $fullName = trim(($user['firstName'] ?? $user['fName'] ?? '') . ' ' . ($user['lastName'] ?? $user['lName'] ?? '')) ?: 'User';
    $role = strtolower($user['role'] ?? $user['userType'] ?? 'student');
    $roleMap = [0=>'admin',1=>'manager',2=>'teacher',3=>'student',4=>'parent'];
    if (is_numeric($role)) $role = $roleMap[(int)$role] ?? 'student';
    $role = $role ?: 'student';

    $email = $user['email'] ?? '';
    $phone = $user['phone'] ?? '';
    $dob = $user['dateOfBirth'] ?? $user['dob'] ?? '';
    $gender = ucfirst($user['gender'] ?? '');
    $address = trim(implode("\n", array_filter([$user['address_line1']??'', $user['address_line2']??'', $user['address_line3']??''])));
    $grade = $user['grade'] ?? '';
    $class = $user['class'] ?? '';
    $subject = $user['subject'] ?? '';
    $studentIndex = $user['studentIndex'] ?? '';
    $nic = $user['nic'] ?? '';
    $relationship = ucfirst($user['relationship'] ?? '');

    $roleLabels = ['admin'=>'Administrator','manager'=>'Manager','teacher'=>'Teacher','student'=>'Student','parent'=>'Parent'];
    $roleLabel = $roleLabels[$role] ?? 'User';
    ?>

    <div class="profile-container">

        <!-- Avatar + Core Info -->
        <div class="profile-main">
            <div class="avatar-wrapper">
                <div class="avatar">
                    <svg viewBox="0 0 120 120" class="avatar-svg">
                        <circle cx="60" cy="60" r="58" fill="#f1f5f9" stroke="#e2e8f0" stroke-width="3"/>
                        <circle cx="60" cy="42" r="18" fill="#64748b"/>
                        <path d="M35 85 Q60 105 85 85 Q85 75 60 75 Q35 75 35 85" fill="#64748b"/>
                    </svg>
                </div>
                <?php if($role === 'student' || $role === 'teacher'): ?>
                    <div class="status-dot"></div>
                <?php endif; ?>
            </div>

            <div class="user-info">
                <h2 class="user-name"><?php echo htmlspecialchars($fullName); ?></h2>
                <span class="user-role"><?php echo htmlspecialchars($roleLabel); ?></span>

                <div class="contact-info">
                    <?php if($email): ?>
                        <div class="contact-line">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <a href="mailto:<?php echo htmlspecialchars($email); ?>"><?php echo htmlspecialchars($email); ?></a>
                        </div835
                    <?php endif; ?>
                    <?php if($phone): ?>
                        <div class="contact-line">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <a href="tel:<?php echo htmlspecialchars($phone); ?>"><?php echo htmlspecialchars($phone); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="details-grid">
            <?php if($dob): ?>
                <div class="detail-card">
                    <span class="detail-label">Date of Birth</span>
                    <span class="detail-value"><?php echo htmlspecialchars(date('d F Y', strtotime($dob))); ?></span>
                </div>
            <?php endif; ?>

            <?php if($gender): ?>
                <div class="detail-card">
                    <span class="detail-label">Gender</span>
                    <span class="detail-value"><?php echo htmlspecialchars($gender); ?></span>
                </div>
            <?php endif; ?>

            <?php if($address): ?>
                <div class="detail-card full">
                    <span class="detail-label">Address</span>
                    <span class="detail-value address"><?php echo nl2br(htmlspecialchars($address)); ?></span>
                </div>
            <?php endif; ?>

            <!-- Role-specific fields -->
            <?php if($role === 'student'): ?>
                <div class="detail-card">
                    <span class="detail-label">Class</span>
                    <span class="detail-value"><?php echo htmlspecialchars($grade . ' / ' . $class); ?></span>
                </div>
                <?php if($studentIndex): ?>
                    <div class="detail-card highlight">
                        <span class="detail-label">Index Number</span>
                        <span class="detail-value"><?php echo htmlspecialchars($studentIndex); ?></span>
                    </div>
                <?php endif; ?>

            <?php elseif($role === 'teacher'): ?>
                <div class="detail-card">
                    <span class="detail-label">Subject</span>
                    <span class="detail-value"><?php echo htmlspecialchars($subject ?: '—'); ?></span>
                </div>
                <div class="detail-card">
                    <span class="detail-label">Assigned Class</span>
                    <span class="detail-value"><?php echo htmlspecialchars($grade && $class ? "$grade / $class" : '—'); ?></span>
                </div>

            <?php elseif($role === 'parent'): ?>
                <?php if($relationship): ?>
                    <div class="detail-card">
                        <span class="detail-label">Relationship</span>
                        <span class="detail-value"><?php echo htmlspecialchars($relationship); ?></span>
                    </div>
                <?php endif; ?>
                <?php if($studentIndex): ?>
                    <div class="detail-card highlight">
                        <span class="detail-label">Child's Index</span>
                        <span class="detail-value"><?php echo htmlspecialchars($studentIndex); ?></span>
                    </div>
                <?php endif; ?>

            <?php elseif($nic): ?>
                <div class="detail-card">
                    <span class="detail-label">NIC</span>
                    <span class="detail-value"><?php echo htmlspecialchars($nic); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>