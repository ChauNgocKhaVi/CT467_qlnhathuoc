<?php
require_once __DIR__ . '/../src/bootstrap.php';

// Kiểm tra nếu có MaLoai được truyền vào
if (isset($_GET['MaLoai'])) {
    $maLoai = $_GET['MaLoai'];

    // Lấy thông tin loại thuốc cần sửa
    $stmt = $pdo->prepare("SELECT * FROM LoaiThuoc WHERE MaLoai = :maLoai");
    $stmt->execute(['maLoai' => $maLoai]);
    $loai = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$loai) {
        die("Loại thuốc không tồn tại!");
    }
} else {
    die("Thiếu mã loại!");
}

// Xử lý cập nhật khi nhấn nút "Lưu"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenLoai = $_POST['TenLoai'];
    $donViTinh = $_POST['DonViTinh'];

    $stmt = $pdo->prepare("UPDATE LoaiThuoc SET TenLoai = :tenLoai, DonViTinh = :donViTinh WHERE MaLoai = :maLoai");
    $stmt->execute([
        'tenLoai' => $tenLoai,
        'donViTinh' => $donViTinh,
        'maLoai' => $maLoai
    ]);

    // Thêm thông báo cập nhật thành công
    header("Location: index.php?successLoai=Loại thuốc đã được cập nhật thành công");
    exit();
}

include __DIR__ . '/../src/partials/head.php';
include __DIR__ . '/../src/partials/header.php';
?>

<div class="container d-flex justify-content-center align-items-center">
    <div class="col-md-6">
        <h2 class="text-center mb-4">Chỉnh sửa loại thuốc</h2>

        <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="TenLoai" class="form-label">Tên Loại</label>
                <input type="text" class="form-control" id="TenLoai" name="TenLoai"
                    value="<?php echo htmlspecialchars($loai['TenLoai']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="DonViTinh" class="form-label">Đơn vị tính</label>
                <input type="text" class="form-control" id="DonViTinh" name="DonViTinh"
                    value="<?php echo htmlspecialchars($loai['DonViTinh']); ?>" required>
            </div>
            <div class="d-flex justify-content-center gap-3">
                <a href="index.php" class="btn btn-danger w-50 text-center">Hủy</a>
                <button type="submit" class="btn btn-primary w-50">Lưu</button>
            </div>
        </form>
    </div>
</div>