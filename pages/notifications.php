<?php if (!defined('APP_INIT')) { die('Direct access not allowed.'); } ?>

<h2>Obavijesti</h2>

<div class="filter-tabs">
    <button class="active" data-filter="all">Sve</button>
    <button data-filter="info">Info</button>
    <button data-filter="success">Uspjeh</button>
    <button data-filter="warning">Upozorenje</button>
    <button data-filter="error">Greska</button>
</div>

<?php
$notifications = $db->fetchAll(
    'SELECT * FROM notifications
     WHERE is_active = 1
     AND (expires_at IS NULL OR expires_at > NOW())
     ORDER BY created_at DESC'
);
?>

<?php if (empty($notifications)): ?>
    <div class="intro-section">
        <p>Trenutno nema aktivnih obavijesti.</p>
    </div>
<?php else: ?>
    <?php foreach ($notifications as $notification): ?>
        <div class="notification <?php echo htmlspecialchars($notification['type']); ?>">
            <h3><?php echo htmlspecialchars($notification['title']); ?></h3>
            <p><?php echo htmlspecialchars($notification['message']); ?></p>
            <span class="date">
                <?php echo date('d.m.Y H:i', strtotime($notification['created_at'])); ?>
                <?php if ($notification['expires_at']): ?>
                    - Istice: <?php echo date('d.m.Y H:i', strtotime($notification['expires_at'])); ?>
                <?php endif; ?>
            </span>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
