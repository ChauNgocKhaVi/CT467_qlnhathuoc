<?php
require_once __DIR__ . '/../src/bootstrap.php';

$maNCC = $_GET['MaNCC'] ?? '';

// Lấy thông tin nhà cung cấp hiện tại
$stmt = $pdo->prepare("SELECT * FROM NhaCungCap WHERE MaNCC = :maNCC");
$stmt->execute(['maNCC' => $maNCC]);
$ncc = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ncc) {
    die("Nhà cung cấp không tồn tại!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenNCC = $_POST['tenNCC'] ?? '';
    $soDienThoai = $_POST['SoDienThoai'] ?? '';

    if (!empty($tenNCC) && !empty($soDienThoai)) {
        $stmt = $pdo->prepare("UPDATE NhaCungCap SET TenNCC = :tenNCC, SoDienThoai = :soDienThoai WHERE MaNCC = :maNCC");
        $stmt->execute([
            'tenNCC' => $tenNCC,
            'soDienThoai' => $soDienThoai,
            'maNCC' => $maNCC
        ]);
        header("Location: index.php?successNCC=Cập nhật thành công");
        exit();
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    }
}
?>

<?php include __DIR__ . '/../src/partials/head.php'; ?>
<?php include __DIR__ . '/../src/partials/header.php'; ?>

<div class="container">
    <h2 class="mt-3">Sửa Nhà Cung Cấp</h2>
    <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Tên Nhà Cung Cấp</label>
            <input type="text" name="tenNCC" class="form-control"
                value="<?php echo htmlspecialchars($ncc['TenNCC']); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Số Điện Thoại</label>
            <input type="text" name="SoDienThoai" class="form-control"
                value="<?php echo htmlspecialchars($ncc['SoDienThoai']); ?>">
        </div>
        <div class="d-flex justify-content-center gap-3">
            <a href="index.php" class="btn btn-danger w-50 text-center">Hủy</a>
            <button type="submit" class="btn btn-primary w-50">Lưu</button>
        </div>
    </form>
</div>