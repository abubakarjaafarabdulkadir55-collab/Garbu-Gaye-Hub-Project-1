<?php
require_once __DIR__ . '/../config/config.php';
requireLogin();
$pageTitle = 'My profile';
$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $newPass  = $_POST['new_password'] ?? '';

    if ($fullName === '') $errors[] = 'Full name is required.';

    if (empty($errors)) {
        if ($newPass !== '') {
            if (strlen($newPass) < 6) {
                $errors[] = 'New password must be at least 6 characters.';
            } else {
                $hash = password_hash($newPass, PASSWORD_DEFAULT);
                $pdo->prepare("UPDATE users SET full_name=?, phone=?, password=? WHERE id=?")
                    ->execute([$fullName, $phone, $hash, $userId]);
            }
        } else {
            $pdo->prepare("UPDATE users SET full_name=?, phone=? WHERE id=?")
                ->execute([$fullName, $phone, $userId]);
        }

        if (empty($errors)) {
            $_SESSION['user_name'] = $fullName;
            $user['full_name'] = $fullName;
            $user['phone'] = $phone;
            $success = true;
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="dash-shell">
  <?php require __DIR__ . '/../includes/dashboard-sidebar.php'; ?>
  <main class="dash-main">
    <h2>My profile</h2>

    <?php if ($success): ?><div class="alert alert-success">Your profile has been updated.</div><?php endif; ?>
    <?php if ($errors): ?><div class="alert alert-error"><?= implode('<br>', array_map('h', $errors)) ?></div><?php endif; ?>

    <div class="card" style="max-width:520px">
      <form method="post">
        <div class="form-group">
          <label for="full_name">Full name</label>
          <input type="text" id="full_name" name="full_name" required value="<?= h($user['full_name']) ?>">
        </div>
        <div class="form-group">
          <label>Email (cannot be changed)</label>
          <input type="email" value="<?= h($user['email']) ?>" disabled>
        </div>
        <div class="form-group">
          <label for="phone">Phone</label>
          <input type="text" id="phone" name="phone" value="<?= h($user['phone']) ?>">
        </div>
        <div class="form-group">
          <label for="new_password">New password <span class="muted">(leave blank to keep current)</span></label>
          <input type="password" id="new_password" name="new_password">
        </div>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </form>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
