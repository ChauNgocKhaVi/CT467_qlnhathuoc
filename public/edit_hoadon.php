<?php
require_once __DIR__ . '/../src/bootstrap.php';

// Kiểm tra nếu có MaHD được truyền vào
if (isset($_GET['MaHD'])) {
    $maHD = $_GET['MaHD'];

    // Lấy thông tin hóa đơn cần sửa
    $stmt = $pdo->prepare("SELECT * FROM HoaDon WHERE MaHD = :maHD");
    $stmt->execute(['maHD' => $maHD]);
    $hoaDon = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$hoaDon) {
        die("Hóa đơn không tồn tại!");
    }
} else {
    die("Thiếu mã hóa đơn!");
}

// Xử lý cập nhật khi nhấn nút "Lưu"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maKH = !empty($_POST['MaKH']) ? $_POST['MaKH'] : NULL; // Cho phép NULL do ON DELETE SET NULL
    $ngayLap = $_POST['NgayLap'];
    $tongTien = $_POST['TongTien'];

    $stmt = $pdo->prepare("UPDATE HoaDon SET MaKH = :maKH, NgayLap = :ngayLap, TongTien = :tongTien WHERE MaHD = :maHD");
    $stmt->execute([
        'maKH' => $maKH,
        'ngayLap' => $ngayLap,
        'tongTien' => $tongTien,
        'maHD' => $maHD
    ]);

    // Thêm thông báo cập nhật thành công
    header("Location: index.php?successHD=Hóa đơn đã được cập nhật thành công");
    exit();
}

include __DIR__ . '/../src/partials/head.php';
include __DIR__ . '/../src/partials/header.php';
?>

<div class="container d-flex justify-content-center align-items-center">
    <div class="col-md-6">
        <h2 class="text-center mb-4">Chỉnh sửa hóa đơn</h2>

        <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="MaKH" class="form-label">Mã Khách Hàng</label>
                <input type="number" class="form-control" id="MaKH" name="MaKH"
                    value="<?php echo htmlspecialchars($hoaDon['MaKH'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="NgayLap" class="form-label">Ngày Lập</label>
                <input type="date" class="form-control" id="NgayLap" name="NgayLap"
                    value="<?php echo htmlspecialchars($hoaDon['NgayLap']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="TongTien" class="form-label">Tổng Tiền</label>
                <input type="number" step="0.01" class="form-control" id="TongTien" name="TongTien"
                    value="<?php echo htmlspecialchars($hoaDon['TongTien']); ?>" required>
            </div>
            <div class="d-flex justify-content-center gap-3">
                <a href="index.php" class="btn btn-danger w-50 text-center">Hủy</a>
                <button type="submit" class="btn btn-primary w-50">Lưu</button>
            </div>
        </form>
    </div>
</div>