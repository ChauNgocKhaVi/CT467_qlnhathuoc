<?php
require_once __DIR__ . '/../src/bootstrap.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenLoai = $_POST['tenLoai'] ?? '';
    $donViTinh = $_POST['donViTinh'] ?? '';

    if (!empty($tenLoai) && !empty($donViTinh)) {
        $stmt = $pdo->prepare("INSERT INTO LoaiThuoc (TenLoai, DonViTinh) VALUES (:tenLoai, :donViTinh)");
        $stmt->execute([
            'tenLoai' => $tenLoai,
            'donViTinh' => $donViTinh
        ]);

        // Chuyển hướng về trang danh sách
        header("Location: index.php?successLoai=Loại thuốc đã được thêm thành công");
        exit();
    } else {
        // Chuyển hướng ngược lại nếu thiếu thông tin
        header("Location: add.php?errorLoai=Vui lòng nhập đầy đủ thông tin!");
        exit();
    }
}
?>