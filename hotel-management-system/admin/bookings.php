<?php
require_once __DIR__ . '/../config/config.php';
requireAdmin();
$pageTitle = 'Manage bookings';

$statusFilter = $_GET['status'] ?? '';
$sql = "SELECT b.*, u.full_name, u.email, r.room_number, rt.name as type_name
        FROM bookings b
        JOIN users u ON u.id = b.user_id
        JOIN rooms r ON r.id = b.room_id
        JOIN room_types rt ON rt.id = r.room_type_id";
$params = [];
if ($statusFilter !== '') {
    $sql .= " WHERE b.status = ?";
    $params[] = $statusFilter;
}
$sql .= " ORDER BY b.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$bookings = $stmt->fetchAll();
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
      <h1>Bookings</h1>
    </div>

    <?php $flash = getFlash(); if ($flash): ?>
      <div class="alert alert-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
    <?php endif; ?>

    <div class="card">
      <div class="table-toolbar">
        <input type="search" placeholder="Search bookings..." data-table-search="bookingsTable">
        <div style="display:flex; gap:8px; flex-wrap:wrap">
          <a class="btn btn-sm <?= $statusFilter===''?'btn-dark':'btn-outline' ?>" style="<?= $statusFilter===''?'':'color:var(--forest); border-color:var(--line)' ?>" href="bookings.php">All</a>
          <?php foreach (['pending','confirmed','checked_in','checked_out','cancelled'] as $s): ?>
            <a class="btn btn-sm <?= $statusFilter===$s?'btn-dark':'btn-outline' ?>" style="<?= $statusFilter===$s?'':'color:var(--forest); border-color:var(--line)' ?>" href="bookings.php?status=<?= $s ?>"><?= ucfirst(str_replace('_',' ',$s)) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <table class="data-table" id="bookingsTable">
        <thead><tr><th>Code</th><th>Guest</th><th>Room</th><th>Check-in</th><th>Check-out</th><th>Total</th><th>Status</th><th>Update</th></tr></thead>
        <tbody>
        <?php foreach ($bookings as $b): ?>
          <tr>
            <td><?= h($b['booking_code']) ?></td>
            <td><?= h($b['full_name']) ?><br><span class="muted" style="font-size:.78rem"><?= h($b['email']) ?></span></td>
            <td><?= h($b['type_name']) ?> #<?= h($b['room_number']) ?></td>
            <td><?= h(niceDate($b['check_in'])) ?></td>
            <td><?= h(niceDate($b['check_out'])) ?></td>
            <td><?= money($b['total_price']) ?></td>
            <td><span class="badge badge-<?= h($b['status']) ?>"><?= h($b['status']) ?></span></td>
            <td>
              <form method="post" action="booking-update.php" class="status-form" style="display:flex; gap:6px">
                <input type="hidden" name="id" value="<?= $b['id'] ?>">
                <select name="status">
                  <?php foreach (['pending','confirmed','checked_in','checked_out','cancelled'] as $s): ?>
                    <option value="<?= $s ?>" <?= $b['status']===$s?'selected':'' ?>><?= ucfirst(str_replace('_',' ',$s)) ?></option>
                  <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-sm btn-dark">Save</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($bookings)): ?><tr><td colspan="8" class="muted">No bookings found.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>
<script src="<?= BASE_URL ?>assets/js/admin.js"></script>
</body>
</html>
