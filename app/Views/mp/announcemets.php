<?php
// filepath: /home/snake/Projects/Iskole/app/Views/mp/announcemets.php
?>
<link rel="stylesheet" href="/css/mp/announcements.css">

<section class="mp-announcements theme-light" aria-labelledby="ann-title">
    <div class="ann-header">
        <div class="ann-title-wrap">
            <h2 id="ann-title">Announcements</h2>
            <p class="ann-subtitle">Latest updates and notices</p>
        </div>
        <div class="ann-actions">
            <div class="chip-group" role="tablist" aria-label="Announcement filters">
                <button class="chip active" role="tab" aria-selected="true" data-filter="all">All</button>
                <button class="chip" role="tab" aria-selected="false" data-filter="unread">Unread</button>
                <button class="chip" role="tab" aria-selected="false" data-filter="pinned">Pinned</button>
                <button class="chip" role="tab" aria-selected="false" data-filter="general">General</button>
                <button class="chip" role="tab" aria-selected="false" data-filter="academic">Academic</button>
            </div>
        </div>
    </div>

    <div class="ann-grid" role="list">
        <?php
        // Expecting: $announcements = [ [ 'title' => '', 'body' => '', 'author' => '', 'date' => '', 'tags' => ['general'], 'pinned' => true, 'unread' => false ], ... ]
        $now = date('Y-m-d');
        $sample = [
            [
                'title' => 'System Maintenance Window',
                'body' => 'Scheduled maintenance will occur on Friday from 10:00 PM to 12:00 AM. During this period, access to certain features may be limited. We appreciate your understanding.',
                'author' => 'IT Department',
                'date' => $now,
                'tags' => ['general'],
                'pinned' => true,
                'unread' => false,
            ],
            [
                'title' => 'Midterm Exam Schedule Released',
                'body' => 'The midterm exam timetable is now available. Please review the schedule carefully and contact your subject teacher for any discrepancies.',
                'author' => 'Examination Unit',
                'date' => $now,
                'tags' => ['academic'],
                'pinned' => false,
                'unread' => true,
            ],
            [
                'title' => 'Staff Meeting Reminder',
                'body' => 'A reminder that the monthly staff meeting will be held on Wednesday at 2:00 PM in the conference hall.',
                'author' => 'Principal Office',
                'date' => $now,
                'tags' => ['general'],
                'pinned' => false,
                'unread' => false,
            ],
        ];
        $list = isset($announcements) && is_array($announcements) ? $announcements : $sample;
        ?>

        <?php foreach ($list as $i => $a): ?>
            <?php
            $tags = $a['tags'] ?? [];
            $classes = ['ann-card'];
            foreach ($tags as $t) {
                $classes[] = 'tag-' . preg_replace('/[^a-z0-9\-]/', '', strtolower($t));
            }
            if (!empty($a['pinned'])) {
                $classes[] = 'is-pinned';
            }
            if (!empty($a['unread'])) {
                $classes[] = 'is-unread';
            }
            ?>
            <article role="listitem" class="<?php echo implode(' ', $classes); ?>" tabindex="0"
                aria-label="Announcement: <?php echo htmlspecialchars($a['title'] ?? ''); ?>">
                <div class="ann-card-header">
                    <div class="ann-badges">
                        <?php if (!empty($a['pinned'])): ?><span class="badge badge-pin"
                                aria-label="Pinned">Pinned</span><?php endif; ?>
                        <?php foreach ($tags as $t): ?><span
                                class="badge"><?php echo htmlspecialchars(ucfirst($t)); ?></span><?php endforeach; ?>
                    </div>
                    <time class="ann-date"
                        datetime="<?php echo htmlspecialchars($a['date'] ?? ''); ?>"><?php echo htmlspecialchars($a['date'] ?? ''); ?></time>
                </div>

                <h3 class="ann-title-text"><?php echo htmlspecialchars($a['title'] ?? ''); ?></h3>
                <p class="ann-body"><?php echo htmlspecialchars($a['body'] ?? ''); ?></p>

                <div class="ann-meta">
                    <span class="author">By <?php echo htmlspecialchars($a['author'] ?? ''); ?></span>
                    <?php if (!empty($a['unread'])): ?><span class="dot unread" title="Unread"></span><?php endif; ?>
                </div>

                <div class="ann-actions-row">
                    <button class="btn ghost" type="button">Details</button>
                    <div class="spacer"></div>
                    <button class="btn" type="button">Mark as Read</button>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<script>
    (function () {
        const container = document.currentScript.previousElementSibling; // section.mp-announcements
        if (!container) return;
        const grid = container.querySelector('.ann-grid');
        const chips = container.querySelectorAll('.chip-group .chip');

        const applyFilter = (key) => {
            const cards = grid.querySelectorAll('.ann-card');
            cards.forEach(card => {
                const isPinned = card.classList.contains('is-pinned');
                const isUnread = card.classList.contains('is-unread');
                const matchesTag = key && key !== 'all' ? card.classList.contains('tag-' + key) : true;
                let show = true;
                switch (key) {
                    case 'all': show = true; break;
                    case 'pinned': show = isPinned; break;
                    case 'unread': show = isUnread; break;
                    default: show = matchesTag; break;
                }
                card.style.display = show ? '' : 'none';
            });
        };

        chips.forEach(chip => {
            chip.addEventListener('click', () => {
                chips.forEach(c => { c.classList.remove('active'); c.setAttribute('aria-selected', 'false'); });
                chip.classList.add('active');
                chip.setAttribute('aria-selected', 'true');
                applyFilter(chip.dataset.filter);
            });
        });
    })();
</script>