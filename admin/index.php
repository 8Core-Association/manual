<?php
define('APP_INIT', true);
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../class/Database.php';
$db = new Database($pdo);

$action = $_GET['action'] ?? 'dashboard';
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_manual'])) {
        $title = $_POST['title'] ?? '';
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($_POST['title'])));
        $content = $_POST['content'] ?? '';
        $sort_order = (int)($_POST['sort_order'] ?? 0);

        if ($title && $content) {
            $db->query(
                'INSERT INTO manual_sections (title, slug, content, sort_order) VALUES (?, ?, ?, ?)',
                [$title, $slug, $content, $sort_order]
            );
            header('Location: /admin/?action=manual&success=added');
            exit;
        }
    }

    if (isset($_POST['add_notification'])) {
        $title = $_POST['title'] ?? '';
        $message = $_POST['message'] ?? '';
        $type = $_POST['type'] ?? 'info';
        $expires_at = $_POST['expires_at'] ?? null;

        if ($title && $message) {
            $db->query(
                'INSERT INTO notifications (title, message, type, expires_at) VALUES (?, ?, ?, ?)',
                [$title, $message, $type, $expires_at ?: null]
            );
            header('Location: /admin/?action=notifications&success=added');
            exit;
        }
    }

    if (isset($_POST['toggle_manual'])) {
        $id = (int)$_POST['id'];
        $db->query('UPDATE manual_sections SET is_active = NOT is_active WHERE id = ?', [$id]);
        header('Location: /admin/?action=manual&success=updated');
        exit;
    }

    if (isset($_POST['toggle_notification'])) {
        $id = (int)$_POST['id'];
        $db->query('UPDATE notifications SET is_active = NOT is_active WHERE id = ?', [$id]);
        header('Location: /admin/?action=notifications&success=updated');
        exit;
    }

    if (isset($_POST['delete_manual'])) {
        $id = (int)$_POST['id'];
        $db->query('DELETE FROM manual_sections WHERE id = ?', [$id]);
        header('Location: /admin/?action=manual&success=deleted');
        exit;
    }

    if (isset($_POST['delete_notification'])) {
        $id = (int)$_POST['id'];
        $db->query('DELETE FROM notifications WHERE id = ?', [$id]);
        header('Location: /admin/?action=notifications&success=deleted');
        exit;
    }
}

$manuals = $db->fetchAll('SELECT * FROM manual_sections ORDER BY sort_order ASC, title ASC');
$notifications = $db->fetchAll('SELECT * FROM notifications ORDER BY created_at DESC');
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <title>Admin - SEUP Manual</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
    <script src="<?php echo BASE_URL; ?>/js/main.js" defer></script>
</head>
<body>
<header>
    <h1>Admin Panel - SEUP User Manual</h1>
    <nav>
        <a href="<?php echo BASE_URL; ?>/">Pocetna</a>
        <a href="<?php echo BASE_URL; ?>/admin/">Dashboard</a>
        <a href="<?php echo BASE_URL; ?>/admin/?action=manual">Prirucnik</a>
        <a href="<?php echo BASE_URL; ?>/admin/?action=notifications">Obavijesti</a>
    </nav>
</header>
<main>

<?php if ($success): ?>
    <div class="notification success" style="margin-bottom: 2rem;">
        <p>
            <?php
            if ($success === 'added') echo 'Uspjesno dodano!';
            elseif ($success === 'updated') echo 'Uspjesno azurirano!';
            elseif ($success === 'deleted') echo 'Uspjesno obrisano!';
            ?>
        </p>
    </div>
<?php endif; ?>

