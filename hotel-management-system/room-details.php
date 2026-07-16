<?php
require_once __DIR__ . '/config/config.php';

$typeId = (int)($_GET['type'] ?? 0);
$checkIn  = $_GET['check_in']  ?? '';
$checkOut = $_GET['check_out'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM room_types WHERE id = ?");
$stmt->execute([$typeId]);
$roomType = $stmt->fetch();

if (!$roomType) {
    redirect(BASE_URL . 'rooms.php');
}
$pageTitle = $roomType['name'];

$stmt = $pdo->prepare("SELECT * FROM rooms WHERE room_type_id = ? AND status='available' ORDER BY room_number");
$stmt->execute([$typeId]);
$rooms = $stmt->fetchAll();

$availableRooms = [];
foreach ($rooms as $room) {
    if ($checkIn && $checkOut) {
        if (isRoomAvailable($pdo, $room['id'], $checkIn, $checkOut)) $availableRooms[] = $room;
    } else {
        $availableRooms[] = $room;
    }
}

$amenities = array_map('trim', explode(',', $roomType['amenities']));

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <span class="eyebrow">Room type</span>
    <h1><?= h($roomType['name']) ?></h1>
    <p class="breadcrumbs">Home / Rooms / <?= h($roomType['name']) ?></p>
  </div>
</section>

<section class="section">
  <div class="container hero-grid" style="align-items:flex-start">
    <div class="arch-frame" style="max-width:100%">
      <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=900&auto=format&fit=crop" alt="<?= h($roomType['name']) ?>">
    </div>
    <div>
      <p class="lede muted"><?= h($roomType['description']) ?></p>
      <div class="hero-stats" style="margin-top:10px">
        <div><strong><?= money($roomType['base_price']) ?></strong><span>Per night</span></div>
        <div><strong><?= (int)$roomType['capacity'] ?></strong><span>Max guests</span></div>
        <div><strong><?= (int)$roomType['size_sqm'] ?> m&sup2;</strong><span>Room size</span></div>
      </div>
      <h3 class="mt-30">Amenities</h3>
      <div class="tag-row">
        <?php foreach ($amenities as $a): ?><span class="tag"><?= h($a) ?></span><?php endforeach; ?>
      </div>

      <h3 class="mt-30">Available rooms</h3>
      <?php if (empty($availableRooms)): ?>
        <p class="muted">No rooms of this type are free for the selected dates. Try adjusting your dates on the <a href="rooms.php">rooms page</a>.</p>
      <?php else: ?>
        <table class="data-table mt-30">
          <thead><tr><th>Room</th><th>Floor</th><th></th></tr></thead>
          <tbody>
          <?php foreach ($availableRooms as $room): ?>
            <tr>
              <td>Room <?= h($room['room_number']) ?></td>
              <td>Floor <?= (int)$room['floor'] ?></td>
              <td>
                <a class="btn btn-primary btn-sm" href="<?= isLoggedIn() ? 'book-room.php?room=' . $room['id'] . '&check_in=' . h($checkIn) . '&check_out=' . h($checkOut) : 'login.php' ?>">
                  <?= isLoggedIn() ? 'Book this room' : 'Log in to book' ?>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
