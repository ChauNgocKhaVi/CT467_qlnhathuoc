<?php
require_once __DIR__ . '/../src/bootstrap.php';

$maHD = $_GET['MaHD'] ?? 0;

if ($maHD) {
    $stmt = $pdo->prepare("SELECT ct.MaThuoc, t.TenThuoc, ct.SoLuongBan, ct.GiaBan, (ct.SoLuongBan * ct.GiaBan) AS ThanhTien
                        FROM ChiTietHoaDon ct
                        JOIN Thuoc t ON ct.MaThuoc = t.MaThuoc
                        WHERE ct.MaHD = ?");
    $stmt->execute([$maHD]);
    $chiTietHD = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($chiTietHD);
} else {
    echo json_encode([]);
}
?>