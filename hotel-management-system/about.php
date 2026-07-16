<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = 'About';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <span class="eyebrow">Since 1998</span>
    <h1>About Aurelia</h1>
    <p class="breadcrumbs">Home / About</p>
  </div>
</section>

<section class="section">
  <div class="container hero-grid" style="align-items:center">
    <div class="arch-frame">
      <img src="https://images.unsplash.com/photo-1445019980597-93fa8acb246c?q=80&w=800&auto=format&fit=crop" alt="Aurelia Hotel lobby">
    </div>
    <div>
      <span class="eyebrow">Our story</span>
      <h2>Built around a courtyard, not a lobby</h2>
      <p class="muted">Aurelia opened in 1998 in a converted merchant's house on Magnolia Court. Rather than build the hotel around a grand lobby, the original owners kept the courtyard at its center — every room still looks inward, toward the garden, instead of out onto the street.</p>
      <p class="muted">Three decades later that idea hasn't changed. We're still a small, independently run hotel: ten rooms, a kitchen that does breakfast properly, and a front desk that tends to remember your name by the second morning.</p>
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container feature-grid">
    <div class="feature-item">
      <div class="arch-icon">&#127807;</div>
      <h3>The courtyard</h3>
      <p class="muted">A quiet garden at the center of the building, visible from nearly every room.</p>
    </div>
    <div class="feature-item">
      <div class="arch-icon">&#128221;</div>
      <h3>Independently run</h3>
      <p class="muted">No chain, no franchise — just one hotel that's been run by the same small team since it opened.</p>
    </div>
    <div class="feature-item">
      <div class="arch-icon">&#127869;</div>
      <h3>Local first</h3>
      <p class="muted">Breakfast produce and coffee are sourced from within twenty minutes of the front door.</p>
    </div>
  </div>
</section>

<section class="section section-dark text-center">
  <div class="container">
    <span class="eyebrow">Come see it yourself</span>
    <h2>Ten rooms. One courtyard. No two stays the same.</h2>
    <div class="hero-actions" style="justify-content:center; margin-top:26px;">
      <a href="rooms.php" class="btn btn-primary">Browse rooms</a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
