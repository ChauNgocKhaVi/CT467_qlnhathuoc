<?php
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/functions.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hoTen = trim($_POST['hoten']);
    $tenDangNhap = trim($_POST['tendangnhap']);
    $email = trim($_POST['email']);
    $soDienThoai = trim($_POST['sodienthoai']);
    $matKhau = trim($_POST['password']);
    $xacNhanMatKhau = trim($_POST['confirmPassword']);
    $vaiTro = 'user'; // Mặc định đăng ký với vai trò người dùng

    // Kiểm tra dữ liệu đầu vào
    $errors = validateInput($tenDangNhap, $email, $matKhau, $xacNhanMatKhau, $soDienThoai);

    if (!empty($errors)) {
        $error = implode("<br>", $errors);
    } else {
        // Thực hiện đăng ký
        $result = dangKy($pdo, $hoTen, $tenDangNhap, $matKhau, $email, $soDienThoai, $vaiTro);

        if ($result === true) {
            $success = "Đăng ký thành công! <a href='login.php'>Đăng nhập ngay</a>";
        } else {
            $error = $result;
        }
    }
}

include __DIR__ . '/../src/partials/head.php';
?>

<div class="container d-flex justify-content-center align-items-center mt-3" style="min-height: 80vh;">
    <div class="card shadow-lg p-4 rounded" style="max-width: 600px; width: 100%;">
        <h2 class="text-center mb-4">Đăng Ký</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success text-center"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" name="hoten" placeholder="Nhập họ và tên" required>
                </div>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                    <input type="text" class="form-control" name="tendangnhap" placeholder="Nhập tên đăng nhập"
                        required>
                </div>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" name="email" placeholder="Nhập email" required>
                </div>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="text" class="form-control" name="sodienthoai" placeholder="Nhập số điện thoại"
                        required>
                </div>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" name="password" placeholder="Nhập mật khẩu" required>
                </div>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" name="confirmPassword" placeholder="Xác nhận mật khẩu"
                        required>
                </div>
            </div>
            <div class="d-flex justify-content-center gap-3">
                <a href="index.php" class="btn btn-outline-danger" style="width: 120px;">Hủy</a>
                <button type="submit" class="btn btn-outline-primary" style="width: 120px;">Đăng Ký</button>
            </div>
        </form>

        <p class="text-center mt-3">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
    </div>
</div>