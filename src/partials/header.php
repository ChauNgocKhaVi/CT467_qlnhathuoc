<header class="custom-header py-3 shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="h4 text-white mb-0">Nhà Thuốc</h1>
        <nav>
            <?php if (isset($_SESSION["username"])): ?>
            <a href="account.php" class="btn btn-light me-2">Tài khoản</a>
            <a href="logout.php" class="btn btn-light text-danger">Đăng xuất</a>
            <?php else: ?>
            <a href="login.php" class="btn btn-light me-2">Đăng nhập</a>
            <a href="register.php" class="btn btn-light text-primary">Đăng ký</a>
            <?php endif; ?>
        </nav>
    </div>
</header>