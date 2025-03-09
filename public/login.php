<?php
ob_start(); // Bật buffer output để tránh lỗi header
session_start();
require_once __DIR__ . '/../src/bootstrap.php';

use CT467\Labs\User;

$user = new User($pdo); 

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernameOrEmail = trim($_POST['usernameOrEmail']);
    $password = trim($_POST['password']);

    if (!empty($usernameOrEmail) && !empty($password)) {
        $loggedInUser = $user->login($usernameOrEmail, $password);

        if ($loggedInUser) {
            $_SESSION['user'] = $loggedInUser;
            header("Location: index.php");
            exit();
        }

    }
}

include __DIR__ . '/../src/partials/head.php';
// include __DIR__ . '/../src/partials/header.php';
?>

<div class="container d-flex justify-content-center align-items-center mt-3" style="min-height: 80vh;">
    <div class="card shadow-lg p-4 rounded" style="max-width: 500px; width: 100%;">
        <h2 class="text-center mb-4">Đăng Nhập</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" name="usernameOrEmail"
                        placeholder="Nhập tên đăng nhập hoặc email" required>
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