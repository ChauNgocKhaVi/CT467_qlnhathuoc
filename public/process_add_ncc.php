<?php
require_once __DIR__ . '/../src/bootstrap.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenNCC = $_POST['tenNCC'] ?? '';
    $soDienThoai = $_POST['soDienThoai'] ?? '';

    if (!empty($tenNCC) && !empty($soDienThoai)) {
        $stmt = $pdo->prepare("INSERT INTO NhaCungCap (TenNCC, SoDienThoai) VALUES (:tenNCC, :soDienThoai)");
        $stmt->execute([
            'tenNCC' => $tenNCC,
            'soDienThoai' => $soDienThoai
        ]);

        // Chuyển hướng về trang danh sách với thông báo thành công
        header("Location: index.php?successNCC=Nhà cung cấp đã được thêm thành công");
        exit();
    } else {
        // Chuyển hướng lại trang thêm với lỗi
        header("Location: add.php?errorNCC=Vui lòng nhập đầy đủ thông tin!");
        exit();
    }
}
?>