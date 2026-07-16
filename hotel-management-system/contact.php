<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = 'Contact';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '') $errors[] = 'Please enter your name.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email.';
    if ($message === '') $errors[] = 'Please write a message.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?,?,?,?)");
        $stmt->execute([$name, $email, $subject, $message]);
        setFlash('success', 'Thanks — your message has been sent. We usually reply within the hour.');
        redirect(BASE_URL . 'contact.php');
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <span class="eyebrow">Get in touch</span>
    <h1>Contact Aurelia</h1>
    <p class="breadcrumbs">Home / Contact</p>
  </div>
</section>

<section class="section">
  <div class="container hero-grid" style="align-items:flex-start">
    <div>
      <?php if ($errors): ?>
        <div class="alert alert-error"><?= implode('<br>', array_map('h', $errors)) ?></div>
      <?php endif; ?>
      <form method="post" class="auth-card" style="border-top:5px solid var(--gold)">
        <h3>Send us a message</h3>
        <div class="form-row">
          <div class="form-group">
            <label for="name">Full name</label>
            <input type="text" id="name" name="name" required value="<?= h($_POST['name'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required value="<?= h($_POST['email'] ?? '') ?>">
          </div>
        </div>
        <div class="form-group">
          <label for="subject">Subject</label>
          <input type="text" id="subject" name="subject" value="<?= h($_POST['subject'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label for="message">Message</label>
          <textarea id="message" name="message" required><?= h($_POST['message'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Send message</button>
      </form>
    </div>
    <div>
      <h3>Reception desk</h3>
      <p class="muted">Open daily, 7am – 11pm.</p>
      <table class="data-table mt-30">
        <tbody>
          <tr><td>Phone</td><td>+1 (555) 013 8890</td></tr>
          <tr><td>Email</td><td>stay@aureliahotel.com</td></tr>
          <tr><td>Address</td><td>14 Magnolia Court, Rivermill</td></tr>
          <tr><td>Check-in</td><td>From 3:00 PM</td></tr>
          <tr><td>Check-out</td><td>Until 11:00 AM</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
