<?php
require_once __DIR__ . '/../config/config.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id         = (int)($_POST['id'] ?? 0);
    $roomNumber = trim($_POST['room_number'] ?? '');
    $typeId     = (int)($_POST['room_type_id'] ?? 0);
    $floor      = (int)($_POST['floor'] ?? 1);
    $status     = $_POST['status'] ?? 'available';
    $image      = trim($_POST['image_url'] ?? '');

    if ($roomNumber === '' || $typeId === 0) {
        setFlash('error', 'Room number and type are required.');
        redirect(BASE_URL . 'admin/rooms.php');
    }

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE rooms SET room_number=?, room_type_id=?, floor=?, status=?, image_url=? WHERE id=?");
        $stmt->execute([$roomNumber, $typeId, $floor, $status, $image, $id]);
        setFlash('success', 'Room ' . $roomNumber . ' updated.');
    } else {
        $stmt = $pdo->prepare("INSERT INTO rooms (room_number, room_type_id, floor, status, image_url) VALUES (?,?,?,?,?)");
        $stmt->execute([$roomNumber, $typeId, $floor, $status, $image]);
        setFlash('success', 'Room ' . $roomNumber . ' added.');
    }
}

redirect(BASE_URL . 'admin/rooms.php');
