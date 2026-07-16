<?php
require_once __DIR__ . '/../config/config.php';
requireLogin();
$pageTitle = 'My bookings';
$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT b.*, r.room_number, rt.name as type_name
                        FROM bookings b
                        JOIN rooms r ON r.id = b.room_id
                        JOIN room_types rt ON rt.id = r.room_type_id
                        WHERE b.user_id = ?
                        ORDER BY b.check_in DESC");
$stmt->execute([$userId]);
$bookings = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="dash-shell">
  <?php require __DIR__ . '/../includes/dashboard-sidebar.php'; ?>
  <main class="dash-main">
    <div class="card-head">
      <h2 class="mb-0">My bookings</h2>
      <a href="<?= BASE_URL ?>rooms.php" class="btn btn-primary btn-sm">Book a new room</a>
    </div>

    <?php if (empty($bookings)): ?>
      <p class="muted">No bookings yet. <a href="<?= BASE_URL ?>rooms.php">Browse rooms</a> to make your first one.</p>
    <?php else: ?>
      <table class="data-table">
        <thead><tr><th>Code</th><th>Room</th><th>Check-in</th><th>Check-out</th><th>Nights</th><th>Total</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($bookings as $b): ?>
          <tr>
            <td><?= h($b['booking_code']) ?></td>
            <td><?= h($b['type_name']) ?> #<?= h($b['room_number']) ?></td>
            <td><?= h(niceDate($b['check_in'])) ?></td>
            <td><?= h(niceDate($b['check_out'])) ?></td>
            <td><?= (int)$b['nights'] ?></td>
            <td><?= money($b['total_price']) ?></td>
            <td><span class="badge badge-<?= h($b['status']) ?>"><?= h($b['status']) ?></span></td>
            <td>
              <?php if (in_array($b['status'], ['pending','confirmed'])): ?>
                <form method="post" action="cancel-booking.php" onsubmit="return confirm('Cancel this booking?');">
                  <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                  <button type="submit" class="btn btn-wine btn-sm">Cancel</button>
                </form>
              <?php else: ?>
                <span class="muted">—</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
