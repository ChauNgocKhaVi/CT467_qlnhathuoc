CREATE DATABASE qlnhathuoc;
USE qlnhathuoc;

CREATE TABLE NguoiDung (
    MaND INT AUTO_INCREMENT PRIMARY KEY,
    HoTen VARCHAR(100),
    TenDangNhap VARCHAR(50) UNIQUE,
    MatKhau VARCHAR(255),
    Email VARCHAR(100) UNIQUE,
    SoDienThoai VARCHAR(15),
    VaiTro ENUM('admin', 'nhanvien', 'khachhang'),
    TrangThai ENUM('active', 'inactive') DEFAULT 'active'
);

CREATE TABLE Thuoc (
    MaThuoc INT AUTO_INCREMENT PRIMARY KEY,
    MaLoai INT,
    MaHangSX INT,
    MaNCC INT,
    TenThuoc VARCHAR(100),
    CongDung TEXT,
    DonGia DECIMAL(10,2),
    SoLuongTon INT,
    HanSuDung DATE
);

CREATE TABLE HangSX (
    MaHangSX INT AUTO_INCREMENT PRIMARY KEY,
    TenHang VARCHAR(100),
    QuocGia VARCHAR(50)
);

CREATE TABLE LoaiThuoc (
    MaLoai INT AUTO_INCREMENT PRIMARY KEY,
    TenLoai VARCHAR(100),
    DonViTinh VARCHAR(20)
);

CREATE TABLE NhaCungCap (
    MaNCC INT AUTO_INCREMENT PRIMARY KEY,
    TenNCC VARCHAR(100),
    SoDienThoai VARCHAR(15)
);

CREATE TABLE KhachHang (
    MaKH INT AUTO_INCREMENT PRIMARY KEY,
    TenKH VARCHAR(100),
    SoDienThoai VARCHAR(15),
    DiaChi TEXT
);

CREATE TABLE HoaDon (
    MaHD INT AUTO_INCREMENT PRIMARY KEY,
    MaKH INT,
    NgayLap DATE,
    TongTien DECIMAL(10,2)
);

CREATE TABLE ChiTietHoaDon (
    MaCTHD INT AUTO_INCREMENT PRIMARY KEY,
    MaHD INT,
    MaThuoc INT,
    SoLuongBan INT,
    GiaBan DECIMAL(10,2)
);




INSERT INTO HangSX (TenHang, QuocGia) VALUES
('Dược phẩm Bình An', 'Việt Nam'),
('Pfizer', 'Mỹ'),
('Sanofi', 'Pháp'),
('GSK', 'Anh'),
('Bayer', 'Đức');

INSERT INTO LoaiThuoc (TenLoai, DonViTinh) VALUES
('Thuốc giảm đau', 'Viên'),
('Thuốc kháng sinh', 'Viên'),
('Vitamin', 'Viên'),
('Thuốc tiêu hóa', 'Gói'),
('Thuốc hạ sốt', 'Viên');

INSERT INTO NhaCungCap (TenNCC, SoDienThoai) VALUES
('Công ty Dược Hà Nội', '0123456789'),
('Công ty Dược Sài Gòn', '0987654321'),
('Nhà thuốc Phano', '0934567890'),
('Nhà thuốc Long Châu', '0961234567'),
('Công ty Dược Hậu Giang', '0976543210');

INSERT INTO Thuoc (MaLoai, MaHangSX, MaNCC, TenThuoc, CongDung, DonGia, SoLuongTon, HanSuDung) VALUES
(1, 1, 1, 'Paracetamol', 'Giảm đau, hạ sốt', 15000, 100, '2026-12-31'),
(2, 2, 2, 'Amoxicillin', 'Kháng sinh, trị nhiễm khuẩn', 25000, 200, '2025-10-20'),
(3, 3, 3, 'Vitamin C', 'Tăng sức đề kháng', 20000, 300, '2027-06-15'),
(4, 4, 4, 'Men tiêu hóa', 'Hỗ trợ tiêu hóa', 30000, 150, '2026-09-10'),
(5, 5, 5, 'Ibuprofen', 'Giảm đau, kháng viêm', 22000, 120, '2025-11-05');

INSERT INTO KhachHang (TenKH, SoDienThoai, DiaChi) VALUES
('Nguyễn Văn A', '0912345678', 'Hà Nội'),
('Trần Thị B', '0923456789', 'TP. Hồ Chí Minh'),
('Lê Văn C', '0934567890', 'Đà Nẵng'),
('Phạm Thị D', '0945678901', 'Cần Thơ'),
('Hoàng Văn E', '0956789012', 'Hải Phòng');

INSERT INTO HoaDon (MaKH, NgayLap, TongTien) VALUES
(1, '2025-03-01', 45000),
(2, '2025-03-02', 75000),
(3, '2025-03-03', 20000),
(4, '2025-03-04', 30000),
(5, '2025-03-05', 22000);

INSERT INTO ChiTietHoaDon (MaHD, MaThuoc, SoLuongBan, GiaBan) VALUES
(1, 1, 2, 15000),
(2, 2, 3, 25000),
(3, 3, 1, 20000),
(4, 4, 1, 30000),
(5, 5, 1, 22000);
