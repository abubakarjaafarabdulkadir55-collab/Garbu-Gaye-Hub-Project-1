<?php
require_once __DIR__ . '/config/config.php';
unset($_SESSION['user_id'], $_SESSION['user_name']);
setFlash('info', 'You have been logged out.');
redirect(BASE_URL . 'index.php');
