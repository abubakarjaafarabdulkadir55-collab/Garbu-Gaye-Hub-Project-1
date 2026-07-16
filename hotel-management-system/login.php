<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = 'Log in';

if (isLoggedIn()) redirect(BASE_URL . 'dashboard/index.php');

$errors = [];
$redirectAfter = $_GET['redirect'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        setFlash('success', 'Welcome back, ' . $user['full_name'] . '.');
        redirect(BASE_URL . ($_POST['redirect'] ?: 'dashboard/index.php'));
    } else {
        $errors[] = 'Incorrect email or password.';
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="auth-wrap">
  <div class="auth-card">
    <span class="eyebrow">Welcome back</span>
    <h2>Log in to your account</h2>

    <?php if ($errors): ?>
      <div class="alert alert-error"><?= implode('<br>', array_map('h', $errors)) ?></div>
    <?php endif; ?>

    <form method="post">
      <input type="hidden" name="redirect" value="<?= h($redirectAfter) ?>">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required value="<?= h($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Log in</button>
    </form>
    <p class="helper-link">New to Aurelia? <a href="register.php">Create an account</a></p>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
