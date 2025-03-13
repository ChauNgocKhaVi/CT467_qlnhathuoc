<?php
require_once __DIR__ . '/../src/bootstrap.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("UPDATE ThongBao SET TrangThai = 'da_xem' WHERE TrangThai = 'chua_xem'");
    $stmt->execute();

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
