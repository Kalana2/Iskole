<link rel="stylesheet" href="/css/announcements/announcements.css">
<?php include __DIR__ . '/../../Controllers/announcement/readAnnouncementController.php'; ?>
<?php include __DIR__ . '/../../Controllers/announcement/deleteAnnouncementController.php'; ?>
<?php include __DIR__ . '/../../Controllers/announcement/updateAnnouncementController.php'; ?>

<section class="mp-announcements theme-light" aria-labelledby="ann-title">
    <div class="ann-header">
        <div class="ann-title-wrap">
            <h2 id="ann-title">Announcements</h2>
            <p class="ann-subtitle">Latest updates and notices</p>
        </div>
        <div class="ann-actions">
            <div class="chip-group" role="tablist" aria-label="Announcement filters">
                <?php if ($_SESSION['user_role'] < 3): ?>
                    <button class="chip active" role="tab" aria-selected="true" data-filter="all">All</button>

                    <button class="chip" role="tab" aria-selected="false" data-filter="my">Published</button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="ann-grid" role="list">
        <?php
        $list = $announcements ?? [];
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
                            data-audience="<?php echo htmlspecialchars($a['audience'] ?? '', ENT_QUOTES); ?>">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M11.333 2.00004C11.5081 1.82494 11.716 1.68605 11.9447 1.59129C12.1735 1.49653 12.4187 1.44775 12.6663 1.44775C12.914 1.44775 13.1592 1.49653 13.3879 1.59129C13.6167 1.68605 13.8246 1.82494 13.9997 2.00004C14.1748 2.17513 14.3137 2.383 14.4084 2.61178C14.5032 2.84055 14.552 3.08575 14.552 3.33337C14.552 3.58099 14.5032 3.82619 14.4084 4.05497C14.3137 4.28374 14.1748 4.49161 13.9997 4.66671L5.33301 13.3334L1.99967 14L2.66634 10.6667L11.333 2.00004Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Edit
                        </button>
                        <button class="btn-delete" type="button"
                            data-id="<?php echo htmlspecialchars($a['id'] ?? $i); ?>">
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

            <div class="field span-2">
                <label for="targetAudience">Target Audience</label>
                <?php if ($_SESSION['userRole'] == 2): ?>
                    <label>
                        <input type="checkbox" name="roles[]" value="parent">
                        Parent
                    </label>

                    <label>
                        <input type="checkbox" name="roles[]" value="student">
                        Student
                    </label>
                <?php else: ?>

                    <label>
                        <input type="checkbox" name="roles[]" value="admin">
                        Admin
                    </label>

                    <label>
                        <input type="checkbox" name="roles[]" value="mp">
                        Management Panel (MP)
                    </label>

                    <label>
                        <input type="checkbox" name="roles[]" value="teacher">
                        Teacher
                    </label>

                    <label>
                        <input type="checkbox" name="roles[]" value="parent">
                        Parent
                    </label>

                    <label>
                        <input type="checkbox" name="roles[]" value="student">
                        Student
                    </label>
                <?php endif; ?>
                <small class="hint">Choose who should receive this announcement.</small>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" id="cancelBtn">Cancel</button>
                <button type="submit" class="btn-save">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    // filter announcements
    (function() {
        const container = document.querySelector('.mp-announcements');
        if (!container) return;
        const grid = container.querySelector('.ann-grid');
        const chips = container.querySelectorAll('.chip-group .chip');

        const applyFilter = (key) => {
            const cards = grid.querySelectorAll('.ann-card');
            cards.forEach(card => {
                const isMyAnnouncement = card.classList.contains('is-my-announcement');
                const show = key === 'all' || (key === 'my' && isMyAnnouncement);
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

        // Ensure modal is hidden on page load
        modal.style.display = 'none';

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

                // Reset all checkboxes first
                const checkboxes = form.querySelectorAll('input[name="roles[]"]');
                checkboxes.forEach(cb => cb.checked = false);

                // Populate audience checkboxes based on current audience
                if (audience && audience !== 'all') {
                    const audiences = audience.split(',');
                    audiences.forEach(aud => {
                        const checkbox = form.querySelector(`input[name="roles[]"][value="${aud.trim()}"]`);
                        if (checkbox) {
                            checkbox.checked = true;
                        }
                    });
                } else if (audience === 'all') {
                    checkboxes.forEach(cb => cb.checked = true);
                }

                modal.style.display = 'flex';
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
                audience: formData.getAll('roles[]')
            };

            // Validate data before sending
            if (!data.announcement_id || !data.title || !data.body) {
                alert('Please fill in all required fields.');
                return;
            }

            // Check if at least one audience is selected
            if (!data.audience || data.audience.length === 0) {
                alert('Please select at least one target audience.');
                return;
            }

            // Disable submit button during request
            const submitBtn = form.querySelector('.btn-save');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';

            console.log('Sending data:', data); // Debug log

            // Send update request to server
            fetch(window.location.pathname + '?action=update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    console.log('Response status:', response.status); // Debug log
                    console.log('Response headers:', response.headers.get('content-type')); // Debug log

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    return response.text(); // Get as text first to debug
                })
                .then(text => {
                    console.log('Raw response:', text); // Debug log

                    try {
                        const result = JSON.parse(text);
                        if (result.success) {
                            alert('Announcement updated successfully!');
                            closeModal();
                            location.reload();
                        } else {
                            alert('Error updating announcement: ' + (result.error || result.message || 'Unknown error'));
                            submitBtn.disabled = false;
                            submitBtn.textContent = 'Save Changes';
                        }
                    } catch (parseError) {
                        console.error('JSON parse error:', parseError);
                        console.error('Response text that failed to parse:', text);
                        alert('Server returned invalid response. Please check the console for details.');
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Save Changes';
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('Failed to update announcement. Please try again. Check console for details.');
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

        // Close modal function
        function closeModal() {
            modal.style.display = 'none';
            form.reset();
        }

        // Close modal event listeners
        [closeBtn, cancelBtn].forEach(btn => btn.addEventListener('click', closeModal));

        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

    })();

    // Delete Announcement
    (function() {
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-delete')) {
                const btn = e.target.closest('.btn-delete');
                const id = btn.getAttribute('data-id');

                if (!id) {
                    alert('Error: No announcement ID found');
                    return;
                }

                if (confirm('Are you sure you want to delete this announcement?')) {
                    // Disable the delete button during the request
                    btn.disabled = true;
                    btn.style.opacity = '0.5';

                    fetch(window.location.pathname + '?action=delete', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                announcement_id: id
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(result => {
                            if (result.success) {
                                alert('Announcement deleted successfully!');
                                // Reload the page to reflect changes
                                location.reload();
                            } else {
                                alert('Error deleting announcement: ' + (result.error || result.message || 'Unknown error'));
                                // Re-enable the button if there's an error
                                btn.disabled = false;
                                btn.style.opacity = '1';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Failed to delete announcement. Please try again.');
                            // Re-enable the button if there's an error
                            btn.disabled = false;
                            btn.style.opacity = '1';
                        });
                }
            }
        });
    })();
</script>