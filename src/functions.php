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
function dangKy(PDO $pdo, string $hoTen, string $tenDangNhap, string $matKhau, string $email, string $soDienThoai): string|bool
{
    // Mã hóa mật khẩu trước khi gửi vào Stored Procedure
    $hashed_password = password_hash($matKhau, PASSWORD_DEFAULT);

    // Gọi Stored Procedure DangKy
    $query = "CALL DangKy(:hoTen, :tenDangNhap, :email, :soDienThoai, :matKhau, @KetQua)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':hoTen', $hoTen);
    $stmt->bindParam(':tenDangNhap', $tenDangNhap);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':soDienThoai', $soDienThoai);
    $stmt->bindParam(':matKhau', $hashed_password);

    if ($stmt->execute()) {
        // Lấy kết quả trả về từ biến @KetQua
        $result = $pdo->query("SELECT @KetQua AS KetQua")->fetch(PDO::FETCH_ASSOC);
        return $result['KetQua']; // Trả về thông báo từ Stored Procedure
    } else {
        return 'Lỗi đăng ký, vui lòng thử lại!';
    }
}

// --------------------- ĐĂNG NHẬP NGƯỜI DÙNG ---------------------
function dangNhap(PDO $pdo, string $tenDangNhap, string $matKhau): string|bool
{
    // Gọi Stored Procedure DangNhap
    $query = "CALL DangNhap(:tenDangNhap, @MatKhau, @VaiTro, @KetQua)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':tenDangNhap', $tenDangNhap);
    $stmt->execute();

    // Lấy kết quả từ biến OUT
    $result = $pdo->query("SELECT @MatKhau AS MatKhau, @VaiTro AS VaiTro, @KetQua AS KetQua")->fetch(PDO::FETCH_ASSOC);

    if ($result['KetQua'] === 'OK' && password_verify($matKhau, $result['MatKhau'])) {
        // Lưu session đăng nhập
        $_SESSION['TenDangNhap'] = $tenDangNhap;
        $_SESSION['VaiTro'] = $result['VaiTro'];
        return true; // Đăng nhập thành công
    } else {
        return $result['KetQua']; // Trả về thông báo từ Stored Procedure (VD: "Tên đăng nhập không tồn tại!")
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

// ----------------------------- THUỐC ---------------------------------

// Lấy danh sách tất cả các loại thuốc
function layTatCaThuoc(PDO $pdo): array
{
    $query = "SELECT t.*, t.DonGia AS Gia, t.SoLuongTon AS SoLuong, l.TenLoai, h.TenHang, n.TenNCC 
            FROM Thuoc t
            JOIN LoaiThuoc l ON t.MaLoai = l.MaLoai
            JOIN HangSX h ON t.MaHangSX = h.MaHangSX
            JOIN NhaCungCap n ON t.MaNCC = n.MaNCC
            ";
    $stmt = $pdo->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy thông tin thuốc theo ID
function layThuocTheoID(PDO $pdo, int $maThuoc): array|false
{
    $query = "SELECT t.*, l.TenLoai, h.TenHang, n.TenNCC 
            FROM Thuoc t
            JOIN LoaiThuoc l ON t.MaLoai = l.MaLoai
            JOIN HangSX h ON t.MaHangSX = h.MaHangSX
            JOIN NhaCungCap n ON t.MaNCC = n.MaNCC
            WHERE t.MaThuoc = :maThuoc";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':maThuoc', $maThuoc, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Lấy danh sách tất cả thể loại thuốc
function layTatCaLoaiThuoc(PDO $pdo): array
{
    $query = "SELECT * FROM LoaiThuoc";
    $stmt = $pdo->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy danh sách tất cả nhà cung cấp
function layTatCaNhaCungCap(PDO $pdo): array
{
    $query = "SELECT * FROM NhaCungCap";
    $stmt = $pdo->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy danh sách tất cả hãng sản xuất
function layTatCaHangSanXuat(PDO $pdo): array
{
    $query = "SELECT * FROM HangSX";
    $stmt = $pdo->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy danh sách thuốc theo loại
function layThuocTheoLoai(PDO $pdo, int $maLoai): array
{
    $query = "SELECT * FROM Thuoc WHERE MaLoai = :maLoai";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':maLoai', $maLoai, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy danh sách thuốc theo nhà cung cấp
function layThuocTheoNCC(PDO $pdo, int $maNCC): array
{
    $query = "SELECT * FROM Thuoc WHERE MaNCC = :maNCC";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':maNCC', $maNCC, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy danh sách thuốc theo hãng sản xuất
function layThuocTheoHangSX(PDO $pdo, int $maHangSX): array
{
    $query = "SELECT * FROM Thuoc WHERE MaHangSX = :maHangSX";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':maHangSX', $maHangSX, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// --------------------- LẤY HÓA ĐƠN BÁN THUỐC ---------------------
function layHoaDonBanThuoc(PDO $pdo): array
{
    $query = "SELECT hd.MaHD, kh.TenKH, kh.SoDienThoai, hd.NgayLap, hd.TongTien 
            FROM HoaDon hd 
            LEFT JOIN KhachHang kh ON hd.MaKH = kh.MaKH 
            ORDER BY hd.NgayLap DESC";
    $stmt = $pdo->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>