<?php
require_once __DIR__ . '/../config/config.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $pdo->prepare("DELETE FROM rooms WHERE id = ?")->execute([$id]);
    setFlash('success', 'Room deleted.');
}

redirect(BASE_URL . 'admin/rooms.php');
