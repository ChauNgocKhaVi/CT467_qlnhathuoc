<?php
ob_start();
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/functions.php';


$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenDangNhap = trim($_POST['usernameOrEmail']);
    $matKhau = trim($_POST['password']);

    if (!empty($tenDangNhap) && !empty($matKhau)) {
        // Gọi Stored Procedure để lấy mật khẩu hash và vai trò
        $stmt = $pdo->prepare("CALL DangNhap(?, @matkhau, @vaitro, @ketqua)");
        $stmt->execute([$tenDangNhap]);

        // Lấy kết quả từ biến OUT
        $result = $pdo->query("SELECT @matkhau AS MatKhauHash, @vaitro AS VaiTro, @ketqua AS KetQua")->fetch(PDO::FETCH_ASSOC);

        if ($result["KetQua"] === "OK") {
            // Kiểm tra mật khẩu bằng password_verify()
            if (password_verify($matKhau, $result["MatKhauHash"])) {
                $_SESSION["username"] = $tenDangNhap;
                $_SESSION["role"] = $result["VaiTro"];
                header("Location: index.php"); // Chuyển hướng về trang chủ
                exit();
            } else {
                $error = "Mật khẩu không đúng!";
            }
        } else {
            $error = $result["KetQua"]; // Nhận thông báo lỗi từ Stored Procedure
        }
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    }
}

include __DIR__ . '/../src/partials/head.php';
// include __DIR__ . '/../src/partials/header.php';
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