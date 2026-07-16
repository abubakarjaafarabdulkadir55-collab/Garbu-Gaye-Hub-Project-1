<?php
/** Escape output safely */
function h($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/** Is a guest logged in? */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/** Is an admin logged in? */
function isAdmin() {
    return isset($_SESSION['admin_id']);
}

/** Redirect helper */
function redirect($path) {
    header("Location: " . $path);
    exit;
}

/** Force guest login before continuing */
function requireLogin() {
    if (!isLoggedIn()) {
        redirect(BASE_URL . 'login.php');
    }
}

/** Force admin login before continuing */
function requireAdmin() {
    if (!isAdmin()) {
        redirect(BASE_URL . 'admin/login.php');
    }
}

/** Generate a short, human friendly booking code, e.g. AUR-7F3K9Q */
function generateBookingCode() {
    return 'AUR-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
}

/** Format money consistently */
function money($amount) {
    return CURRENCY . number_format((float)$amount, 2);
}

/** Format a date for display, e.g. 12 Jul 2026 */
function niceDate($date) {
    return date('d M Y', strtotime($date));
}

/** Flash message helpers (session based) */
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}
function getFlash() {
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/** Check if a specific room is free for the given date range */
function isRoomAvailable(PDO $pdo, $roomId, $checkIn, $checkOut, $excludeBookingId = null) {
    $sql = "SELECT COUNT(*) FROM bookings
            WHERE room_id = :room_id
              AND status IN ('pending','confirmed','checked_in')
              AND check_in < :check_out
              AND check_out > :check_in";
    if ($excludeBookingId) {
        $sql .= " AND id != :exclude_id";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':room_id', $roomId);
    $stmt->bindValue(':check_in', $checkIn);
    $stmt->bindValue(':check_out', $checkOut);
    if ($excludeBookingId) {
        $stmt->bindValue(':exclude_id', $excludeBookingId);
    }
    $stmt->execute();
    return $stmt->fetchColumn() == 0;
}
