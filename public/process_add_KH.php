<?php
require_once __DIR__ . '/../src/bootstrap.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenKH = $_POST['TenKH'] ?? '';
    $soDienThoai = $_POST['SoDienThoai'] ?? '';
    $diaChi = $_POST['DiaChi'] ?? '';

    if (!empty($tenKH) && !empty($soDienThoai) && !empty($diaChi)) {
        $stmt = $pdo->prepare("INSERT INTO KhachHang (TenKH, SoDienThoai, DiaChi) VALUES (:tenKH, :soDienThoai, :diaChi)");
        $stmt->execute([
            'tenKH' => $tenKH,
            'soDienThoai' => $soDienThoai,
            'diaChi' => $diaChi
        ]);

        // Chuyển hướng về trang danh sách với thông báo thành công
        header("Location: index.php?successKH=Khách hàng đã được thêm thành công");
        exit();
    } else {
        // Chuyển hướng ngược lại nếu thiếu thông tin
        header("Location: add_khachhang.php?errorKH=Vui lòng nhập đầy đủ thông tin!");
        exit();
    }
}
?>