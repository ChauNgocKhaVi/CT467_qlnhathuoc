<?php

// Chuyển hướng
function redirect(string $location): void
{
    header('Location: ' . $location, true, 302);
    exit();
}

// Xử lý dữ liệu đầu vào an toàn
function html_escape(string|null $text): string
{
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8', false);
}

function validateInput(string $tenDangNhap, string $email, string $matKhau, string $xacNhanMatKhau, string $soDienThoai): array
{
    $errors = [];

    // Kiểm tra tên đăng nhập
    if (empty($tenDangNhap)) {
        $errors['tenDangNhap'] = 'Vui lòng nhập tên đăng nhập.';
    }

    // Kiểm tra email hợp lệ
    if (empty($email)) {
        $errors['email'] = 'Vui lòng nhập email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không hợp lệ.';
    }

    // Kiểm tra mật khẩu có đủ mạnh
    if (empty($matKhau)) {
        $errors['matKhau'] = 'Vui lòng nhập mật khẩu.';
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/', $matKhau)) {
        $errors['matKhau'] = 'Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.';
    }

    // Kiểm tra xác nhận mật khẩu
    if ($matKhau !== $xacNhanMatKhau) {
        $errors['xacNhanMatKhau'] = 'Mật khẩu không khớp.';
    }

    // Kiểm tra số điện thoại
    if (empty($soDienThoai)) {
        $errors['soDienThoai'] = 'Vui lòng nhập số điện thoại.';
    } elseif (!preg_match('/^(0|\+84)[35789][0-9]{8}$/', $soDienThoai)) {
        $errors['soDienThoai'] = 'Số điện thoại không hợp lệ.';
    }

    return $errors;
}

// --------------------- ĐĂNG KÝ NGƯỜI DÙNG ---------------------
function dangKy(PDO $pdo, string $hoTen, string $tenDangNhap, string $matKhau, string $email, string $soDienThoai, string $vaiTro): string|bool
{
    // Kiểm tra tài khoản đã tồn tại chưa
    $query = "SELECT 1 FROM Admin WHERE TenDangNhap = :tenDangNhap OR Email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':tenDangNhap', $tenDangNhap);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->fetch()) {
        return 'Tên đăng nhập hoặc email đã tồn tại!';
    }

    // Mã hóa mật khẩu
    $hashed_password = password_hash($matKhau, PASSWORD_DEFAULT);

    // Chèn dữ liệu vào database
    $query = "INSERT INTO Admin (HoTen, TenDangNhap, MatKhau, Email, SoDienThoai, VaiTro, TrangThai) 
            VALUES (:hoTen, :tenDangNhap, :matKhau, :email, :soDienThoai, 'nhanvien', 'active')";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':hoTen', $hoTen);
    $stmt->bindParam(':tenDangNhap', $tenDangNhap);
    $stmt->bindParam(':matKhau', $hashed_password);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':soDienThoai', $soDienThoai);
    if ($stmt->execute()) {
        return true; // Đăng ký thành công
    } else {
        return 'Lỗi đăng ký, vui lòng thử lại.';
    }
}

// --------------------- ĐĂNG NHẬP NGƯỜI DÙNG ---------------------
function dangNhap(PDO $pdo, string $tenDangNhap, string $matKhau): string|bool
{
    $query = "SELECT MaND, HoTen, MatKhau, VaiTro FROM Admin WHERE TenDangNhap = :tenDangNhap AND TrangThai = 'active'";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':tenDangNhap', $tenDangNhap);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($matKhau, $user['MatKhau'])) {
        $_SESSION['MaND'] = $user['MaND'];
        $_SESSION['HoTen'] = $user['HoTen'];
        $_SESSION['VaiTro'] = $user['VaiTro'];
        return true; // Đăng nhập thành công
    } else {
        return 'Sai tên đăng nhập hoặc mật khẩu!';
    }
}

// --------------------- KIỂM TRA NGƯỜI DÙNG ĐÃ ĐĂNG NHẬP ---------------------
function isLoggedIn(): bool
{
    return isset($_SESSION['MaND']);
}

// --------------------- ĐĂNG XUẤT ---------------------
function dangXuat(): void
{
    session_destroy();
    header('Location: login.php');
    exit();
}
?>