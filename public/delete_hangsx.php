<?php
require_once __DIR__ . '/../src/bootstrap.php';

if (!isset($_GET['MaHangSX'])) {
    die("Thiếu mã hãng sản xuất!");
}

$maHangSX = $_GET['MaHangSX'];

// Kiểm tra xem hãng sản xuất có tồn tại không
$stmt = $pdo->prepare("SELECT * FROM HangSX WHERE MaHangSX = :maHangSX");
$stmt->execute(['maHangSX' => $maHangSX]);
$hsx = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$hsx) {
    die("Hãng sản xuất không tồn tại!");
}

// Xóa hãng sản xuất
$stmt = $pdo->prepare("DELETE FROM HangSX WHERE MaHangSX = :maHangSX");
$stmt->execute(['maHangSX' => $maHangSX]);

// Chuyển hướng về danh sách hãng sản xuất với thông báo
header("Location: index.php?successHangSX=Xóa thành công");
exit();