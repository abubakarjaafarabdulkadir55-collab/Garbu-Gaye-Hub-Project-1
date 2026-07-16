<?php
require_once __DIR__ . '/../config/config.php';
requireAdmin();
$pageTitle = 'Manage rooms';

$roomTypes = $pdo->query("SELECT * FROM room_types ORDER BY base_price ASC")->fetchAll();

$stmt = $pdo->query("SELECT r.*, rt.name as type_name, rt.base_price
                      FROM rooms r JOIN room_types rt ON rt.id = r.room_type_id
                      ORDER BY r.room_number ASC");
$rooms = $stmt->fetchAll();
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
      <h1>Rooms</h1>
      <button class="btn btn-primary" data-open-modal="roomModal" onclick="document.getElementById('roomModalTitle').textContent='Add a room'; document.getElementById('roomForm').reset(); document.getElementById('roomForm').querySelector('[name=id]').value='';">+ Add room</button>
    </div>

    <?php $flash = getFlash(); if ($flash): ?>
      <div class="alert alert-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
    <?php endif; ?>

    <div class="card">
      <div class="table-toolbar">
        <input type="search" placeholder="Search rooms..." data-table-search="roomsTable">
      </div>
      <table class="data-table" id="roomsTable">
        <thead><tr><th>Room</th><th>Type</th><th>Floor</th><th>Rate</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($rooms as $r): ?>
          <tr>
            <td>Room <?= h($r['room_number']) ?></td>
            <td><?= h($r['type_name']) ?></td>
            <td><?= (int)$r['floor'] ?></td>
            <td><?= money($r['base_price']) ?></td>
            <td><span class="badge badge-<?= $r['status']==='available'?'confirmed':'cancelled' ?>"><?= h($r['status']) ?></span></td>
            <td style="display:flex; gap:8px">
              <button class="btn btn-sm btn-dark" data-open-modal="roomModal" data-edit-room
                data-id="<?= $r['id'] ?>" data-number="<?= h($r['room_number']) ?>"
                data-type-id="<?= $r['room_type_id'] ?>" data-floor="<?= $r['floor'] ?>"
                data-status="<?= $r['status'] ?>" data-image="<?= h($r['image_url']) ?>">Edit</button>
              <form method="post" action="room-delete.php" onsubmit="return confirm('Delete Room <?= h($r['room_number']) ?>? This also removes its bookings.');">
                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                <button type="submit" class="btn btn-sm btn-wine">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<!-- Add / Edit room modal -->
<div class="modal-bg" id="roomModal">
  <div class="modal">
    <div class="modal-head">
      <h3 id="roomModalTitle" class="mb-0">Add a room</h3>
      <button class="modal-close" data-close-modal>&times;</button>
    </div>
    <form method="post" action="room-save.php" id="roomForm">
      <input type="hidden" name="id" value="">
      <div class="form-group">
        <label for="room_number">Room number</label>
        <input type="text" id="room_number" name="room_number" required>
      </div>
      <div class="form-group">
        <label for="room_type_id">Room type</label>
        <select id="room_type_id" name="room_type_id" required>
          <?php foreach ($roomTypes as $rt): ?>
            <option value="<?= $rt['id'] ?>"><?= h($rt['name']) ?> — <?= money($rt['base_price']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="floor">Floor</label>
          <input type="number" id="floor" name="floor" min="1" value="1" required>
        </div>
        <div class="form-group">
          <label for="status">Status</label>
          <select id="status" name="status">
            <option value="available">Available</option>
            <option value="maintenance">Maintenance</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="image_url">Image URL (optional)</label>
        <input type="text" id="image_url" name="image_url" placeholder="https://...">
      </div>
      <button type="submit" class="btn btn-primary btn-block">Save room</button>
    </form>
  </div>
</div>

<script src="<?= BASE_URL ?>assets/js/admin.js"></script>
</body>
</html>
