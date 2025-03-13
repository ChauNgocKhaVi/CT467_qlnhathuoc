<?php
require_once __DIR__ . '/../src/bootstrap.php';

if (!isset($_GET['MaKH'])) {
    die("Thiếu mã khách hàng!");
}

$maKH = $_GET['MaKH'];

// Kiểm tra xem khách hàng có tồn tại không
$stmt = $pdo->prepare("SELECT * FROM KhachHang WHERE MaKH = :maKH");
$stmt->execute(['maKH' => $maKH]);
$khachHang = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$khachHang) {
    die("Khách hàng không tồn tại!");
}

// Xóa khách hàng
$stmt = $pdo->prepare("DELETE FROM KhachHang WHERE MaKH = :maKH");
$stmt->execute(['maKH' => $maKH]);

// Chuyển hướng về danh sách khách hàng với thông báo
header("Location: index.php?successKH=Xóa thành công");
exit();