<?php
require_once __DIR__ . '/../src/bootstrap.php';

$maHD = $_GET['MaHD'] ?? null;
if (!$maHD) {
    die("Mã hóa đơn không hợp lệ.");
}

// Lấy danh sách chi tiết hóa đơn
$stmt = $pdo->prepare("
    SELECT cthd.MaThuoc, t.TenThuoc, cthd.SoLuongBan, cthd.GiaBan, 
           (cthd.SoLuongBan * cthd.GiaBan) AS ThanhTien
    FROM ChiTietHoaDon cthd
    JOIN Thuoc t ON cthd.MaThuoc = t.MaThuoc
    WHERE cthd.MaHD = :maHD
");
$stmt->execute(['maHD' => $maHD]);
$chiTietHD = $stmt->fetchAll();

// Tạo bảng HTML để trả về AJAX
if ($chiTietHD) {
    echo '<table class="table table-bordered mt-2">';
    echo '<thead><tr>
            <th>Mã Thuốc</th>
            <th>Tên Thuốc</th>
            <th>Số Lượng</th>
            <th>Giá Bán</th>
            <th>Thành Tiền</th>
          </tr></thead><tbody>';
    foreach ($chiTietHD as $ct) {
        echo '<tr>
                <td>' . htmlspecialchars($ct['MaThuoc']) . '</td>
                <td>' . htmlspecialchars($ct['TenThuoc']) . '</td>
                <td>' . htmlspecialchars($ct['SoLuongBan']) . '</td>
                <td>' . number_format($ct['GiaBan'], 2) . ' VNĐ</td>
                <td>' . number_format($ct['ThanhTien'], 2) . ' VNĐ</td>
              </tr>';
    }
    echo '</tbody></table>';
} else {
    echo '<p class="text-danger">Không có chi tiết hóa đơn nào.</p>';
}
?>