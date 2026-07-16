<?php
require_once __DIR__ . '/../config/config.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int)($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? '';
    $valid  = ['pending','confirmed','checked_in','checked_out','cancelled'];

    if (in_array($status, $valid, true)) {
        $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?")->execute([$status, $id]);

        if ($status === 'confirmed') {
            $pdo->prepare("UPDATE payments SET status='paid', paid_at=NOW() WHERE booking_id=? AND status='pending'")->execute([$id]);
        }
        if ($status === 'cancelled') {
            $pdo->prepare("UPDATE payments SET status='refunded' WHERE booking_id=? AND status='paid'")->execute([$id]);
        }
        setFlash('success', 'Booking status updated.');
    } else {
        setFlash('error', 'Invalid status.');
    }
}

redirect(BASE_URL . 'admin/bookings.php');
