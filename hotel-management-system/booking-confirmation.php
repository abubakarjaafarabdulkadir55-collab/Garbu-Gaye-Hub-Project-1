<?php
require_once __DIR__ . '/config/config.php';
requireLogin();
$pageTitle = 'Booking confirmed';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT b.*, r.room_number, rt.name as type_name
                        FROM bookings b
                        JOIN rooms r ON r.id = b.room_id
                        JOIN room_types rt ON rt.id = r.room_type_id
                        WHERE b.id = ? AND b.user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$booking = $stmt->fetch();

if (!$booking) redirect(BASE_URL . 'dashboard/index.php');

require_once __DIR__ . '/includes/header.php';
?>

<section class="section">
  <div class="container text-center">
    <span class="eyebrow">Booking code <?= h($booking['booking_code']) ?></span>
    <h1>You're booked, <?= h($_SESSION['user_name']) ?>.</h1>
    <p class="muted">A confirmation has been added to your dashboard. The front desk will confirm your booking shortly.</p>
  </div>
  <div class="container" style="max-width:600px; margin-top:30px">
    <table class="data-table">
      <tbody>
        <tr><td>Room</td><td><?= h($booking['type_name']) ?> — Room <?= h($booking['room_number']) ?></td></tr>
        <tr><td>Check-in</td><td><?= h(niceDate($booking['check_in'])) ?></td></tr>
        <tr><td>Check-out</td><td><?= h(niceDate($booking['check_out'])) ?></td></tr>
        <tr><td>Nights</td><td><?= (int)$booking['nights'] ?></td></tr>
        <tr><td>Total</td><td><strong><?= money($booking['total_price']) ?></strong></td></tr>
        <tr><td>Status</td><td><span class="badge badge-<?= h($booking['status']) ?>"><?= h($booking['status']) ?></span></td></tr>
      </tbody>
    </table>
    <div class="text-center mt-30">
      <a href="dashboard/my-bookings.php" class="btn btn-primary">View my bookings</a>
      <a href="rooms.php" class="btn btn-outline" style="color:var(--forest); border-color:var(--line)">Book another room</a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
