<?php
require_once __DIR__ . '/../src/bootstrap.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo->beginTransaction(); // Bắt đầu transaction

        $maKH = $_POST['MaKH'] ?? '';
        $tenKH = $_POST['TenKH'] ?? '';
        $soDienThoai = $_POST['SoDienThoai'] ?? '';
        $diaChi = $_POST['DiaChi'] ?? '';
        $ngayLap = $_POST['NgayLap'] ?? date('Y-m-d');
        $tongTien = $_POST['TongTien'] ?? 0;

        // Nếu khách hàng chọn "Thêm mới", cần thêm vào CSDL
        if ($maKH === "new" && !empty($tenKH) && !empty($soDienThoai) && !empty($diaChi)) {
            $stmt = $pdo->prepare("INSERT INTO KhachHang (TenKH, SoDienThoai, DiaChi) VALUES (:tenKH, :soDienThoai, :diaChi)");
            $stmt->execute([
                'tenKH' => $tenKH,
                'soDienThoai' => $soDienThoai,
                'diaChi' => $diaChi
            ]);
            $maKH = $pdo->lastInsertId(); // Lấy ID khách hàng vừa thêm
        }

        // Thêm hóa đơn vào CSDL
        $stmt = $pdo->prepare("INSERT INTO HoaDon (MaKH, NgayLap, TongTien) VALUES (:maKH, :ngayLap, :tongTien)");
        $stmt->execute([
            'maKH' => !empty($maKH) ? $maKH : null,
            'ngayLap' => $ngayLap,
            'tongTien' => $tongTien
        ]);

        $pdo->commit(); // Xác nhận transaction

        // Chuyển hướng với thông báo thành công
        header("Location: index.php?successHD=Hóa đơn đã được tạo thành công");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack(); // Hoàn tác nếu có lỗi
        header("Location: add_hoadon.php?errorHD=Lỗi khi tạo hóa đơn: " . $e->getMessage());
        exit();
    }
}
?>