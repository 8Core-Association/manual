<?php
define('APP_INIT', true);
require __DIR__ . '/config/config.php';
require __DIR__ . '/class/Database.php';
$db = new Database($pdo);
$page = $_GET['page'] ?? 'home';
$allowedPages = [
    'home'          => 'home.php',
    'manual'        => 'manual.php',
    'notifications' => 'notifications.php',
];
$file = $allowedPages[$page] ?? $allowedPages['home'];
require __DIR__ . '/templates/header.php';
require __DIR__ . '/pages/' . $file;
require __DIR__ . '/templates/footer.php';
