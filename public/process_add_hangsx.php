<?php
require_once __DIR__ . '/../src/bootstrap.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenHang = trim($_POST['tenHang'] ?? '');
    $quocGia = trim($_POST['quocGia'] ?? '');

    // Kiểm tra tên hãng có bị trùng không
    $stmt = $pdo->prepare("SELECT * FROM HangSX WHERE TenHang = :tenHang");
    $stmt->execute(['tenHang' => $tenHang]);
    $existingTenHang = $stmt->fetch();

    if ($existingTenHang) {
        // Nếu tên hãng đã tồn tại
        header("Location: add.php?id=formHangSX&&errorHSX=Tên hãng sản xuất đã tồn tại!");
        exit();
    } elseif (!empty($tenHang) && !empty($quocGia)) {
        // Nếu không có lỗi, thực hiện thêm hãng sản xuất vào cơ sở dữ liệu
        $stmt = $pdo->prepare("INSERT INTO HangSX (TenHang, QuocGia) VALUES (:tenHang, :quocGia)");
        $stmt->execute([
            'tenHang' => $tenHang,
            'quocGia' => $quocGia
        ]);

        // Chuyển hướng về trang danh sách với thông báo thành công
        header("Location: index.php?successHangSX=Hãng sản xuất đã được thêm thành công");
        exit();
    } else {
        // Nếu không nhập đầy đủ thông tin
        header("Location: add.php?id=formHangSX&&errorHSX=Vui lòng nhập đầy đủ thông tin!");
        exit();
    }
}
?>