<section class="student-timetable">
    <?php if (!empty($tt_error)): ?>
        <div class="empty-state" style="padding: 1rem;">
            <p><?php echo htmlspecialchars((string) $tt_error); ?></p>
        </div>
    <?php endif; ?>
    <?php include_once __DIR__ . '/../templates/studentTimeTable.php'; ?>
</section>