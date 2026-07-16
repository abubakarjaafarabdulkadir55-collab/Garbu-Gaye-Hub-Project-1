<?php $cur = basename($_SERVER['PHP_SELF']); ?>
<aside class="admin-side">
  <div class="admin-brand">Aurelia <span>Admin</span></div>
  <nav class="admin-nav">
    <span class="group-label">Overview</span>
    <a href="index.php" class="<?= $cur==='index.php'?'active':'' ?>">&#128202; Dashboard</a>

    <span class="group-label">Operations</span>
    <a href="bookings.php" class="<?= $cur==='bookings.php'?'active':'' ?>">&#128197; Bookings</a>
    <a href="rooms.php" class="<?= $cur==='rooms.php'?'active':'' ?>">&#127968; Rooms</a>
    <a href="customers.php" class="<?= $cur==='customers.php'?'active':'' ?>">&#128100; Customers</a>
    <a href="messages.php" class="<?= $cur==='messages.php'?'active':'' ?>">&#9993; Messages</a>

    <span class="group-label">Account</span>
    <a href="<?= BASE_URL ?>index.php">&#8617; View site</a>
    <a href="logout.php">&#8630; Log out</a>
  </nav>
</aside>
