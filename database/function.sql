-- Lấy tổng số lượng thuốc trong kho của một loại thuốc
DELIMITER $$

CREATE FUNCTION tong_so_luong_thuoc_theo_loai(maLoai INT) 
RETURNS INT 
DETERMINISTIC
BEGIN
    DECLARE tong INT;
    -- Lấy tổng số lượng thuốc trong kho của loại thuốc cụ thể
    SELECT SUM(SoLuongTon) INTO tong 
    FROM Thuoc
    WHERE MaLoai = maLoai;
    
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

