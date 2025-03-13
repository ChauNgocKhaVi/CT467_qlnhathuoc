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

// --------------------- LẤY CHI TIẾT HÓA ĐƠN BÁN THUỐC ---------------------
function layChiTietHoaDon($pdo, $maHD)
{
    $stmt = $pdo->prepare("
        SELECT cthd.MaThuoc, t.TenThuoc, cthd.SoLuongBan, cthd.GiaBan, 
               (cthd.SoLuongBan * cthd.GiaBan) AS ThanhTien
        FROM ChiTietHoaDon cthd
        JOIN Thuoc t ON cthd.MaThuoc = t.MaThuoc
        WHERE cthd.MaHD = :maHD
    ");
    $stmt->execute(['maHD' => $maHD]);
    return $stmt->fetchAll();
}

// --------------------- LẤY LOẠI THUỐC ---------------------

// Lấy danh sách tất cả thể loại thuốc
function layTatCaLoaiThuoc(PDO $pdo): array
{
    $query = "SELECT * FROM LoaiThuoc";
    $stmt = $pdo->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// --------------------- LẤY NHÀ CUNG CẤP ---------------------

// Lấy danh sách tất cả nhà cung cấp
function layTatCaNhaCungCap(PDO $pdo): array
{
    $query = "SELECT * FROM NhaCungCap";
    $stmt = $pdo->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// --------------------- LẤY HãNG SẢN XUẤT ---------------------

// Lấy danh sách tất cả hãng sản xuất
function layTatCaHangSanXuat(PDO $pdo): array
{
    $query = "SELECT * FROM HangSX";
    $stmt = $pdo->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// --------------------- LẤY DANH SÁCH KHÁCH HÀNG ---------------------

// Lấy danh sách tất cả hãng sản xuất
function layTatCaKhachHang(PDO $pdo): array
{
    $query = "SELECT * FROM KhachHang";
    $stmt = $pdo->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// --------------------- LẤY DANH SÁCH NHÂN VIÊN ---------------------

// Lấy danh sách tất cả hãng sản xuất
function layTatCaNhanVien(PDO $pdo): array
{
    $query = "SELECT * FROM Admin";
    $stmt = $pdo->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// --------------------- THÊM ---------------------

//Thêm thuốc
function themThuoc(PDO $pdo, $maLoai, $maHangSX, $maNCC, $tenThuoc, $donGia, $soLuongTon, $hanSuDung)
{
    $query = "INSERT INTO Thuoc (MaLoai, MaHangSX, MaNCC, TenThuoc, DonGia, SoLuongTon, HanSuDung) 
            VALUES (:maLoai, :maHangSX, :maNCC, :tenThuoc, :donGia, :soLuongTon, :hanSuDung)";
    $stmt = $pdo->prepare($query);

    $stmt->bindParam(':maLoai', $maLoai, PDO::PARAM_INT);
    $stmt->bindParam(':maHangSX', $maHangSX, PDO::PARAM_INT);
    $stmt->bindParam(':maNCC', $maNCC, PDO::PARAM_INT);
    $stmt->bindParam(':tenThuoc', $tenThuoc, PDO::PARAM_STR);
    $stmt->bindParam(':donGia', $donGia, PDO::PARAM_STR);
    $stmt->bindParam(':soLuongTon', $soLuongTon, PDO::PARAM_INT);
    $stmt->bindParam(':hanSuDung', $hanSuDung, PDO::PARAM_STR);

    return $stmt->execute();
}

// Thêm nhân viên
function themNhanVien(PDO $pdo, $hoTen, $tenDangNhap, $email, $matKhau, $soDienThoai, $vaiTro, $trangThai)
{
    // Kiểm tra xem tên đăng nhập hoặc email đã tồn tại chưa
    $checkQuery = "SELECT COUNT(*) FROM Admin WHERE TenDangNhap = :tenDangNhap OR Email = :email";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->bindParam(':tenDangNhap', $tenDangNhap, PDO::PARAM_STR);
    $checkStmt->bindParam(':email', $email, PDO::PARAM_STR);
    $checkStmt->execute();
    $exists = $checkStmt->fetchColumn();

    if ($exists > 0) {
        return "Tên đăng nhập hoặc email đã tồn tại!";
    }

    // Hash mật khẩu trước khi lưu
    $hashedPassword = password_hash($matKhau, PASSWORD_BCRYPT);

    // Thực hiện thêm nhân viên
    $query = "INSERT INTO Admin (HoTen, TenDangNhap, MatKhau, Email, SoDienThoai, VaiTro, TrangThai) 
            VALUES (:hoTen, :tenDangNhap, :matKhau, :email, :soDienThoai, :vaiTro, :trangThai)";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':hoTen', $hoTen, PDO::PARAM_STR);
    $stmt->bindParam(':tenDangNhap', $tenDangNhap, PDO::PARAM_STR);
    $stmt->bindParam(':matKhau', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':soDienThoai', $soDienThoai, PDO::PARAM_STR);
    $stmt->bindParam(':vaiTro', $vaiTro, PDO::PARAM_STR);
    $stmt->bindParam(':trangThai', $trangThai, PDO::PARAM_STR);

    if ($stmt->execute()) {
        return true; // Thành công
    } else {
        return "Thêm nhân viên thất bại!";
    }
}

// --------------------- EDIT ---------------------

// Cập nhật thông tin thuốc
function suaThuoc($pdo, $data)
{
    try {
        $stmt = $pdo->prepare("
            UPDATE Thuoc 
            SET MaLoai = ?, MaHangSX = ?, MaNCC = ?, TenThuoc = ?, CongDung = ?, DonGia = ?, SoLuongTon = ?, HanSuDung = ? 
            WHERE MaThuoc = ?
        ");
        return $stmt->execute([
            $data['MaLoai'],
            $data['MaHangSX'],
            $data['MaNCC'],
            $data['TenThuoc'],
            $data['CongDung'],
            $data['DonGia'],
            $data['SoLuongTon'],
            $data['HanSuDung'],
            $data['MaThuoc']
        ]);
    } catch (Exception $e) {
        return false;
    }
}


// Cập nhật nhân viên
function suaNhanVien($pdo, $data)
{
    try {
        $stmt = $pdo->prepare("
            UPDATE Admin SET HoTen = ?, SoDienThoai = ?, TenDangNhap = ?, Email = ?, VaiTro = ?, TrangThai = ? 
            WHERE MaND = ?
        ");
        return $stmt->execute([
            $data['HoTen'],
            $data['SoDienThoai'],
            $data['TenDangNhap'],
            $data['Email'],
            $data['VaiTro'],
            $data['TrangThai'],
            $data['MaND']
        ]);
    } catch (Exception $e) {
        return false;
    }
}

// --------------------- XÓA ---------------------

// Xóa thuốc
function xoaThuoc($pdo, $MaThuoc)
{
    try {
        $stmt = $pdo->prepare("DELETE FROM Thuoc WHERE MaThuoc = ?");
        return $stmt->execute([$MaThuoc]);
    } catch (Exception $e) {
        return false;
    }
}

// Xóa nhân viên
function xoaNhanVien($pdo, $MaND)
{
    try {
        $stmt = $pdo->prepare("DELETE FROM Admin WHERE MaND = ?");
        return $stmt->execute([$MaND]);
    } catch (Exception $e) {
        return false;
    }
}

// --------------- THÔNG BÁO THUỐC HẾT HẠN ---------------------

// Lấy danh sách thông báo thuốc hết hạn
function layThongBaoThuocHetHan($pdo)
{
    $stmt = $pdo->prepare("
        SELECT tb.*, t.TenThuoc 
        FROM ThongBao tb
        JOIN Thuoc t ON tb.MaThuoc = t.MaThuoc
        WHERE tb.TrangThai = 'chua_xem'
        ORDER BY tb.ThoiGianThongBao DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Cập nhật thông báo thuốc hết hạn
function capNhatThongBaoThuocHetHan($pdo)
{
    $stmt = $pdo->prepare("
        INSERT INTO ThongBao (MaThuoc, NoiDung)
        SELECT t.MaThuoc, CONCAT('Thuốc \"', t.TenThuoc, '\" sắp hết hạn vào ngày ', t.HanSuDung)
        FROM Thuoc t
        WHERE t.HanSuDung <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
        AND NOT EXISTS (
            SELECT 1 FROM ThongBao tb WHERE tb.MaThuoc = t.MaThuoc AND tb.TrangThai = 'chua_xem'
        )
    ");
    $stmt->execute();
}


?>