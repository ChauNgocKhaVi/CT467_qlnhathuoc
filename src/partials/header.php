<header>
    <div class="navbar navbar-expand-lg  m-0 p-0 custom-header">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a title="Nhà thuốc" href="index.php">
                    <img class="logo" src="/images/logo2.png"> 
                    Nhà thuốc
                </a>
            </div>
            <div class="d-flex align-items-center">
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
            </div>
        </div>
    </div>
</header>