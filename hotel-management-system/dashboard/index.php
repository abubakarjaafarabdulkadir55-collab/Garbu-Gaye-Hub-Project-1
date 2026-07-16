<?php
require_once __DIR__ . '/../config/config.php';
requireLogin();
$pageTitle = 'My account';
$userId = $_SESSION['user_id'];

$stats = $pdo->prepare("SELECT
    COUNT(*) as total,
    SUM(status IN ('confirmed','checked_in')) as active,
    SUM(status = 'checked_out') as past,
    SUM(status = 'cancelled') as cancelled
    FROM bookings WHERE user_id = ?");
$stats->execute([$userId]);
$stats = $stats->fetch();

$stmt = $pdo->prepare("SELECT b.*, r.room_number, rt.name as type_name
                        FROM bookings b
                        JOIN rooms r ON r.id = b.room_id
                        JOIN room_types rt ON rt.id = r.room_type_id
                        WHERE b.user_id = ?
                        ORDER BY b.created_at DESC LIMIT 5");
$stmt->execute([$userId]);
$recent = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="dash-shell">
  <?php require __DIR__ . '/../includes/dashboard-sidebar.php'; ?>
  <main class="dash-main">
    <h2>Welcome back, <?= h($_SESSION['user_name']) ?></h2>
    <p class="muted">Here's a quick look at your stays with us.</p>

    <div class="stat-grid mt-30">
      <div class="stat-card"><strong><?= (int)$stats['total'] ?></strong><span>Total bookings</span></div>
      <div class="stat-card"><strong><?= (int)$stats['active'] ?></strong><span>Upcoming / active</span></div>
      <div class="stat-card"><strong><?= (int)$stats['past'] ?></strong><span>Past stays</span></div>
      <div class="stat-card"><strong><?= (int)$stats['cancelled'] ?></strong><span>Cancelled</span></div>
    </div>

    <div class="card-head">
      <h3>Recent bookings</h3>
      <a href="my-bookings.php" class="btn btn-dark btn-sm">View all</a>
    </div>

    <?php if (empty($recent)): ?>
      <p class="muted">You haven't booked a room yet. <a href="<?= BASE_URL ?>rooms.php">Browse available rooms</a>.</p>
    <?php else: ?>
      <table class="data-table">
        <thead><tr><th>Code</th><th>Room</th><th>Check-in</th><th>Check-out</th><th>Total</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($recent as $b): ?>
          <tr>
            <td><?= h($b['booking_code']) ?></td>
            <td><?= h($b['type_name']) ?> #<?= h($b['room_number']) ?></td>
            <td><?= h(niceDate($b['check_in'])) ?></td>
            <td><?= h(niceDate($b['check_out'])) ?></td>
            <td><?= money($b['total_price']) ?></td>
            <td><span class="badge badge-<?= h($b['status']) ?>"><?= h($b['status']) ?></span></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
