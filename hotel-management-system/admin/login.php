<?php
require_once __DIR__ . '/../config/config.php';
$pageTitle = 'Admin login';

if (isAdmin()) redirect(BASE_URL . 'admin/index.php');

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id']   = $admin['id'];
        $_SESSION['admin_name'] = $admin['full_name'];
        $_SESSION['admin_role'] = $admin['role'];
        redirect(BASE_URL . 'admin/index.php');
    } else {
        $errors[] = 'Incorrect email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin login · Aurelia Hotel</title>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>
<div class="auth-wrap" style="background:var(--forest-deep)">
  <div class="auth-card">
    <span class="eyebrow">Staff access</span>
    <h2>Admin login</h2>
    <p class="muted" style="margin-top:-10px">Aurelia Hotel management console.</p>

    <?php if ($errors): ?>
      <div class="alert alert-error"><?= implode('<br>', array_map('h', $errors)) ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required value="<?= h($_POST['email'] ?? 'admin@aurelia.com') ?>">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Log in</button>
    </form>
    <p class="helper-link muted" style="font-size:.78rem">Default seed login: admin@aurelia.com — see sql/schema.sql notes to set the password hash.</p>
  </div>
</div>
</body>
</html>
