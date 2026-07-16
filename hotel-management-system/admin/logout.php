<?php
require_once __DIR__ . '/../config/config.php';
unset($_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_role']);
redirect(BASE_URL . 'admin/login.php');
