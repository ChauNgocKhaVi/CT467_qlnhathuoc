<?php
require_once __DIR__ . '/../src/bootstrap.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenHang = $_POST['tenHang'] ?? '';
    $quocGia = $_POST['quocGia'] ?? '';

    if (!empty($tenHang) && !empty($quocGia)) {
        $stmt = $pdo->prepare("INSERT INTO HangSX (TenHang, QuocGia) VALUES (:tenHang, :quocGia)");
        $stmt->execute([
            'tenHang' => $tenHang,
            'quocGia' => $quocGia
        ]);

        // Chuyển hướng về trang danh sách với thông báo thành công
        header("Location: index.php?successHangSX=Hãng sản xuất đã được thêm thành công");
        exit();
    } else {
        // Chuyển hướng ngược lại nếu thiếu thông tin
        header("Location: add_hangsx.php?errorHangSX=Vui lòng nhập đầy đủ thông tin!");
        exit();
    }
}