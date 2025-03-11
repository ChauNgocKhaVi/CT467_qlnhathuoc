-- Lấy tổng số lượng thuốc trong kho
DELIMITER $$
CREATE FUNCTION tong_so_luong_thuoc() 
RETURNS INT 
DETERMINISTIC
BEGIN
    DECLARE tong INT;
    SELECT SUM(SoLuongTon) INTO tong FROM Thuoc;
    RETURN tong;
END $$
DELIMITER ;

SELECT tong_so_luong_thuoc();

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

