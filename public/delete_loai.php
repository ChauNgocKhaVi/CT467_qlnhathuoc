<?php
require_once __DIR__ . '/../src/bootstrap.php';

if (!isset($_GET['MaLoai'])) {
    die("Thiếu mã loại!");
}

$maLoai = $_GET['MaLoai'];

// Kiểm tra xem loại thuốc có tồn tại không
$stmt = $pdo->prepare("SELECT * FROM LoaiThuoc WHERE MaLoai = :maLoai");
$stmt->execute(['maLoai' => $maLoai]);
$loai = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$loai) {
    die("Loại thuốc không tồn tại!");
}

// Xóa loại thuốc
$stmt = $pdo->prepare("DELETE FROM LoaiThuoc WHERE MaLoai = :maLoai");
$stmt->execute(['maLoai' => $maLoai]);

// Chuyển hướng về danh sách loại thuốc với thông báo
header("Location: index.php?successLoai=Xóa thành công");
exit();