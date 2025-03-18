<?php
require_once __DIR__ . '/../src/bootstrap.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenKH = $_POST['TenKH'] ?? '';
    $soDienThoai = trim($_POST['SoDienThoai'] ?? '');
    $diaChi = $_POST['DiaChi'] ?? '';

    // Kiểm tra số điện thoại hợp lệ (chỉ chấp nhận số từ 10 đến 15 chữ số)
    if (!preg_match('/^[0-9]{10,15}$/', $soDienThoai)) {
        header("Location: add.php?id=formKH&&errorKH=Số điện thoại không hợp lệ!");
        exit();
    }

    // Kiểm tra xem số điện thoại đã tồn tại trong bảng KhachHang chưa
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM KhachHang WHERE SoDienThoai = :soDienThoai");
    $stmt->execute(['soDienThoai' => $soDienThoai]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        // Nếu số điện thoại đã tồn tại, thông báo lỗi
        header("Location: add.php?id=formKH&&errorKH=Số điện thoại đã tồn tại!");
        exit();
    }

    // Nếu tất cả các kiểm tra đều hợp lệ, thực hiện thêm khách hàng vào cơ sở dữ liệu
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
        header("Location: add.php?id=formKH&&errorKH=Vui lòng nhập đầy đủ thông tin!");
        exit();
    }
}
?>