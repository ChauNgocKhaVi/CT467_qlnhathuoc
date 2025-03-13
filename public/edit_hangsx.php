<?php
require_once __DIR__ . '/../src/bootstrap.php';

// Kiểm tra nếu có MaHangSX được truyền vào
if (isset($_GET['MaHangSX'])) {
    $maHangSX = $_GET['MaHangSX'];

    // Lấy thông tin hãng sản xuất cần sửa
    $stmt = $pdo->prepare("SELECT * FROM HangSX WHERE MaHangSX = :maHangSX");
    $stmt->execute(['maHangSX' => $maHangSX]);
    $hang = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$hang) {
        die("Hãng sản xuất không tồn tại!");
    }
} else {
    die("Thiếu mã hãng sản xuất!");
}

// Xử lý cập nhật khi nhấn nút "Lưu"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenHang = $_POST['TenHang'];
    $quocGia = $_POST['QuocGia'];

    $stmt = $pdo->prepare("UPDATE HangSX SET TenHang = :tenHang, QuocGia = :quocGia WHERE MaHangSX = :maHangSX");
    $stmt->execute([
        'tenHang' => $tenHang,
        'quocGia' => $quocGia,
        'maHangSX' => $maHangSX
    ]);

    // Thêm thông báo cập nhật thành công
    header("Location: index.php?successHangSX=Hãng sản xuất đã được cập nhật thành công");
    exit();
}

include __DIR__ . '/../src/partials/head.php';
include __DIR__ . '/../src/partials/header.php';
?>

<div class="container d-flex justify-content-center align-items-center">
    <div class="col-md-6">
        <h2 class="text-center mb-4">Chỉnh sửa hãng sản xuất</h2>

        <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="TenHang" class="form-label">Tên Hãng</label>
                <input type="text" class="form-control" id="TenHang" name="TenHang"
                    value="<?php echo htmlspecialchars($hang['TenHang']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="QuocGia" class="form-label">Quốc Gia</label>
                <input type="text" class="form-control" id="QuocGia" name="QuocGia"
                    value="<?php echo htmlspecialchars($hang['QuocGia']); ?>" required>
            </div>
            <div class="d-flex justify-content-center gap-3">
                <a href="index.php" class="btn btn-danger w-50 text-center">Hủy</a>
                <button type="submit" class="btn btn-primary w-50">Lưu</button>
            </div>
        </form>
    </div>
</div>