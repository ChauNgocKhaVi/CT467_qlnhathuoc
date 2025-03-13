<?php
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/functions.php';

include __DIR__ . '/../src/partials/header.php';
include __DIR__ . '/../src/partials/head.php';

$type = $_GET['id'] ?? ''; // Nếu không có tham số 'type' thì mặc định là 'thuoc'

// Lấy danh sách Loại Thuốc
$stmtLoai = $pdo->query("SELECT MaLoai, TenLoai FROM LoaiThuoc");
$loaiThuocList = $stmtLoai->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách Hãng Sản Xuất
$stmtHang = $pdo->query("SELECT MaHangSX, TenHang FROM HangSX");
$hangSXList = $stmtHang->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách Nhà Cung Cấp
$stmtNCC = $pdo->query("SELECT MaNCC, TenNCC FROM NhaCungCap");
$nccList = $stmtNCC->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .bi-box-arrow-left {
        font-size: 50px;
        margin-left: 20px;
        color: black
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-2 mt-2">
            <a href="index.php">
                <i class="bi bi-box-arrow-left" title="Quay về"></i>
            </a>
        </div>
        <div class="col-8 text-center">
            <h1 class="mb-5 mt-3">Thêm mới</h1>
        </div>
    </div>
</div>

<?php if ($type == 'formThuoc'): ?>
    <!-- Form Thêm Thuốc -->
    <form action="process_add.php" method="POST">
        <input type="hidden" name="action" value="addthuoc">
        <div class="mb-3">
            <label for="tenThuoc" class="form-label">Tên Thuốc</label>
            <input type="text" class="form-control" id="tenThuoc" name="tenThuoc" required>
        </div>

        <div class="mb-3">
            <label for="maLoai" class="form-label">Loại thuốc</label>
            <select class="form-control" id="maLoai" name="maLoai" required>
                <option value="">Chọn loại thuốc</option>
                <?php foreach ($loaiThuocList as $loai): ?>
                    <option value="<?= $loai['MaLoai'] ?>"><?= htmlspecialchars($loai['TenLoai']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="MaHangSX" class="form-label">Hãng sản xuất</label>
            <select class="form-control" id="MaHangSX" name="MaHangSX" required>
                <option value="">Chọn hãng sản xuất</option>
                <?php foreach ($hangSXList as $hang): ?>
                    <option value="<?= $hang['MaHangSX'] ?>"><?= htmlspecialchars($hang['TenHang']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="MaNCC" class="form-label">Nhà cung cấp</label>
            <select class="form-control" id="MaNCC" name="MaNCC" required>
                <option value="">Chọn nhà cung cấp</option>
                <?php foreach ($nccList as $ncc): ?>
                    <option value="<?= $ncc['MaNCC'] ?>"><?= htmlspecialchars($ncc['TenNCC']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="CongDung" class="form-label">Công dụng</label>
            <input type="text" class="form-control" id="CongDung" name="CongDung" required>
        </div>


        <div class="mb-3">
            <label for="donGia" class="form-label">Đơn Giá</label>
            <input type="number" class="form-control" id="donGia" name="donGia" required>
        </div>

        <div class="mb-3">
            <label for="soLuongTon" class="form-label">Số Lượng Tồn</label>
            <input type="number" class="form-control" id="soLuongTon" name="soLuongTon" required>
        </div>

        <div class="mb-3">
            <label for="hanSuDung" class="form-label">Hạn Sử Dụng</label>
            <input type="date" class="form-control" id="hanSuDung" name="hanSuDung" required>
        </div>

        <button type="submit" class="btn btn-primary">Lưu</button>
    </form>

<?php elseif ($type == 'formLoai'): ?>
    <!-- Form Thêm Loại Thuốc -->
    <form action="your_action.php" method="POST">
        <div class="mb-3">
            <label for="tenLoai" class="form-label">Tên Loại</label>
            <input type="text" class="form-control" id="tenLoai" name="tenLoai" required>
        </div>
        <div class="mb-3">
            <label for="donViTinh" class="form-label">Đơn Vị Tính</label>
            <input type="text" class="form-control" id="donViTinh" name="donViTinh" required>
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
    </form>

<?php elseif ($type == 'formNCC'): ?>
    <!-- Form Thêm Nhà Cung Cấp -->
    <form action="your_action.php" method="POST">
        <div class="mb-3">
            <label for="tenNCC" class="form-label">Tên Nhà Cung Cấp</label>
            <input type="text" class="form-control" id="tenNCC" name="tenNCC" required>
        </div>
        <div class="mb-3">
            <label for="soDienThoai" class="form-label">Số Điện Thoại</label>
            <input type="text" class="form-control" id="soDienThoai" name="soDienThoai" required>
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
    </form>

<?php elseif ($type == 'formHangSX'): ?>
    <!-- Form Thêm Hãng Sản Xuất -->
    <form action="your_action.php" method="POST">
        <div class="mb-3">
            <label for="tenHang" class="form-label">Tên Hãng Sản Xuất</label>
            <input type="text" class="form-control" id="tenHang" name="tenHang" required>
        </div>
        <div class="mb-3">
            <label for="quocGia" class="form-label">Quốc Gia</label>
            <input type="text" class="form-control" id="quocGia" name="quocGia" required>
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
    </form>

<?php elseif ($type == 'formHoaDon'): ?>
    <!-- Form Thêm Hóa đơn -->
    <form action="your_action.php" method="POST">




        <button type="submit" class="btn btn-primary">Lưu</button>
    </form>

<?php elseif ($type == 'formNhanVien'): ?>
    <!-- Form Thêm Tài Khoản Nhân viên / Admin -->
    <form action="process_add.php" method="POST">
        <input type="hidden" name="action" value="addnhanvien">
        <div class="mb-3">
            <label for="HoTen" class="form-label">Họ và Tên</label>
            <input type="text" class="form-control" id="HoTen" name="HoTen" required>
        </div>
        <div class="mb-3">
            <label for="TenDangNhap" class="form-label">Tên đăng nhập</label>
            <input type="text" class="form-control" id="TenDangNhap" name="TenDangNhap" required>
        </div>
        <div class="mb-3">
            <label for="Email" class="form-label">Email</label>
            <input type="email" class="form-control" id="Email" name="Email" required>
        </div>
        <div class="mb-3">
            <label for="MatKhau" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" id="MatKhau" name="MatKhau" required>
        </div>
        <div class="mb-3">
            <label for="MatKhauConfirm" class="form-label">Xác nhận mật khẩu</label>
            <input type="password" class="form-control" id="MatKhauConfirm" name="MatKhauConfirm" required>
        </div>
        <div class="mb-3">
            <label for="SoDienThoai" class="form-label">Số Điện Thoại</label>
            <input type="text" class="form-control" id="SoDienThoai" name="SoDienThoai" required>
        </div>
        <div class="mb-3">
            <label for="VaiTro" class="form-label">Vai trò</label>
            <select class="form-control" id="VaiTro" name="VaiTro">
                <option value="admin">Admin</option>
                <option value="nhanvien">Nhân viên</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="TrangThai" class="form-label">Trạng thái</label>
            <select class="form-control" id="TrangThai" name="TrangThai">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
    </form>
<?php else: ?>
    <p>Chọn một loại để thêm mới.</p>
<?php endif; ?>
<?php include __DIR__ . '/../src/partials/footer.php'; ?>