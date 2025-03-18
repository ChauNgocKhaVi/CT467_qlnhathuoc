<?php
require_once __DIR__ . '/../src/bootstrap.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenLoai = trim($_POST['tenLoai'] ?? '');  // Loại bỏ khoảng trắng thừa
    $donViTinh = trim($_POST['donViTinh'] ?? ''); // Loại bỏ khoảng trắng thừa

    // Kiểm tra tên loại thuốc có bị trùng không
    $stmt = $pdo->prepare("SELECT * FROM LoaiThuoc WHERE TenLoai = :tenLoai");
    $stmt->execute(['tenLoai' => $tenLoai]);
    $existingTenLoai = $stmt->fetch();

    if ($existingTenLoai) {
        // Nếu tên loại thuốc đã tồn tại
        header("Location: add.php?id=formLoai&&errorLoai=Tên loại thuốc đã tồn tại!");
        exit();
    } elseif (!empty($tenLoai) && !empty($donViTinh)) {
        // Nếu không có lỗi, thực hiện thêm loại thuốc vào cơ sở dữ liệu
        $stmt = $pdo->prepare("INSERT INTO LoaiThuoc (TenLoai, DonViTinh) VALUES (:tenLoai, :donViTinh)");
        $stmt->execute([
            'tenLoai' => $tenLoai,
            'donViTinh' => $donViTinh
        ]);

        // Chuyển hướng về trang danh sách với thông báo thành công
        header("Location: index.php?successLoai=Loại thuốc đã được thêm thành công");
        exit();
    } else {
        // Nếu không nhập đầy đủ thông tin
        header("Location: add.php?id=formLoai&&errorLoai=Vui lòng nhập đầy đủ thông tin!");
        exit();
    }
}
?>