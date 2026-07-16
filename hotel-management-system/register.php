<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = 'Create account';

if (isLoggedIn()) redirect(BASE_URL . 'dashboard/index.php');

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($fullName === '') $errors[] = 'Please enter your full name.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email.';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm) $errors[] = 'Passwords do not match.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'An account with that email already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone, password) VALUES (?,?,?,?)");
            $stmt->execute([$fullName, $email, $phone, $hash]);
            $_SESSION['user_id']   = $pdo->lastInsertId();
            $_SESSION['user_name'] = $fullName;
            setFlash('success', 'Welcome to Aurelia, ' . $fullName . '.');
            redirect(BASE_URL . 'dashboard/index.php');
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="auth-wrap">
  <div class="auth-card">
    <span class="eyebrow">New here</span>
    <h2>Create your account</h2>
    <p class="muted" style="margin-top:-10px">Book rooms, track stays, and manage everything from one place.</p>

    <?php if ($errors): ?>
      <div class="alert alert-error"><?= implode('<br>', array_map('h', $errors)) ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="form-group">
        <label for="full_name">Full name</label>
        <input type="text" id="full_name" name="full_name" required value="<?= h($_POST['full_name'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required value="<?= h($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" value="<?= h($_POST['phone'] ?? '') ?>">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
          <label for="confirm_password">Confirm password</label>
          <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Create account</button>
    </form>
    <p class="helper-link">Already have an account? <a href="login.php">Log in</a></p>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
