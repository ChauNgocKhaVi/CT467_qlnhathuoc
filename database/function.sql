-- Lấy tổng số lượng thuốc trong kho của một loại thuốc
DELIMITER $$
DROP FUNCTION IF EXISTS tong_so_luong_thuoc_theo_loai $$
CREATE FUNCTION tong_so_luong_thuoc_theo_loai(p_maLoai INT) RETURNS INT 
DETERMINISTIC
BEGIN
    DECLARE tong INT DEFAULT 0;
    
    -- Lấy tổng số lượng thuốc trong kho của loại thuốc cụ thể, loại bỏ các giá trị NULL
    SELECT SUM(SoLuongTon) INTO tong
    FROM Thuoc 
    WHERE MaLoai = p_maLoai;
    
    RETURN tong;
END $$

DELIMITER ;

-- Kiểm tra số lượng thuốc theo mã
DELIMITER $$
CREATE FUNCTION so_luong_thuoc(ma INT) 
RETURNS INT 
DETERMINISTIC
BEGIN
    DECLARE soLuong INT;
    SELECT SoLuongTon INTO soLuong FROM Thuoc WHERE MaThuoc = ma;
    RETURN soLuong;
END $$
DELIMITER ;

SELECT so_luong_thuoc(1);

