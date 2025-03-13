<?php
require_once __DIR__ . '/../src/bootstrap.php';

// Kiểm tra nếu có MaKH được truyền vào
if (isset($_GET['MaKH'])) {
    $maKH = $_GET['MaKH'];

    // Lấy thông tin khách hàng cần sửa
    $stmt = $pdo->prepare("SELECT * FROM KhachHang WHERE MaKH = :maKH");
    $stmt->execute(['maKH' => $maKH]);
    $khachHang = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$khachHang) {
        die("Khách hàng không tồn tại!");
    }
} else {
    die("Thiếu mã khách hàng!");
}

// Xử lý cập nhật khi nhấn nút "Lưu"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenKH = $_POST['TenKH'];
    $soDienThoai = $_POST['SoDienThoai'];
    $diaChi = $_POST['DiaChi'];

    $stmt = $pdo->prepare("UPDATE KhachHang SET TenKH = :tenKH, SoDienThoai = :soDienThoai, DiaChi = :diaChi WHERE MaKH = :maKH");
    $stmt->execute([
        'tenKH' => $tenKH,
        'soDienThoai' => $soDienThoai,
        'diaChi' => $diaChi,
        'maKH' => $maKH
    ]);

    // Chuyển hướng với thông báo thành công
    header("Location: index.php?successKH=Khách hàng đã được cập nhật thành công");
    exit();
}

include __DIR__ . '/../src/partials/head.php';
include __DIR__ . '/../src/partials/header.php';
?>

<div class="container d-flex justify-content-center align-items-center">
    <div class="col-md-6">
        <h2 class="text-center mb-4">Chỉnh sửa khách hàng</h2>

        <?php if (isset($_GET['successKH'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['successKH']); ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="TenKH" class="form-label">Tên Khách Hàng</label>
                <input type="text" class="form-control" id="TenKH" name="TenKH"
                    value="<?php echo htmlspecialchars($khachHang['TenKH']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="SoDienThoai" class="form-label">Số Điện Thoại</label>
                <input type="text" class="form-control" id="SoDienThoai" name="SoDienThoai"
                    value="<?php echo htmlspecialchars($khachHang['SoDienThoai']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="DiaChi" class="form-label">Địa Chỉ</label>
                <textarea class="form-control" id="DiaChi" name="DiaChi"
                    required><?php echo htmlspecialchars($khachHang['DiaChi']); ?></textarea>
            </div>
            <div class="d-flex justify-content-center gap-3">
                <a href="index.php" class="btn btn-danger w-50 text-center">Hủy</a>
                <button type="submit" class="btn btn-primary w-50">Lưu</button>
            </div>
        </form>
    </div>
</div>