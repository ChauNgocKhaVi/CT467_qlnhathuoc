-- Lấy danh sách thuốc theo loại
DELIMITER $$
CREATE PROCEDURE LayThuocTheoLoai(IN loai INT)
BEGIN
    SELECT * FROM Thuoc WHERE MaLoai = loai ORDER BY TenThuoc ASC;
END $$
DELIMITER ;

CALL LayThuocTheoLoai(2);

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
    IN tenThuoc VARCHAR(255), 
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

-- Xóa thuốc khỏi hệ thống
DELIMITER $$
CREATE PROCEDURE XoaThuoc(IN id INT)
BEGIN
    DELETE FROM Thuoc WHERE MaThuoc = id;
END $$
DELIMITER ;

CALL XoaThuoc(1);
