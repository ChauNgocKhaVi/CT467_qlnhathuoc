-- Thông báo thuốc sắp hết hạn
DELIMITER $$

CREATE TRIGGER canh_bao_thuoc_het_han
AFTER UPDATE ON Thuoc
FOR EACH ROW
BEGIN
    -- Nếu thuốc sắp hết hạn trong vòng 30 ngày
    IF NEW.HanSuDung <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN
        INSERT INTO ThongBao (MaThuoc, NoiDung)
        VALUES (NEW.MaThuoc, CONCAT('Thuốc "', NEW.TenThuoc, '" sắp hết hạn vào ngày ', NEW.HanSuDung));
    END IF;
END $$

DELIMITER ;

-- Tự động cập nhật số lượng khi thêm thuốc vào kho
DELIMITER $$
CREATE TRIGGER cap_nhat_so_luong_sau_nhap
AFTER INSERT ON NhapThuoc
FOR EACH ROW
BEGIN
    UPDATE Thuoc 
    SET SoLuongTon = SoLuongTon + NEW.SoLuongNhap
    WHERE MaThuoc = NEW.MaThuoc;
END $$
DELIMITER ;

-- Kiểm tra trùng lặp
DROP TRIGGER IF EXISTS kt_trunglap;

DELIMITER $$

CREATE TRIGGER kt_trunglap BEFORE INSERT ON Admin
FOR EACH ROW
BEGIN
    -- Kiểm tra tên đăng nhập trùng
    IF EXISTS (SELECT 1 FROM Admin WHERE TenDangNhap = NEW.TenDangNhap) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Tên đăng nhập đã tồn tại!';
    END IF;

    -- Kiểm tra email trùng
    IF EXISTS (SELECT 1 FROM Admin WHERE Email = NEW.Email) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Email đã tồn tại!';
    END IF;

    -- Kiểm tra số điện thoại có hợp lệ không
    IF NEW.SoDienThoai NOT REGEXP '^[0-9]{10,15}$' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Số điện thoại không hợp lệ!';
    END IF;
END$$


-- Cập nhật doanh thu khi thêm hóa đơn
DELIMITER $$

CREATE TRIGGER capNhatDoanhThuSauKhiThemHD
AFTER INSERT ON hoadon
FOR EACH ROW
BEGIN
    -- Khai báo biến để lưu doanh thu của hóa đơn mới
    DECLARE doanhThu DECIMAL(15, 2);
    DECLARE doanhThuTrongNgay DECIMAL(15, 2);

    -- Gán giá trị doanh thu từ hóa đơn mới
    SET doanhThu = NEW.TongTien;

    -- Tính tổng doanh thu trong ngày
    SELECT SUM(TongTien) INTO doanhThuTrongNgay
    FROM hoadon
    WHERE DATE(NgayLap) = DATE(NEW.NgayLap);

    -- Bạn có thể thực hiện hành động khác, ví dụ:
    -- In ra doanh thu của ngày đó (hoặc có thể xử lý ở ứng dụng khác)
    -- Trigger không thể trả về kết quả nhưng có thể sử dụng kết quả này trong các logic tiếp theo
END $$

DELIMITER ;






