<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = 'Home';

// Featured rooms — one per room type
$stmt = $pdo->query("SELECT rt.*, MIN(r.id) as sample_room_id
                      FROM room_types rt
                      JOIN rooms r ON r.room_type_id = rt.id AND r.status='available'
                      GROUP BY rt.id ORDER BY rt.base_price ASC");
$roomTypes = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <div class="container hero-grid">
    <div class="hero-copy">
      <span class="eyebrow">Rivermill, Est. 1998</span>
      <h1>A quiet arrival,<br>every time.</h1>
      <p class="lede">Aurelia sits at the edge of the old river district — courtyard rooms, honest service, and a front desk that remembers your name by the second night.</p>
      <div class="hero-stats">
        <div><strong>27</strong><span>Years welcoming guests</span></div>
        <div><strong>4.8</strong><span>Average guest rating</span></div>
        <div><strong>10</strong><span>Rooms &amp; suites</span></div>
      </div>
      <div class="hero-actions">
        <a href="rooms.php" class="btn btn-primary">Browse rooms</a>
        <a href="about.php" class="btn btn-outline">Our story</a>
      </div>
    </div>
    <div class="arch-frame">
      <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=800&auto=format&fit=crop" alt="Aurelia Hotel courtyard suite">
    </div>
  </div>

  <div class="container">
    <form class="book-widget" action="rooms.php" method="get">
      <div>
        <label for="check_in">Check-in</label>
        <input type="date" id="check_in" name="check_in">
      </div>
      <div>
        <label for="check_out">Check-out</label>
        <input type="date" id="check_out" name="check_out">
      </div>
      <div>
        <label for="guests">Guests</label>
        <select id="guests" name="guests">
          <option value="1">1 guest</option>
          <option value="2" selected>2 guests</option>
          <option value="3">3 guests</option>
          <option value="4">4 guests</option>
        </select>
      </div>
      <div>
        <label for="room_type">Room type</label>
        <select id="room_type" name="room_type">
          <option value="">Any type</option>
          <?php foreach ($roomTypes as $rt): ?>
            <option value="<?= $rt['id'] ?>"><?= h($rt['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn btn-dark">Check availability</button>
    </form>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow">Rooms &amp; Suites</span>
      <h2>Four ways to stay</h2>
      <p class="muted">Every category is built around one idea: enough space to actually unpack, and nothing that gets in the way of it.</p>
    </div>
    <div class="room-grid">
      <?php foreach ($roomTypes as $i => $rt):
        $images = [
          'https://images.unsplash.com/photo-1590490360182-c33d57733427?q=80&w=700&auto=format&fit=crop',
          'https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=700&auto=format&fit=crop',
          'https://images.unsplash.com/photo-1591088398332-8a7791972843?q=80&w=700&auto=format&fit=crop',
          'https://images.unsplash.com/photo-1611048267451-e6ed903d4a38?q=80&w=700&auto=format&fit=crop',
        ];
        $amenities = array_slice(explode(',', $rt['amenities']), 0, 3);
      ?>
      <div class="room-card">
        <div class="thumb"><img src="<?= $images[$i % count($images)] ?>" alt="<?= h($rt['name']) ?>"></div>
        <div class="body">
          <h3><?= h($rt['name']) ?></h3>
          <p class="muted" style="font-size:.9rem"><?= h($rt['description']) ?></p>
          <div class="tag-row">
            <?php foreach ($amenities as $a): ?><span class="tag"><?= h(trim($a)) ?></span><?php endforeach; ?>
          </div>
          <div class="price"><?= money($rt['base_price']) ?> <small>/ night</small></div>
          <div class="cta-row">
            <a href="room-details.php?type=<?= $rt['id'] ?>" class="btn btn-dark btn-sm">View details</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container feature-grid">
    <div class="feature-item">
      <div class="arch-icon">&#127968;</div>
      <h3>Courtyard quiet</h3>
      <p class="muted">Rooms face inward, toward the garden — not the street.</p>
    </div>
    <div class="feature-item">
      <div class="arch-icon">&#9749;</div>
      <h3>Breakfast until 11</h3>
      <p class="muted">For guests who did not come here to set an alarm.</p>
    </div>
    <div class="feature-item">
      <div class="arch-icon">&#128274;</div>
      <h3>Flexible booking</h3>
      <p class="muted">Change your dates from your account, no phone call needed.</p>
    </div>
    <div class="feature-item">
      <div class="arch-icon">&#128172;</div>
      <h3>A desk that answers</h3>
      <p class="muted">Real replies from the front desk, usually within the hour.</p>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow">Guest notes</span>
      <h2>What people remember</h2>
    </div>
    <div class="room-grid">
      <div class="quote-card">
        <div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
        <p>"Small details, done properly — the courtyard breakfast, the quiet halls. We rebooked before we'd even checked out."</p>
        <strong>— Naomi R.</strong>
      </div>
      <div class="quote-card">
        <div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
        <p>"The Executive Suite's lounge sold us. Genuinely felt like renting a small apartment instead of a hotel room."</p>
        <strong>— Daniel K.</strong>
      </div>
      <div class="quote-card">
        <div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
        <p>"Booked, changed my dates twice, and cancelled a spare room, all from my account. Never had to call anyone."</p>
        <strong>— Priya S.</strong>
      </div>
    </div>
  </div>
</section>

<section class="section section-dark text-center">
  <div class="container">
    <span class="eyebrow">Ready when you are</span>
    <h2>Your room at Aurelia is one form away.</h2>
    <div class="hero-actions" style="justify-content:center; margin-top:26px;">
      <a href="rooms.php" class="btn btn-primary">Browse availability</a>
      <a href="contact.php" class="btn btn-outline">Ask us a question</a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
