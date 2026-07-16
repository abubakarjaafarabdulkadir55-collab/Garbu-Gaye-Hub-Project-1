<?php
require_once __DIR__ . '/../config/config.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingId = (int)($_POST['booking_id'] ?? 0);

    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->execute([$bookingId, $_SESSION['user_id']]);
    $booking = $stmt->fetch();

    if ($booking && in_array($booking['status'], ['pending', 'confirmed'])) {
        $pdo->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ?")->execute([$bookingId]);
        setFlash('success', 'Booking ' . $booking['booking_code'] . ' has been cancelled.');
    } else {
        setFlash('error', 'That booking could not be cancelled.');
    }
}

redirect(BASE_URL . 'dashboard/my-bookings.php');
