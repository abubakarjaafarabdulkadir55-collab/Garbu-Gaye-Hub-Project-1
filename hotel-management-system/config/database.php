<?php
/**
 * Database connection (PDO)
 * Update these four values to match your local / server MySQL setup.
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'aurelia_hotel');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('<div style="font-family:sans-serif;padding:40px;color:#6E2E2E">
        <h2>Database connection failed</h2>
        <p>Please check <code>config/database.php</code> and make sure the
        <strong>aurelia_hotel</strong> database has been imported from
        <code>sql/schema.sql</code>.</p>
        <p style="color:#999">' . htmlspecialchars($e->getMessage()) . '</p></div>');
}
