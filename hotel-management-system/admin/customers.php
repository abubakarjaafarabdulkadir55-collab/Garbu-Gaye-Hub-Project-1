<?php
require_once __DIR__ . '/../config/config.php';
requireAdmin();
$pageTitle = 'Customers';

$stmt = $pdo->query("SELECT u.*, COUNT(b.id) as booking_count, COALESCE(SUM(CASE WHEN b.status != 'cancelled' THEN b.total_price ELSE 0 END),0) as total_spent
                      FROM users u
                      LEFT JOIN bookings b ON b.user_id = u.id
                      GROUP BY u.id
                      ORDER BY u.created_at DESC");
$customers = $stmt->fetchAll();
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
    <div class="admin-topbar"><h1>Customers</h1></div>

    <div class="card">
      <div class="table-toolbar">
        <input type="search" placeholder="Search customers..." data-table-search="custTable">
      </div>
      <table class="data-table" id="custTable">
        <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Bookings</th><th>Total spent</th><th>Joined</th></tr></thead>
        <tbody>
        <?php foreach ($customers as $c): ?>
          <tr>
            <td><?= h($c['full_name']) ?></td>
            <td><?= h($c['email']) ?></td>
            <td><?= h($c['phone'] ?: '—') ?></td>
            <td><?= (int)$c['booking_count'] ?></td>
            <td><?= money($c['total_spent']) ?></td>
            <td><?= h(niceDate($c['created_at'])) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($customers)): ?><tr><td colspan="6" class="muted">No customers yet.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>
<script src="<?= BASE_URL ?>assets/js/admin.js"></script>
</body>
</html>
