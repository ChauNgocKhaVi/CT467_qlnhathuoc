DROP DATABASE IF EXISTS qlnhathuoc;
CREATE DATABASE qlnhathuoc;
USE qlnhathuoc;

CREATE TABLE Admin (
    MaND INT AUTO_INCREMENT PRIMARY KEY,
    HoTen VARCHAR(100) NOT NULL,
    TenDangNhap VARCHAR(50) UNIQUE NOT NULL, -- các giá trị trong một cột (hoặc tập hợp cột) không được trùng lặp
    MatKhau VARCHAR(255) NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL,
    SoDienThoai VARCHAR(15) NOT NULL,
    VaiTro ENUM('admin', 'nhanvien') NOT NULL DEFAULT 'nhanvien',
    TrangThai ENUM('active', 'inactive') DEFAULT 'active'
);

CREATE TABLE LoaiThuoc (
    MaLoai INT AUTO_INCREMENT PRIMARY KEY,
    TenLoai VARCHAR(100),
    DonViTinh VARCHAR(20)
);

CREATE TABLE HangSX (
    MaHangSX INT AUTO_INCREMENT PRIMARY KEY,
    TenHang VARCHAR(100) NOT NULL,
    QuocGia VARCHAR(50) NOT NULL
);

CREATE TABLE NhaCungCap (
    MaNCC INT AUTO_INCREMENT PRIMARY KEY,
    TenNCC VARCHAR(100) NOT NULL,
    SoDienThoai VARCHAR(15) NOT NULL
);

CREATE TABLE Thuoc (
    MaThuoc INT AUTO_INCREMENT PRIMARY KEY,
    MaLoai INT NOT NULL,
    MaHangSX INT NOT NULL,
    MaNCC INT NOT NULL,
    TenThuoc VARCHAR(100) NOT NULL,
    CongDung TEXT,
    DonGia DECIMAL(10,2) NOT NULL CHECK (DonGia >= 0),
    SoLuongTon INT DEFAULT 0 CHECK (SoLuongTon >= 0),
    HanSuDung DATE NOT NULL,
    FOREIGN KEY (MaLoai) REFERENCES LoaiThuoc(MaLoai) ON DELETE CASCADE, -- tự động xóa các bản ghi liên quan trong bảng con khi bản ghi trong bảng cha bị xóa
    FOREIGN KEY (MaHangSX) REFERENCES HangSX(MaHangSX) ON DELETE CASCADE,
    FOREIGN KEY (MaNCC) REFERENCES NhaCungCap(MaNCC) ON DELETE CASCADE
);

CREATE TABLE KhachHang (
    MaKH INT AUTO_INCREMENT PRIMARY KEY,
    TenKH VARCHAR(100) NOT NULL,
    SoDienThoai VARCHAR(15) NOT NULL, 
    DiaChi TEXT NOT NULL
);

CREATE TABLE HoaDon (
    MaHD INT AUTO_INCREMENT PRIMARY KEY,
    MaKH INT NULL, -- Cần NULL vì ON DELETE SET NULL
    NgayLap DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    TongTien DECIMAL(10,2) DEFAULT 0 CHECK (TongTien >= 0),
    FOREIGN KEY (MaKH) REFERENCES KhachHang(MaKH) ON DELETE SET NULL 
);

-- ON DELETE SET NULL được sử dụng khi thiết lập mối quan hệ giữa các bảng (FOREIGN KEY - khóa ngoại).
-- Khi một bản ghi trong bảng cha (bảng tham chiếu) bị xóa, 
-- tất cả các bản ghi trong bảng con (bảng chứa khóa ngoại) sẽ được cập nhật thành NULL thay vì bị xóa hoặc giữ nguyên.

CREATE TABLE ChiTietHoaDon (
    MaHD INT NOT NULL,
    MaThuoc INT NOT NULL,
    SoLuongBan INT NOT NULL CHECK (SoLuongBan > 0),
    GiaBan DECIMAL(10,2) NOT NULL CHECK (GiaBan >= 0),
    PRIMARY KEY (MaHD, MaThuoc),
    FOREIGN KEY (MaHD) REFERENCES HoaDon(MaHD) ON DELETE CASCADE,
    FOREIGN KEY (MaThuoc) REFERENCES Thuoc(MaThuoc) ON DELETE CASCADE
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
(1, '2025-03-01', 30000),
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

INSERT INTO Admin (HoTen, TenDangNhap, MatKhau, Email, SoDienThoai, VaiTro, TrangThai) VALUES
('Admin', 'Admin3', '$2y$10$yz4P5huhn0sMci/oUaVqDOpWkdmfmQMJuv/16i41wxBX.pr8vZRVW', 'camtien@gmail.com', '0346667631','admin', 'active');
