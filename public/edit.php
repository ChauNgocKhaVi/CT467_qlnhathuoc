<?php
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/functions.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra dữ liệu gửi đến
error_log(print_r($data, true));

if (isset($data['MaThuoc'])) {
    $result = suaThuoc($pdo, $data);
    echo json_encode(["success" => $result]);
    exit();
}

if (isset($data['MaND'])) {
    $result = suaNhanVien($pdo, $data);
    echo json_encode(["success" => $result]);
    exit();
}

// Trường hợp không có dữ liệu hợp lệ
echo json_encode(["success" => false, "message" => "Dữ liệu không hợp lệ!"]);
exit();
