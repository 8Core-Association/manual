<?php if (!defined('APP_INIT')) { die('Direct access not allowed.'); } ?>

<h2>Dobrodosli u SEUP User Manual</h2>

<div class="intro-section">
    <p>
        SEUP (Sistem za Elektronsku Upravnu Podrsku) je moderni sustav za upravljanje administrativnim
        procesima. Ovaj portal sadrzi detaljnu dokumentaciju, korisnicke prirucnike i vazne obavijesti.
    </p>
    <p>
        Koristite navigaciju iznad za pristup razlicitim sekcijama. U User Manual sekciji pronacit cete
        detaljna uputstva za sve funkcionalnosti sustava, dok u sekciji Obavijesti mozete pregledati
        najnovije vijesti i upozorenja.
    </p>
</div>

<?php
$manualCount = $db->fetch('SELECT COUNT(*) as count FROM manual_sections WHERE is_active = 1')['count'] ?? 0;
$notificationCount = $db->fetch('SELECT COUNT(*) as count FROM notifications WHERE is_active = 1')['count'] ?? 0;
$recentNotifications = $db->fetchAll(
    'SELECT * FROM notifications WHERE is_active = 1 ORDER BY created_at DESC LIMIT 3'
);
?>

<div class="stats">
    <div class="stat-card">
        <span class="number"><?php echo $manualCount; ?></span>
        <span class="label">Sekcija u prirucniku</span>
    </div>
    <div class="stat-card">
        <span class="number"><?php echo $notificationCount; ?></span>
        <span class="label">Aktivnih obavijesti</span>
    </div>
    <div class="stat-card">
        <span class="number">24/7</span>
        <span class="label">Dostupnost sustava</span>
    </div>
</div>

<?php if (!empty($recentNotifications)): ?>
<h3>Najnovije obavijesti</h3>
<?php foreach ($recentNotifications as $notification): ?>
    <div class="notification <?php echo htmlspecialchars($notification['type']); ?>">
        <h3><?php echo htmlspecialchars($notification['title']); ?></h3>
        <p><?php echo htmlspecialchars($notification['message']); ?></p>
        <span class="date"><?php echo date('d.m.Y H:i', strtotime($notification['created_at'])); ?></span>
    </div>
<?php endforeach; ?>
<?php endif; ?>
