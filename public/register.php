<?php
require_once __DIR__ . '/../src/bootstrap.php';
use CT467\Labs\User;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User($pdo);

    $hoten = $_POST['hoten'];
    $tendangnhap = $_POST['tendangnhap'];
    $email = $_POST['email'];
    $sodienthoai = $_POST['sodienthoai'];
    $password = $_POST['password'];

    if ($user->register($hoten, $tendangnhap, $email, $sodienthoai, $password)) {
        echo "<div class='alert alert-success text-center'>Đăng ký thành công! <a href='login.php'>Đăng nhập ngay</a></div>";
    } else {
        echo "<div class='alert alert-danger text-center'>Đăng ký thất bại! Vui lòng thử lại.</div>";
    }
}

include __DIR__ . '/../src/partials/head.php';
// include __DIR__ . '/../src/partials/header.php';
?>

<div class="container d-flex justify-content-center align-items-center mt-3" style="min-height: 80vh;">
    <div class="card shadow-lg p-4 rounded" style="max-width: 600px; width: 100%;">
        <h2 class="text-center mb-4">Đăng Ký</h2>
        <form method="POST" action="">
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
            <div class="d-flex justify-content-center gap-3">
                <a href="index.php" class="btn  btn-outline-danger" style="width: 120px;">Hủy</a>
                <button type="submit" class="btn btn-outline-primary" style="width: 120px;">Đăng Ký</button>
            </div>



        </form>
        <p class="text-center mt-3">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
    </div>
</div>