<?php $cur = basename($_SERVER['PHP_SELF']); ?>
<aside class="dash-side">
  <div class="brand" style="margin-bottom:24px; display:block"><a href="<?= BASE_URL ?>index.php" style="color:inherit">Aurelia <span style="color:var(--gold)">Hotel</span></a></div>
  <a href="index.php" class="<?= $cur==='index.php'?'active':'' ?>">&#128200; Overview</a>
  <a href="my-bookings.php" class="<?= $cur==='my-bookings.php'?'active':'' ?>">&#128197; My bookings</a>
  <a href="profile.php" class="<?= $cur==='profile.php'?'active':'' ?>">&#128100; Profile</a>
  <a href="<?= BASE_URL ?>rooms.php">&#127968; Book a room</a>
  <a href="<?= BASE_URL ?>logout.php">&#8630; Log out</a>
</aside>
