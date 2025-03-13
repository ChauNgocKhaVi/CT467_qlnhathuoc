<?php
require_once __DIR__ . '/../src/bootstrap.php';

if (!isset($_GET['MaHD'])) {
    die("Thiếu mã hóa đơn!");
}

$maHD = $_GET['MaHD'];

// Kiểm tra xem hóa đơn có tồn tại không
$stmt = $pdo->prepare("SELECT * FROM HoaDon WHERE MaHD = :maHD");
$stmt->execute(['maHD' => $maHD]);
$hoaDon = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$hoaDon) {
    die("Hóa đơn không tồn tại!");
}

// Xóa hóa đơn
$stmt = $pdo->prepare("DELETE FROM HoaDon WHERE MaHD = :maHD");
$stmt->execute(['maHD' => $maHD]);

// Chuyển hướng về danh sách hóa đơn với thông báo
header("Location: index.php?successHD=Xóa hóa đơn thành công");
exit();