<?php if ($action === 'dashboard'): ?>
    <div class="admin-container">
        <h2>Dashboard</h2>
        <div class="stats">
            <div class="stat-card">
                <span class="number"><?php echo count($manuals); ?></span>
                <span class="label">Ukupno sekcija</span>
            </div>
            <div class="stat-card">
                <span class="number"><?php echo count(array_filter($manuals, fn($m) => $m['is_active'])); ?></span>
                <span class="label">Aktivnih sekcija</span>
            </div>
            <div class="stat-card">
                <span class="number"><?php echo count($notifications); ?></span>
                <span class="label">Ukupno obavijesti</span>
            </div>
            <div class="stat-card">
                <span class="number"><?php echo count(array_filter($notifications, fn($n) => $n['is_active'])); ?></span>
                <span class="label">Aktivnih obavijesti</span>
            </div>
        </div>
        <div class="intro-section">
            <p>Dobrodosli u admin panel. Koristite navigaciju za upravljanje prirucnikom i obavijestima.</p>
        </div>
    </div>
<?php endif; ?>

<?php if ($action === 'manual'): ?>
    <div class="admin-container">
        <h2>Upravljanje prirucnikom</h2>

        <div class="admin-section">
            <h3>Dodaj novu sekciju</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Naslov:</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Sadrzaj (HTML):</label>
                    <textarea name="content" required></textarea>
                </div>
                <div class="form-group">
                    <label>Redoslijed:</label>
                    <input type="number" name="sort_order" value="0">
                </div>
                <button type="submit" name="add_manual" class="btn btn-primary">Dodaj sekciju</button>
            </form>
        </div>

        <div class="admin-section">
            <h3>Postojece sekcije</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Naslov</th>
                        <th>Slug</th>
                        <th>Redoslijed</th>
                        <th>Aktivan</th>
                        <th>Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($manuals as $manual): ?>
                        <tr>
                            <td><?php echo $manual['id']; ?></td>
                            <td><?php echo htmlspecialchars($manual['title']); ?></td>
                            <td><?php echo htmlspecialchars($manual['slug']); ?></td>
                            <td><?php echo $manual['sort_order']; ?></td>
                            <td><?php echo $manual['is_active'] ? 'Da' : 'Ne'; ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $manual['id']; ?>">
                                    <button type="submit" name="toggle_manual" class="btn btn-small btn-warning">Toggle</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $manual['id']; ?>">
                                    <button type="submit" name="delete_manual" class="btn btn-small btn-error btn-delete">Obrisi</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php if ($action === 'notifications'): ?>
    <div class="admin-container">
        <h2>Upravljanje obavijestima</h2>

        <div class="admin-section">
            <h3>Dodaj novu obavijest</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Naslov:</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Poruka:</label>
                    <textarea name="message" required></textarea>
                </div>
                <div class="form-group">
                    <label>Tip:</label>
                    <select name="type">
                        <option value="info">Info</option>
                        <option value="success">Uspjeh</option>
                        <option value="warning">Upozorenje</option>
                        <option value="error">Greska</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Istice (opcionalno):</label>
                    <input type="datetime-local" name="expires_at">
                </div>
                <button type="submit" name="add_notification" class="btn btn-primary">Dodaj obavijest</button>
            </form>
        </div>

        <div class="admin-section">
            <h3>Postojece obavijesti</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Naslov</th>
                        <th>Tip</th>
                        <th>Kreirano</th>
                        <th>Istice</th>
                        <th>Aktivan</th>
                        <th>Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notifications as $notification): ?>
                        <tr>
                            <td><?php echo $notification['id']; ?></td>
                            <td><?php echo htmlspecialchars($notification['title']); ?></td>
                            <td><?php echo htmlspecialchars($notification['type']); ?></td>
                            <td><?php echo date('d.m.Y H:i', strtotime($notification['created_at'])); ?></td>
                            <td><?php echo $notification['expires_at'] ? date('d.m.Y H:i', strtotime($notification['expires_at'])) : '-'; ?></td>
                            <td><?php echo $notification['is_active'] ? 'Da' : 'Ne'; ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $notification['id']; ?>">
                                    <button type="submit" name="toggle_notification" class="btn btn-small btn-warning">Toggle</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $notification['id']; ?>">
                                    <button type="submit" name="delete_notification" class="btn btn-small btn-error btn-delete">Obrisi</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

</main>
<footer>
    <p>&copy; <?php echo date('Y'); ?> 8Core / SEUP - Admin Panel</p>
</footer>
</body>
</html>
