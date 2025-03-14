<?php
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/functions.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['MaThuoc'])) {
    $result = xoaThuoc($pdo, $data['MaThuoc']);
    echo json_encode(["success" => $result]);
    exit();
}

if (isset($data['MaND'])) {
    $result = xoaNhanVien($pdo, $data['MaND']);
    echo json_encode(["success" => $result]);
    exit();
}

// Trường hợp không có dữ liệu hợp lệ
echo json_encode(["success" => false, "message" => "Dữ liệu không hợp lệ!"]);
exit();
