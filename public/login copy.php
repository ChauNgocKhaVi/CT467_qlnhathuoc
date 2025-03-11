<?php
ob_start(); // Bật buffer output để tránh lỗi header
session_start();
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/functions.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenDangNhap = trim($_POST['usernameOrEmail']);
    $matKhau = trim($_POST['password']);

    if (!empty($tenDangNhap) && !empty($matKhau)) {
        $result = dangNhap($pdo, $tenDangNhap, $matKhau);

        if ($result === true) {
            header("Location: index.php"); // Chuyển hướng sau khi đăng nhập thành công
            exit();
        } else {
            $error = $result; // Nhận thông báo lỗi từ `dangNhap()`
        }
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    }
}

include __DIR__ . '/../src/partials/head.php';
?>

<div class="container d-flex justify-content-center align-items-center mt-3" style="min-height: 80vh;">
    <div class="card shadow-lg p-4 rounded" style="max-width: 500px; width: 100%;">
        <h2 class="text-center mb-4">Đăng Nhập</h2>

        <!-- Hiển thị lỗi nếu có -->
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" name="usernameOrEmail" placeholder="Nhập tên đăng nhập"
                        required>
                </div>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" name="password" placeholder="Nhập mật khẩu" required>
                </div>
            </div>
            <div class="d-flex justify-content-center gap-3">
                <a href="index.php" class="btn btn-outline-danger" style="width: 120px;">Hủy</a>
                <button type="submit" class="btn btn-outline-primary" style="width: 120px;">Đăng Nhập</button>
            </div>
        </form>

        <p class="text-center mt-3">Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
    </div>
</div>