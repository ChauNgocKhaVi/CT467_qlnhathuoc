<header class="custom-header py-3 shadow-sm">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h1 class="h4 text-white mb-0 ms-5">Nhà Thuốc</h1>
        <nav class="d-flex align-items-center">
            <?php if (isset($_SESSION["username"])): ?>
                <div class="me-3 text-white text-end">
                    <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>
                    <br>
                    <span
                        class="badge bg-info"><?php echo ($_SESSION["VaiTro"] === "admin") ? "Quản trị viên" : "Nhân viên"; ?></span>
                </div>
                <a href="logout.php" class="btn btn-light text-danger">Đăng xuất</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-light me-2">Đăng nhập</a>
                <a href="register.php" class="btn btn-light text-primary">Đăng ký</a>
            <?php endif; ?>
        </nav>
    </div>
</header>