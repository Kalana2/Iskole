<?php

// Reusable navigation bar partial
// Usage:
//   $items = ['Announcements', 'Academic', 'Requests']; // or $navItems
//   $active = 'Announcements'; // or index: 0
//   include __DIR__ . '/navigation.php';

// Support both $items and legacy $navItems variable names
$items = $items ?? ($navItems ?? null);
if (!$items || !is_array($items)) {
    $items = ['Announcements', 'Academic', 'Requests', 'Management', 'Report'];
}

$active = $active ?? null; // string label or integer index
?>
<nav class="mp-nav" role="navigation">
    <div class="mp-nav-inner">
        <ul class="nav-links" role="menubar">
            <?php foreach ($items as $index => $label): ?>
                <?php
                $isActive = false;
                if (is_string($active)) {
                    $isActive = ($active === $label);
                } elseif (is_int($active)) {
                    $isActive = ($active === $index);
                }
                if ($label === 'Leave') {
                    $href = '/index.php?url=leave';
                } else {
                    $href = '/index.php?url=teacher&tab=' . urlencode($label);
                }
                ?>
                <li role="none">
                    <a role="menuitem" href="<?php echo $href; ?>" class="<?php echo $isActive ? 'active' : ''; ?>" <?php echo $isActive ? 'aria-current="page"' : ''; ?>>
                        <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>