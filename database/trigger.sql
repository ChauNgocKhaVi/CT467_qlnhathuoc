-- Thông báo thuốc sắp hết hạn
DELIMITER $$
CREATE TRIGGER canh_bao_thuoc_het_han
BEFORE UPDATE ON Thuoc
FOR EACH ROW
BEGIN
    IF NEW.HanSuDung <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN
        SET NEW.ThuocSapHetHan = 1;
    ELSE
        SET NEW.ThuocSapHetHan = 0;
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