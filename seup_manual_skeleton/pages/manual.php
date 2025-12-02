<?php if (!defined('APP_INIT')) { die('Direct access not allowed.'); } ?>

<h2>SEUP User Manual</h2>

<div class="search-box">
    <input type="text" id="manualSearch" placeholder="Pretrazite prirucnik...">
</div>

<?php
$sections = $db->fetchAll(
    'SELECT * FROM manual_sections WHERE is_active = 1 ORDER BY sort_order ASC, title ASC'
);
?>

<?php if (empty($sections)): ?>
    <div class="intro-section">
        <p>Trenutno nema dostupnih sekcija u prirucniku. Molimo pokusajte kasnije.</p>
    </div>
<?php else: ?>
    <?php foreach ($sections as $section): ?>
        <div class="manual-section">
            <h3>
                <?php echo htmlspecialchars($section['title']); ?>
                <span style="font-size: 0.8em; color: #64748b;">▼</span>
            </h3>
            <div class="content">
                <?php echo $section['content']; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
