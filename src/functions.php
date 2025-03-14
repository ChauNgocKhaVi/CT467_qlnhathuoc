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

// Hàm gọi Stored Function để lấy tổng số lượng thuốc trong kho của một loại thuốc
function tongSoLuongThuocTheoLoai(PDO $pdo, $maLoai)
{
    try {
        // Gọi hàm trong cơ sở dữ liệu để lấy tổng số lượng thuốc theo mã loại
        $stmt = $pdo->prepare("SELECT tong_so_luong_thuoc_theo_loai(:maLoai) AS tongSoLuong");

        // Liên kết tham số vào câu truy vấn
        $stmt->bindParam(':maLoai', $maLoai, PDO::PARAM_INT);

        // Thực thi câu lệnh SQL
        $stmt->execute();

        // Lấy kết quả và trả về
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Kiểm tra và trả về tổng số lượng thuốc hoặc 0 nếu không có kết quả
        return $result['tongSoLuong'] ?? 0;
    } catch (PDOException $e) {
        // Nếu có lỗi, ghi lại lỗi và trả về 0
        error_log("Lỗi khi gọi Stored Function: " . $e->getMessage());
        return 0;
    }
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
function themThuoc(PDO $pdo, $maLoai, $maHangSX, $maNCC, $tenThuoc, $congDung, $donGia, $soLuongTon, $hanSuDung)
{
    try {
        // Câu lệnh gọi Stored Procedure
        $stmt = $pdo->prepare("
            CALL ThemThuoc(:maLoai, :maHangSX, :maNCC, :tenThuoc, :congDung, :donGia, :soLuongTon, :hanSuDung)
        ");

        // Bind các tham số vào câu lệnh
        $stmt->bindParam(':maLoai', $maLoai, PDO::PARAM_INT);
        $stmt->bindParam(':maHangSX', $maHangSX, PDO::PARAM_INT);
        $stmt->bindParam(':maNCC', $maNCC, PDO::PARAM_INT);
        $stmt->bindParam(':tenThuoc', $tenThuoc, PDO::PARAM_STR);
        $stmt->bindParam(':congDung', $congDung, PDO::PARAM_STR);
        $stmt->bindParam(':donGia', $donGia, PDO::PARAM_STR);
        $stmt->bindParam(':soLuongTon', $soLuongTon, PDO::PARAM_INT);
        $stmt->bindParam(':hanSuDung', $hanSuDung, PDO::PARAM_STR);

        // Thực thi câu lệnh
        $stmt->execute();

        return true;
    } catch (Exception $e) {
        // Ghi log lỗi nếu có
        error_log("Lỗi khi thực thi stored procedure ThemThuoc: " . $e->getMessage());
        return false;
    }
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

// Cập nhật thông tin thuốc bằng Stored Procedure
function suaThuoc($pdo, $data)
{
    try {
        // Sử dụng CALL và đảm bảo tên tham số đúng theo Stored Procedure
        $stmt = $pdo->prepare("
            CALL SuaThuoc(:id, :maLoai, :maHangSX, :maNCC, :tenThuoc, :congDung, :donGia, :soLuongTon, :hanSuDung)
        ");

        // Gọi các tham số đã được đặt trong câu lệnh CALL
        $result = $stmt->execute([
            ':id' => $data['MaThuoc'],               // MaThuoc cần được truyền vào
            ':maLoai' => $data['MaLoai'],             // MaLoai
            ':maHangSX' => $data['MaHangSX'],         // MaHangSX
            ':maNCC' => $data['MaNCC'],               // MaNCC
            ':tenThuoc' => $data['TenThuoc'],         // TenThuoc
            ':congDung' => $data['CongDung'],         // CongDung
            ':donGia' => $data['DonGia'],             // DonGia
            ':soLuongTon' => $data['SoLuongTon'],     // SoLuongTon
            ':hanSuDung' => $data['HanSuDung']        // HanSuDung
        ]);

        // Kiểm tra kết quả thực thi
        if ($result) {
            return true;
        } else {
            // Log nếu không thực thi thành công
            error_log("Lỗi khi thực thi câu lệnh UPDATE thuốc");
            return false;
        }
    } catch (Exception $e) {
        // Log lỗi nếu có
        error_log("Lỗi: " . $e->getMessage());
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

// --------------------- XUẤT EXCEL -------------------------

// Lấy tên khách hàng
function getTenKhachHang(PDO $pdo, int $maKH): string
{
    if ($maKH === 0)
        return "Khách lẻ"; // Nếu MaKH là 0, nghĩa là không có khách hàng

    $stmt = $pdo->prepare("SELECT TenKH FROM KhachHang WHERE MaKH = :maKH");
    $stmt->execute(['maKH' => $maKH]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['TenKH'] ?? "Không xác định";
}

// Lấy tên thuốc
function getTenThuoc(PDO $pdo, int $maThuoc): string
{
    $stmt = $pdo->prepare("SELECT TenThuoc FROM Thuoc WHERE MaThuoc = :maThuoc");
    $stmt->execute(['maThuoc' => $maThuoc]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['TenThuoc'] ?? 'Không xác định';
}


// Lấy danh sách chi tiết hóa đơn
function getDanhSachChiTietHoaDon(PDO $pdo): array
{
    $stmt = $pdo->query("
        SELECT cthd.MaHD, t.TenThuoc, cthd.SoLuongBan, cthd.GiaBan, 
               (cthd.SoLuongBan * cthd.GiaBan) AS ThanhTien
        FROM ChiTietHoaDon cthd
        JOIN Thuoc t ON cthd.MaThuoc = t.MaThuoc
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy danh sách hóa đơn
function getDanhSachHoaDon(PDO $pdo): array
{
    $query = "SELECT hd.MaHD, hd.NgayLap, hd.TongTien, COALESCE(hd.MaKH, 0) AS MaKH 
    FROM HoaDon hd";
    $stmt = $pdo->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy danh sách thuốc
function getDanhSachThuoc(PDO $pdo): array
{
    $stmt = $pdo->query("
        SELECT t.MaThuoc, t.TenThuoc, l.TenLoai AS Loai, hsx.TenHang AS HangSanXuat, 
            ncc.TenNCC AS NhaCungCap, t.CongDung, t.DonGia, t.SoLuongTon, t.HanSuDung
        FROM Thuoc t
        JOIN LoaiThuoc l ON t.MaLoai = l.MaLoai
        JOIN HangSX hsx ON t.MaHangSX = hsx.MaHangSX
        JOIN NhaCungCap ncc ON t.MaNCC = ncc.MaNCC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy danh sách loại thuốc
function getDanhSachLoaiThuoc(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT MaLoai, TenLoai FROM LoaiThuoc");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy danh sách nhà cung cấp
function getDanhSachNhaCungCap(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT MaNCC, TenNCC, SoDienThoai FROM NhaCungCap");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy danh sách hãng sản xuất
function getDanhSachHangSanXuat(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT MaHangSX, TenHang, QuocGia FROM HangSX");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy danh sách khách hàng
function getDanhSachKhachHang(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT MaKH, TenKH, SoDienThoai FROM KhachHang");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// -------------------------- NHẬP TỪ EXCEL -----------------------

// Nhập dữ liệu từ sheet Hóa Đơn
function importHoaDon(PDO $pdo, $sheet)
{
    $data = $sheet->toArray();
    array_shift($data); // Bỏ qua tiêu đề

    $stmtHD = $pdo->prepare("INSERT INTO HoaDon (MaKH, NgayLap, TongTien) VALUES (?, ?, ?)");
    $stmtChiTiet = $pdo->prepare("INSERT INTO ChiTietHoaDon (MaHD, MaThuoc, SoLuongBan, GiaBan) VALUES (?, ?, ?, ?)");

    foreach ($data as $row) {
        // Xử lý mã khách hàng
        $maKH = getMaKhachHang($pdo, $row[2]); // Lấy mã khách hàng từ tên (cột thứ 3 trong Excel)

        // Chèn vào bảng HoaDon
        $stmtHD->execute([$maKH, $row[1], $row[3]]);
        $maHD = $pdo->lastInsertId(); // Lấy mã hóa đơn vừa chèn

        // Xử lý chi tiết hóa đơn
        $chiTietData = getChiTietHoaDonExcel($row[0], $pdo); // Lấy chi tiết theo mã hóa đơn

        foreach ($chiTietData as $chiTiet) {
            $maThuoc = getMaThuoc($pdo, $chiTiet[1]); // Lấy mã thuốc từ tên
            $stmtChiTiet->execute([$maHD, $maThuoc, $chiTiet[2], $chiTiet[3]]);
        }
    }

    return "Đã nhập " . count($data) . " hóa đơn.";
}

// Lấy mã khách hàng từ tên khách hàng
function getMaKhachHang(PDO $pdo, $tenKH)
{
    $stmt = $pdo->prepare("SELECT MaKH FROM KhachHang WHERE TenKH = ?");
    $stmt->execute([$tenKH]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['MaKH'] ?? null;
}

// Lấy mã thuốc từ tên thuốc
function getMaThuoc(PDO $pdo, $tenThuoc)
{
    $stmt = $pdo->prepare("SELECT MaThuoc FROM Thuoc WHERE TenThuoc = ?");
    $stmt->execute([$tenThuoc]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['MaThuoc'] ?? null;
}

// Lấy chi tiết hóa đơn từ file Excel (sheet Chi Tiết Hóa Đơn)
function getChiTietHoaDonExcel($maHD, PDO $pdo)
{
    global $spreadsheet;
    $sheet = $spreadsheet->getSheetByName("Chi Tiết Hóa Đơn");

    if (!$sheet)
        return [];

    $data = $sheet->toArray();
    array_shift($data); // Bỏ qua tiêu đề

    $chiTiet = [];
    foreach ($data as $row) {
        if ($row[0] == $maHD) { // Nếu mã hóa đơn trùng
            $chiTiet[] = [$row[0], $row[1], $row[2], $row[3]]; // Mã HĐ, Tên Thuốc, Số Lượng, Giá
        }
    }

    return $chiTiet;
}

// Nhập dữ liệu từ sheet Thuốc
function importThuoc(PDO $pdo, $sheet)
{
    $data = $sheet->toArray();
    array_shift($data); // Bỏ qua tiêu đề

    $stmt = $pdo->prepare("INSERT INTO Thuoc (MaLoai, MaHangSX, MaNCC, TenThuoc, CongDung, DonGia, SoLuongTon, HanSuDung) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    foreach ($data as $row) {
        $stmt->execute([$row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7]]);
    }
    return "Đã nhập " . count($data) . " thuốc.";
}

// Nhập dữ liệu từ sheet Loại Thuốc
function importLoaiThuoc(PDO $pdo, $sheet)
{
    $data = $sheet->toArray();
    array_shift($data);

    $stmt = $pdo->prepare("INSERT INTO LoaiThuoc (TenLoai, DonViTinh) VALUES (?, ?)");

    foreach ($data as $row) {
        $stmt->execute([$row[0], $row[1]]);
    }
    return "Đã nhập " . count($data) . " loại thuốc.";
}

// Nhập dữ liệu từ sheet Nhà Cung Cấp
function importNhaCungCap(PDO $pdo, $sheet)
{
    $data = $sheet->toArray();
    array_shift($data);

    $stmt = $pdo->prepare("INSERT INTO NhaCungCap (TenNCC, SoDienThoai) VALUES (?, ?)");

    foreach ($data as $row) {
        $stmt->execute([$row[0], $row[1]]);
    }
    return "Đã nhập " . count($data) . " nhà cung cấp.";
}

// Nhập dữ liệu từ sheet Hãng Sản Xuất
function importHangSanXuat(PDO $pdo, $sheet)
{
    $data = $sheet->toArray();
    array_shift($data);  // Bỏ qua tiêu đề

    $stmt = $pdo->prepare("INSERT INTO HangSX (TenHang, QuocGia) VALUES (?, ?)");

    foreach ($data as $row) {
        $stmt->execute([$row[0], $row[1]]);
    }

    return "Đã nhập " . count($data) . " hãng sản xuất.";
}

// Nhập dữ liệu từ sheet Khách Hàng
function importKhachHang(PDO $pdo, $sheet)
{
    $data = $sheet->toArray();
    array_shift($data);

    $stmt = $pdo->prepare("INSERT INTO KhachHang (TenKH, SoDienThoai, DiaChi) VALUES (?, ?, ?)");

    foreach ($data as $row) {
        $stmt->execute([$row[0], $row[1], $row[2]]);
    }
    return "Đã nhập " . count($data) . " khách hàng.";
}

?>