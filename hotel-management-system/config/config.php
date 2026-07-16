<?php
/**
 * Global site configuration & bootstrap.
 * Every page includes this file first.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('SITE_NAME', 'Aurelia Hotel');
define('SITE_TAGLINE', 'A quiet arrival, every time.');
define('CURRENCY', '$');

// Base URL — auto-detected so this works whether the project sits at your
// server's root (http://localhost/) or in a subfolder
// (http://localhost/hotel-management-system/). You should NOT need to edit
// this by hand. If detection ever fails on unusual hosting, you can force
// it manually instead, e.g.: define('BASE_URL', '/hotel-management-system/');
$docRoot     = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
$projectRoot = rtrim(str_replace('\\', '/', dirname(__DIR__)), '/');

if ($docRoot !== '' && strpos($projectRoot, $docRoot) === 0) {
    $basePath = substr($projectRoot, strlen($docRoot));
} else {
    $basePath = '';
}
define('BASE_URL', $basePath === '' ? '/' : $basePath . '/');

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../includes/functions.php';
