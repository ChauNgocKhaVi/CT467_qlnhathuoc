-- Thêm thuốc mới
DELIMITER $$
CREATE PROCEDURE ThemThuoc(
    IN maLoai INT, 
    IN maHangSX INT, 
    IN maNCC INT, 
    IN tenThuoc VARCHAR(255), 
    IN congDung TEXT, 
    IN donGia DECIMAL(10,2), 
    IN soLuongTon INT, 
    IN hanSuDung DATE
)
BEGIN
    INSERT INTO Thuoc (MaLoai, MaHangSX, MaNCC, TenThuoc, CongDung, DonGia, SoLuongTon, HanSuDung)
    VALUES (maLoai, maHangSX, maNCC, tenThuoc, congDung, donGia, soLuongTon, hanSuDung);
END $$
DELIMITER ;

CALL ThemThuoc(1, 2, 3, 'Paracetamol', 'Giảm đau, hạ sốt', 5000, 100, '2025-12-31');

-- Cập nhật thông tin thuốc
DELIMITER $$
CREATE PROCEDURE SuaThuoc(
    IN id INT,
    IN maLoai INT, 
    IN maHangSX INT, 
    IN maNCC INT, 
    IN tenThuoc VARCHAR(100), 
    IN congDung TEXT, 
    IN donGia DECIMAL(10,2), 
    IN soLuongTon INT, 
    IN hanSuDung DATE
)
BEGIN
    UPDATE Thuoc 
    SET MaLoai = maLoai, MaHangSX = maHangSX, MaNCC = maNCC, 
        TenThuoc = tenThuoc, CongDung = congDung, DonGia = donGia, 
        SoLuongTon = soLuongTon, HanSuDung = hanSuDung
    WHERE MaThuoc = id;
END $$
DELIMITER ;

CALL SuaThuoc(1, 2, 3, 4, 'Ibuprofen', 'Giảm đau, chống viêm', 7000, 50, '2026-06-30');

-- Đăng ký
DELIMITER $$

CREATE PROCEDURE DangKy(
    IN p_HoTen VARCHAR(100),
    IN p_TenDangNhap VARCHAR(50),
    IN p_Email VARCHAR(100),
    IN p_SoDienThoai VARCHAR(15),
    IN p_HashedMatKhau VARCHAR(255), -- Nhận mật khẩu đã hash từ PHP
    OUT p_KetQua VARCHAR(255)
)
BEGIN
    -- Bắt đầu Transaction để đảm bảo tính toàn vẹn dữ liệu
    START TRANSACTION;
    -- Thêm tài khoản với mật khẩu đã hash từ PHP
    INSERT INTO Admin (HoTen, TenDangNhap, MatKhau, Email, SoDienThoai, VaiTro, TrangThai)
    VALUES (p_HoTen, p_TenDangNhap, p_HashedMatKhau, p_Email, p_SoDienThoai, 'nhanvien', 'active');
    -- Xác nhận Transaction
    COMMIT;
    SET p_KetQua = 'Đăng ký thành công!';
END$$

DELIMITER ;


-- Đăng nhập
DELIMITER $$

CREATE PROCEDURE DangNhap(
    IN p_TenDangNhap VARCHAR(50),
    OUT p_MatKhau VARCHAR(255),
    OUT p_VaiTro ENUM('admin', 'nhanvien'),
    OUT p_KetQua VARCHAR(255)
)
BEGIN
    DECLARE db_MatKhau VARCHAR(255);
    DECLARE db_TrangThai ENUM('active', 'inactive');

    -- Lấy thông tin tài khoản
    SELECT MatKhau, VaiTro, TrangThai INTO db_MatKhau, p_VaiTro, db_TrangThai
    FROM Admin WHERE TenDangNhap = p_TenDangNhap;

    -- Kiểm tra tài khoản có tồn tại không
    IF db_MatKhau IS NULL THEN
        SET p_KetQua = 'Tên đăng nhập không tồn tại!';
    ELSEIF db_TrangThai = 'inactive' THEN
        SET p_KetQua = 'Tài khoản đã bị vô hiệu hóa!';
    ELSE
        -- Trả về mật khẩu đã hash để kiểm tra bằng PHP
        SET p_MatKhau = db_MatKhau;
        SET p_KetQua = 'OK';
    END IF;
END$$

DELIMITER ;

-- Thống kê Doanh Thu
DELIMITER $$

CREATE PROCEDURE ThongKeDoanhThu(IN kieuThongKe VARCHAR(10))
BEGIN
    IF kieuThongKe = 'ngay' THEN
        SELECT DATE(NgayLap) AS ThoiGian, SUM(TongTien) AS DoanhThu
        FROM HoaDon
        GROUP BY DATE(NgayLap)
        ORDER BY ThoiGian DESC;

    ELSEIF kieuThongKe = 'tuan' THEN
        SELECT YEARWEEK(NgayLap, 1) AS Tuan, 
               MIN(DATE(NgayLap)) AS NgayBatDau, 
               MAX(DATE(NgayLap)) AS NgayKetThuc, 
               SUM(TongTien) AS DoanhThu
        FROM HoaDon
        GROUP BY YEARWEEK(NgayLap, 1)
        ORDER BY Tuan DESC;

    ELSEIF kieuThongKe = 'thang' THEN
        SELECT YEAR(NgayLap) AS Nam, MONTH(NgayLap) AS Thang, 
               SUM(TongTien) AS DoanhThu
        FROM HoaDon
        GROUP BY YEAR(NgayLap), MONTH(NgayLap)
        ORDER BY Nam DESC, Thang DESC;

    ELSEIF kieuThongKe = 'nam' THEN
        SELECT YEAR(NgayLap) AS Nam, 
               SUM(TongTien) AS DoanhThu
        FROM HoaDon
        GROUP BY YEAR(NgayLap)
        ORDER BY Nam DESC;
    END IF;
END $$

DELIMITER ;








