<link rel="stylesheet" href="/css/announcements/announcements.css">
<?php include __DIR__ . '/../../Controllers/announcement/readAnnouncementController.php'; ?>
<section class="mp-announcements theme-light" aria-labelledby="ann-title">
    <div class="ann-header">
        <div class="ann-title-wrap">
            <h2 id="ann-title">Announcements</h2>
            <p class="ann-subtitle">Latest updates and notices</p>
        </div>
        <div class="ann-actions">
            <div class="chip-group" role="tablist" aria-label="Announcement filters">
                <button class="chip active" role="tab" aria-selected="true" data-filter="all">All Announcements</button>
                <button class="chip" role="tab" aria-selected="false" data-filter="my">My Announcements</button>
            </div>
        </div>
    </div>

    <div class="ann-grid" role="list">
        <?php
        // Use announcements from controller, fallback to empty array if not set
        $list = isset($announcements) && is_array($announcements) ? $announcements : [];
        $currentUserId = $_SESSION['user_id'] ?? null;

        if (empty($list)) {
            echo '<div class="no-announcements-wrapper"><p class="no-announcements">No announcements available at this time.</p></div>';
        }
        ?>

        <?php foreach ($list as $i => $a): ?>
            <?php
            $classes = ['ann-card'];
            $isMyAnnouncement = isset($a['author_id']) && isset($currentUserId) && $a['author_id'] == $currentUserId;
            if ($isMyAnnouncement) {
                $classes[] = 'is-my-announcement';
            }
            ?>
            <article role="listitem" class="<?php echo implode(' ', $classes); ?>" tabindex="0"
                data-author-id="<?php echo htmlspecialchars($a['author_id'] ?? ''); ?>"
                aria-label="Announcement: <?php echo htmlspecialchars($a['title'] ?? ''); ?>">
                <div class="ann-card-header">
                    <time class="ann-date"
                        datetime="<?php echo htmlspecialchars($a['date'] ?? ''); ?>"><?php echo htmlspecialchars($a['date'] ?? ''); ?></time>
                </div>

                <h3 class="ann-title-text"><?php echo htmlspecialchars($a['title'] ?? ''); ?></h3>
                <p class="ann-body"><?php echo htmlspecialchars($a['body'] ?? ''); ?></p>

                <div class="ann-meta">
                    <span class="author">By <?php echo htmlspecialchars($a['author'] ?? ''); ?></span>
                </div>

                <?php if ($isMyAnnouncement): ?>
                    <div class="ann-actions-row">
                        <button class="btn-edit" type="button"
                            data-id="<?php echo htmlspecialchars($a['id'] ?? $i); ?>"
                            data-title="<?php echo htmlspecialchars($a['title'] ?? '', ENT_QUOTES); ?>"
                            data-body="<?php echo htmlspecialchars($a['body'] ?? '', ENT_QUOTES); ?>"
                            data-audience="<?php echo htmlspecialchars($a['audience'] ?? 'all', ENT_QUOTES); ?>">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M11.333 2.00004C11.5081 1.82494 11.716 1.68605 11.9447 1.59129C12.1735 1.49653 12.4187 1.44775 12.6663 1.44775C12.914 1.44775 13.1592 1.49653 13.3879 1.59129C13.6167 1.68605 13.8246 1.82494 13.9997 2.00004C14.1748 2.17513 14.3137 2.383 14.4084 2.61178C14.5032 2.84055 14.552 3.08575 14.552 3.33337C14.552 3.58099 14.5032 3.82619 14.4084 4.05497C14.3137 4.28374 14.1748 4.49161 13.9997 4.66671L5.33301 13.3334L1.99967 14L2.66634 10.6667L11.333 2.00004Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Edit
                        </button>
                        <button class="btn-delete" type="button"
                            data-id="<?php echo htmlspecialchars($a['id'] ?? $i); ?>"
                            data-title="<?php echo htmlspecialchars($a['title'] ?? '', ENT_QUOTES); ?>"
                            data-body="<?php echo htmlspecialchars($a['body'] ?? '', ENT_QUOTES); ?>"
                            data-audience="<?php echo htmlspecialchars($a['audience'] ?? 'all', ENT_QUOTES); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-icon lucide-trash">
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                <path d="M3 6h18" />
                                <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                            </svg>
                            Delete
                        </button>

                    </div>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<!-- Edit Announcement Modal -->
<div id="editAnnouncementModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Edit Announcement</h2>
            <button type="button" class="modal-close" id="closeModalBtn">&times;</button>
        </div>
        <form id="editAnnouncementForm">
            <input type="hidden" id="edit_announcement_id" name="announcement_id">

            <div class="form-group">
                <label for="edit_title">Title <span class="required">*</span></label>
                <input type="text" id="edit_title" name="title" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="edit_body">Message <span class="required">*</span></label>
                <textarea id="edit_body" name="body" class="form-control" rows="6" required></textarea>
            </div>

            <div class="form-group">
                <label for="edit_audience">Select Audience <span class="required">*</span></label>
                <select id="edit_audience" name="audience" class="form-control" required>
                    <option value="all">All Users</option>
                    <option value="teachers">Teachers</option>
                    <option value="students">Students</option>
                    <option value="parents">Parents</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" id="cancelBtn">Cancel</button>
                <button type="submit" class="btn-save">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function() {
        const container = document.querySelector('.mp-announcements');
        if (!container) return;
        const grid = container.querySelector('.ann-grid');
        const chips = container.querySelectorAll('.chip-group .chip');

        const applyFilter = (key) => {
            const cards = grid.querySelectorAll('.ann-card');
            cards.forEach(card => {
                const isMyAnnouncement = card.classList.contains('is-my-announcement');
                let show = true;
                switch (key) {
                    case 'all':
                        show = true;
                        break;
                    case 'my':
                        show = isMyAnnouncement;
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

    // Edit Announcement Modal Functions
    (function() {
        const modal = document.getElementById('editAnnouncementModal');
        const form = document.getElementById('editAnnouncementForm');
        const closeBtn = document.getElementById('closeModalBtn');
        const cancelBtn = document.getElementById('cancelBtn');

        // Open modal when edit button is clicked
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-edit')) {
                const btn = e.target.closest('.btn-edit');
                const id = btn.getAttribute('data-id');
                const title = btn.getAttribute('data-title');
                const body = btn.getAttribute('data-body');
                const audience = btn.getAttribute('data-audience');

                document.getElementById('edit_announcement_id').value = id;
                document.getElementById('edit_title').value = title;
                document.getElementById('edit_body').value = body;
                document.getElementById('edit_audience').value = audience || 'all';

                modal.style.display = 'flex';
            }
        });

        // Close modal function
        function closeModal() {
            modal.style.display = 'none';
            form.reset();
        }

        // Close modal on close button click
        closeBtn.addEventListener('click', closeModal);

        // Close modal on cancel button click
        cancelBtn.addEventListener('click', closeModal);

        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Handle form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            const data = {
                announcement_id: formData.get('announcement_id'),
                title: formData.get('title'),
                body: formData.get('body'),
                audience: formData.get('audience')
            };

            // Disable submit button during request
            const submitBtn = form.querySelector('.btn-save');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';

            // Send update request to server
            fetch(window.location.pathname + '?action=update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Announcement updated successfully!');
                        closeModal();
                        // Reload the page to show updated data
                        location.reload();
                    } else {
                        alert('Error updating announcement: ' + (result.message || 'Unknown error'));
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Save Changes';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update announcement. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Save Changes';
                });
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.style.display === 'flex') {
                closeModal();
            }
        });
    })();
</script>