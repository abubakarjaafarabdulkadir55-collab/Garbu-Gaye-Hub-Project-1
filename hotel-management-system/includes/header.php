<?php
// Expects $pageTitle to be set by the including page.
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? h($pageTitle) . ' · ' . SITE_NAME : SITE_NAME ?></title>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>

<nav class="navbar">
  <div class="container">
    <a href="<?= BASE_URL ?>index.php" class="brand">Aurelia <span>Hotel</span></a>
    <ul class="nav-links">
      <li><a href="<?= BASE_URL ?>index.php" class="<?= $current==='index.php'?'active':'' ?>">Home</a></li>
      <li><a href="<?= BASE_URL ?>rooms.php" class="<?= $current==='rooms.php'||$current==='room-details.php'?'active':'' ?>">Rooms</a></li>
      <li><a href="<?= BASE_URL ?>about.php" class="<?= $current==='about.php'?'active':'' ?>">About</a></li>
      <li><a href="<?= BASE_URL ?>contact.php" class="<?= $current==='contact.php'?'active':'' ?>">Contact</a></li>
      <?php if (isLoggedIn()): ?>
        <li><a href="<?= BASE_URL ?>dashboard/index.php">My account</a></li>
        <li><a href="<?= BASE_URL ?>logout.php">Log out</a></li>
      <?php else: ?>
        <li><a href="<?= BASE_URL ?>login.php" class="<?= $current==='login.php'?'active':'' ?>">Log in</a></li>
      <?php endif; ?>
    </ul>
    <div class="nav-actions">
      <?php if (!isLoggedIn()): ?>
        <a href="<?= BASE_URL ?>register.php" class="btn btn-primary btn-sm">Book now</a>
      <?php else: ?>
        <a href="<?= BASE_URL ?>rooms.php" class="btn btn-primary btn-sm">Book now</a>
      <?php endif; ?>
      <button class="nav-toggle" aria-label="Toggle menu">&#9776;</button>
    </div>
  </div>
</nav>

<?php $flash = getFlash(); if ($flash): ?>
  <div class="container mt-30">
    <div class="alert alert-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
  </div>
<?php endif; ?>
