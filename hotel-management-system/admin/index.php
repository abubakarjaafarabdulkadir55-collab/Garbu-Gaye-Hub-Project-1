<?php
require_once __DIR__ . '/../config/config.php';
requireAdmin();
$pageTitle = 'Admin dashboard';

$totalRooms   = $pdo->query("SELECT COUNT(*) FROM rooms")->fetchColumn();
$totalGuests  = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$activeStays  = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status IN ('confirmed','checked_in')")->fetchColumn();
$pendingCount = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetchColumn();
$revenue      = $pdo->query("SELECT COALESCE(SUM(total_price),0) FROM bookings WHERE status != 'cancelled'")->fetchColumn();
$unreadMsgs   = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0")->fetchColumn();

$stmt = $pdo->query("SELECT b.*, u.full_name, r.room_number, rt.name as type_name
                      FROM bookings b
                      JOIN users u ON u.id = b.user_id
                      JOIN rooms r ON r.id = b.room_id
                      JOIN room_types rt ON rt.id = r.room_type_id
                      ORDER BY b.created_at DESC LIMIT 8");
$recentBookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($pageTitle) ?> · Aurelia Admin</title>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
  <?php require __DIR__ . '/../includes/admin-sidebar.php'; ?>
  <main class="admin-main">
    <div class="admin-topbar">
      <h1>Dashboard</h1>
      <span class="who">Signed in as <?= h($_SESSION['admin_name']) ?></span>
    </div>

    <div class="kpi-grid">
      <div class="kpi"><strong><?= money($revenue) ?></strong><span>Total revenue</span></div>
      <div class="kpi"><strong><?= (int)$activeStays ?></strong><span>Active / upcoming stays</span></div>
      <div class="kpi"><strong><?= (int)$pendingCount ?></strong><span>Pending approval</span></div>
      <div class="kpi"><strong><?= (int)$totalRooms ?></strong><span>Total rooms</span></div>
      <div class="kpi"><strong><?= (int)$totalGuests ?></strong><span>Registered guests</span></div>
      <div class="kpi"><strong><?= (int)$unreadMsgs ?></strong><span>Unread messages</span></div>
    </div>

    <div class="card">
      <div class="card-head">
        <h3>Recent bookings</h3>
        <a href="bookings.php" class="btn btn-dark btn-sm">Manage bookings</a>
      </div>
      <table class="data-table">
        <thead><tr><th>Code</th><th>Guest</th><th>Room</th><th>Check-in</th><th>Check-out</th><th>Total</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($recentBookings as $b): ?>
          <tr>
            <td><?= h($b['booking_code']) ?></td>
            <td><?= h($b['full_name']) ?></td>
            <td><?= h($b['type_name']) ?> #<?= h($b['room_number']) ?></td>
            <td><?= h(niceDate($b['check_in'])) ?></td>
            <td><?= h(niceDate($b['check_out'])) ?></td>
            <td><?= money($b['total_price']) ?></td>
            <td><span class="badge badge-<?= h($b['status']) ?>"><?= h($b['status']) ?></span></td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($recentBookings)): ?><tr><td colspan="7" class="muted">No bookings yet.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>
<script src="<?= BASE_URL ?>assets/js/admin.js"></script>
</body>
</html>
