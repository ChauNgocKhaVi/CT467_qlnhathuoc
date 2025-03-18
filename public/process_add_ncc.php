<?php
require_once __DIR__ . '/../src/bootstrap.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenNCC = $_POST['tenNCC'] ?? '';
    $soDienThoai = trim($_POST['soDienThoai'] ?? ''); // Loại bỏ khoảng trắng ở đầu và cuối số điện thoại

    // Kiểm tra tên nhà cung cấp có bị trùng không
    $stmt = $pdo->prepare("SELECT * FROM NhaCungCap WHERE TenNCC = :tenNCC");
    $stmt->execute(['tenNCC' => $tenNCC]);
    $existingTenNCC = $stmt->fetch();

    // Kiểm tra số điện thoại có bị trùng không
    $stmt = $pdo->prepare("SELECT * FROM NhaCungCap WHERE SoDienThoai = :soDienThoai");
    $stmt->execute(['soDienThoai' => $soDienThoai]);
    $existingSoDienThoai = $stmt->fetch();

    if ($existingTenNCC) {
        // Nếu tên nhà cung cấp đã tồn tại
        header("Location: add.php?id=formNCC&&errorNCC=Tên nhà cung cấp đã tồn tại!");
        exit();
    } elseif ($existingSoDienThoai) {
        // Nếu số điện thoại đã tồn tại
        header("Location: add.php?id=formNCC&&errorNCC=Số điện thoại này đã được sử dụng!");
        exit();
    } elseif (!empty($tenNCC) && !empty($soDienThoai)) {
        // Nếu không có lỗi, thực hiện thêm nhà cung cấp vào cơ sở dữ liệu
        $stmt = $pdo->prepare("INSERT INTO NhaCungCap (TenNCC, SoDienThoai) VALUES (:tenNCC, :soDienThoai)");
        $stmt->execute([
            'tenNCC' => $tenNCC,
            'soDienThoai' => $soDienThoai
        ]);

        // Chuyển hướng về trang danh sách với thông báo thành công
        header("Location: index.php?successNCC=Nhà cung cấp đã được thêm thành công");
        exit();
    } else {
        // Nếu không nhập đầy đủ thông tin
        header("Location: add.php?id=formNCC&&errorNCC=Vui lòng nhập đầy đủ thông tin!");
        exit();
    }
}
?>