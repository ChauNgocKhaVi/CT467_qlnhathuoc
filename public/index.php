<?php
session_start();
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/functions.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
} elseif (!empty($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Xóa session sau khi hiển thị
}

include __DIR__ . '/../src/partials/head.php';
include __DIR__ . '/../src/partials/header.php';
?>

<style>

</style>

<?php if (!empty($success_message)): ?>
    <div id="success-alert" class="alert alert-success text-center">
        <?php echo htmlspecialchars($success_message); ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            setTimeout(function () {
                $("#success-alert").fadeOut(500);
            }, 5000);
        });
    </script>
<?php endif; ?>


<div class="welcome-container">
    <div class="welcome-box">
        <h1>Chào mừng bạn đến với Nhà Thuốc</h1>
        <p>Chúng tôi cung cấp những sản phẩm chất lượng giúp bạn chăm sóc sức khỏe tốt nhất!</p>
    </div>
</div>

<script>
    // Hiển thị thông báo đăng nhập thành công nếu có
    document.addEventListener('DOMContentLoaded', function () {
        <?php if (!empty($successMessage)): ?>
            Swal.fire({
                title: "Đăng nhập thành công!",
                text: "<?php echo htmlspecialchars($successMessage); ?>",
                icon: "success",
                confirmButtonText: "OK"
            });
        <?php endif; ?>
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php include __DIR__ . '/../src/partials/footer.php'; ?>