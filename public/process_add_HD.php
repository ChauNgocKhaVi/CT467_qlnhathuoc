<?php
require_once __DIR__ . '/../src/bootstrap.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maKH = $_POST['MaKH'];
    $ngayLap = $_POST['NgayLap'];
    $tongTien = 0;

    $thuocList = $_POST['MaThuoc'] ?? [];
    $soLuongList = $_POST['SoLuongBan'] ?? [];
    $giaBanList = $_POST['GiaBan'] ?? [];

    $pdo->beginTransaction(); // Bắt đầu transaction

    try {
        // Nếu khách hàng mới, thêm vào bảng KhachHang
        if ($maKH == "new") {
            $tenKH = trim($_POST['TenKH']);
            $soDienThoai = trim($_POST['SoDienThoai']);
            $diaChi = trim($_POST['DiaChi']);

            if (empty($tenKH) || empty($soDienThoai) || empty($diaChi)) {
                throw new Exception("Thông tin khách hàng không đầy đủ.");
            }

            $stmt = $pdo->prepare("INSERT INTO KhachHang (TenKH, SoDienThoai, DiaChi) VALUES (?, ?, ?)");
            $stmt->execute([$tenKH, $soDienThoai, $diaChi]);
            $maKH = $pdo->lastInsertId();
        } else {
            $maKH = intval($maKH);
        }

        // Thêm hóa đơn vào bảng HoaDon
        $stmt = $pdo->prepare("INSERT INTO HoaDon (MaKH, NgayLap, TongTien) VALUES (?, ?, ?)");
        $stmt->execute([$maKH, $ngayLap, $tongTien]);
        $maHD = $pdo->lastInsertId();

        // Thêm chi tiết hóa đơn và tính tổng tiền
        $stmt = $pdo->prepare("INSERT INTO ChiTietHoaDon (MaHD, MaThuoc, SoLuongBan, GiaBan) VALUES (?, ?, ?, ?)");
        
        foreach ($thuocList as $index => $maThuoc) {
            $maThuoc = intval($maThuoc);
            $soLuong = isset($soLuongList[$index]) ? intval($soLuongList[$index]) : 0;
            $giaBan = isset($giaBanList[$index]) ? floatval($giaBanList[$index]) : 0;

            if ($maThuoc > 0 && $soLuong > 0 && $giaBan >= 0) {
                $tongTien += $soLuong * $giaBan;
                $stmt->execute([$maHD, $maThuoc, $soLuong, $giaBan]);
            }
        }

        // Cập nhật tổng tiền cho hóa đơn
        $stmt = $pdo->prepare("UPDATE HoaDon SET TongTien = ? WHERE MaHD = ?");
        $stmt->execute([$tongTien, $maHD]);

        $pdo->commit(); // Xác nhận transaction

        header("Location: index.php?success=1");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack(); // Hủy bỏ transaction nếu có lỗi
        die("Lỗi: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}
?>