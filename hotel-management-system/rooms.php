<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = 'Rooms & Suites';

$checkIn  = $_GET['check_in']  ?? '';
$checkOut = $_GET['check_out'] ?? '';
$guests   = (int)($_GET['guests'] ?? 0);
$typeId   = $_GET['room_type'] ?? '';

$sql = "SELECT rt.*, r.id as room_id, r.room_number, r.status
        FROM room_types rt
        JOIN rooms r ON r.room_type_id = rt.id
        WHERE r.status = 'available'";
$params = [];

if ($guests > 0) {
    $sql .= " AND rt.capacity >= :guests";
    $params[':guests'] = $guests;
}
if ($typeId !== '') {
    $sql .= " AND rt.id = :type_id";
    $params[':type_id'] = $typeId;
}
$sql .= " ORDER BY rt.base_price ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$allRooms = $stmt->fetchAll();

// If dates were given, filter out rooms already booked for that range
$filtered = [];
foreach ($allRooms as $room) {
    if ($checkIn && $checkOut) {
        if (isRoomAvailable($pdo, $room['room_id'], $checkIn, $checkOut)) {
            $filtered[] = $room;
        }
    } else {
        $filtered[] = $room;
    }
}

// Group by room type so we show one card per type with a representative room
$byType = [];
foreach ($filtered as $room) {
    if (!isset($byType[$room['id']])) $byType[$room['id']] = $room;
}

$roomTypesForFilter = $pdo->query("SELECT id, name FROM room_types ORDER BY base_price ASC")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <span class="eyebrow">Availability</span>
    <h1>Rooms &amp; Suites</h1>
    <p class="breadcrumbs">Home / Rooms</p>
  </div>
</section>

<section class="section" style="padding-top:50px">
  <div class="container">
    <form class="book-widget" style="margin-top:0" method="get">
      <div>
        <label for="check_in">Check-in</label>
        <input type="date" id="check_in" name="check_in" value="<?= h($checkIn) ?>">
      </div>
      <div>
        <label for="check_out">Check-out</label>
        <input type="date" id="check_out" name="check_out" value="<?= h($checkOut) ?>">
      </div>
      <div>
        <label for="guests">Guests</label>
        <select id="guests" name="guests">
          <option value="0">Any</option>
          <?php for ($g=1; $g<=4; $g++): ?>
            <option value="<?= $g ?>" <?= $guests==$g?'selected':'' ?>><?= $g ?> guest<?= $g>1?'s':'' ?></option>
          <?php endfor; ?>
        </select>
      </div>
      <div>
        <label for="room_type">Room type</label>
        <select id="room_type" name="room_type">
          <option value="">Any type</option>
          <?php foreach ($roomTypesForFilter as $rt): ?>
            <option value="<?= $rt['id'] ?>" <?= $typeId==$rt['id']?'selected':'' ?>><?= h($rt['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn btn-dark">Search</button>
    </form>

    <div class="mt-30">
      <?php if (($checkIn && $checkOut)): ?>
        <p class="muted"><?= count($byType) ?> room type(s) available from <?= h(niceDate($checkIn)) ?> to <?= h(niceDate($checkOut)) ?>.</p>
      <?php endif; ?>
    </div>

    <div class="room-grid mt-30">
      <?php if (empty($byType)): ?>
        <p>No rooms match that search. Try different dates or clear the filters.</p>
      <?php endif; ?>
      <?php foreach ($byType as $rt):
        $amenities = array_slice(explode(',', $rt['amenities']), 0, 4);
      ?>
      <div class="room-card">
        <div class="thumb"><img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?q=80&w=700&auto=format&fit=crop" alt="<?= h($rt['name']) ?>"></div>
        <div class="body">
          <h3><?= h($rt['name']) ?></h3>
          <p class="muted" style="font-size:.9rem"><?= h($rt['description']) ?></p>
          <div class="tag-row">
            <?php foreach ($amenities as $a): ?><span class="tag"><?= h(trim($a)) ?></span><?php endforeach; ?>
          </div>
          <div class="price"><?= money($rt['base_price']) ?> <small>/ night &middot; sleeps <?= (int)$rt['capacity'] ?></small></div>
          <div class="cta-row">
            <a href="room-details.php?type=<?= $rt['id'] ?>&check_in=<?= h($checkIn) ?>&check_out=<?= h($checkOut) ?>" class="btn btn-dark btn-sm">View &amp; book</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
