<?php
require_once __DIR__ . '/../config/config.php';
requireAdmin();
$pageTitle = 'Messages';

if (isset($_GET['read'])) {
    $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?")->execute([(int)$_GET['read']]);
    redirect(BASE_URL . 'admin/messages.php');
}

$messages = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
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
    <div class="admin-topbar"><h1>Messages</h1></div>

    <?php foreach ($messages as $m): ?>
      <div class="card" style="<?= $m['is_read'] ? '' : 'border-left:4px solid var(--gold)' ?>">
        <div class="card-head">
          <div>
            <h3 class="mb-0"><?= h($m['subject'] ?: '(no subject)') ?></h3>
            <span class="muted" style="font-size:.85rem"><?= h($m['name']) ?> &middot; <?= h($m['email']) ?> &middot; <?= h(niceDate($m['created_at'])) ?></span>
          </div>
          <?php if (!$m['is_read']): ?>
            <a href="messages.php?read=<?= $m['id'] ?>" class="btn btn-sm btn-dark">Mark as read</a>
          <?php else: ?>
            <span class="badge badge-checked_out">read</span>
          <?php endif; ?>
        </div>
        <p><?= nl2br(h($m['message'])) ?></p>
      </div>
    <?php endforeach; ?>
    <?php if (empty($messages)): ?><p class="muted">No messages yet.</p><?php endif; ?>
  </main>
</div>
<script src="<?= BASE_URL ?>assets/js/admin.js"></script>
</body>
</html>
