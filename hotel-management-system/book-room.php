<?php
require_once __DIR__ . '/config/config.php';
requireLogin();
$pageTitle = 'Book your room';

$roomId = (int)($_GET['room'] ?? $_POST['room_id'] ?? 0);
$stmt = $pdo->prepare("SELECT r.*, rt.name as type_name, rt.base_price, rt.capacity, rt.amenities
                        FROM rooms r JOIN room_types rt ON rt.id = r.room_type_id
                        WHERE r.id = ?");
$stmt->execute([$roomId]);
$room = $stmt->fetch();

if (!$room) redirect(BASE_URL . 'rooms.php');

$errors = [];
$checkIn  = $_POST['check_in']  ?? ($_GET['check_in']  ?? '');
$checkOut = $_POST['check_out'] ?? ($_GET['check_out'] ?? '');
$guests   = $_POST['guests'] ?? 1;
$requests = $_POST['special_requests'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$checkIn || !$checkOut) $errors[] = 'Please choose a check-in and check-out date.';
    elseif (strtotime($checkOut) <= strtotime($checkIn)) $errors[] = 'Check-out must be after check-in.';
    elseif ((int)$guests > $room['capacity']) $errors[] = 'This room sleeps a maximum of ' . $room['capacity'] . ' guests.';
    elseif (!isRoomAvailable($pdo, $room['id'], $checkIn, $checkOut)) $errors[] = 'This room is no longer available for those dates.';

    if (empty($errors)) {
        $nights = (strtotime($checkOut) - strtotime($checkIn)) / 86400;
        $total  = $nights * $room['base_price'];
        $code   = generateBookingCode();

        $stmt = $pdo->prepare("INSERT INTO bookings (booking_code, user_id, room_id, check_in, check_out, guests, nights, total_price, status, special_requests)
                                VALUES (?,?,?,?,?,?,?,?, 'pending', ?)");
        $stmt->execute([$code, $_SESSION['user_id'], $room['id'], $checkIn, $checkOut, $guests, $nights, $total, $requests]);
        $bookingId = $pdo->lastInsertId();

        $pdo->prepare("INSERT INTO payments (booking_id, amount, method, status) VALUES (?,?, 'card', 'pending')")
            ->execute([$bookingId, $total]);

        redirect(BASE_URL . 'booking-confirmation.php?id=' . $bookingId);
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <span class="eyebrow">Almost there</span>
    <h1>Book Room <?= h($room['room_number']) ?></h1>
    <p class="breadcrumbs">Home / Rooms / <?= h($room['type_name']) ?> / Book</p>
  </div>
</section>

<section class="section">
  <div class="container hero-grid" style="align-items:flex-start">
    <form method="post" class="auth-card" style="border-top:5px solid var(--gold)" data-price="<?= $room['base_price'] ?>">
      <?php if ($errors): ?>
        <div class="alert alert-error"><?= implode('<br>', array_map('h', $errors)) ?></div>
      <?php endif; ?>
      <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
      <div class="form-row">
        <div class="form-group">
          <label for="check_in">Check-in</label>
          <input type="date" id="check_in" name="check_in" required value="<?= h($checkIn) ?>">
        </div>
        <div class="form-group">
          <label for="check_out">Check-out</label>
          <input type="date" id="check_out" name="check_out" required value="<?= h($checkOut) ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="guests">Guests</label>
        <select id="guests" name="guests">
          <?php for ($g=1; $g<=$room['capacity']; $g++): ?>
            <option value="<?= $g ?>" <?= $guests==$g?'selected':'' ?>><?= $g ?> guest<?= $g>1?'s':'' ?></option>
          <?php endfor; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="special_requests">Special requests (optional)</label>
        <textarea id="special_requests" name="special_requests"><?= h($requests) ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Confirm booking</button>
    </form>

    <div>
      <h3>Booking summary</h3>
      <table class="data-table">
        <tbody>
          <tr><td>Room</td><td><?= h($room['type_name']) ?> — Room <?= h($room['room_number']) ?></td></tr>
          <tr><td>Rate</td><td><?= money($room['base_price']) ?> / night</td></tr>
          <tr><td>Nights</td><td id="nightsOut">—</td></tr>
          <tr><td>Total</td><td><strong><?= CURRENCY ?><span id="totalOut">0.00</span></strong></td></tr>
        </tbody>
      </table>
      <p class="muted mt-30" style="font-size:.85rem">Payment is collected at check-in or by card once the front desk confirms your booking. You can cancel from your dashboard any time before check-in.</p>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
