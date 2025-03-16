-- Lấy tổng số lượng thuốc trong kho của một loại thuốc
DELIMITER $$
DROP FUNCTION IF EXISTS tong_so_luong_thuoc_theo_loai $$
CREATE FUNCTION tong_so_luong_thuoc_theo_loai(p_maLoai INT) RETURNS INT 
DETERMINISTIC
BEGIN
    DECLARE tong INT DEFAULT 0;
    
    -- Lấy tổng số lượng thuốc trong kho của
    -- loại thuốc cụ thể, loại bỏ các giá trị NULL
    SELECT SUM(SoLuongTon) INTO tong
    FROM Thuoc 
    WHERE MaLoai = p_maLoai;
    
    RETURN tong;
END $$

DELIMITER ;

