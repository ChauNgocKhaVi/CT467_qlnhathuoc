<?php
require_once __DIR__ . '/../src/bootstrap.php';
include __DIR__ . '/../src/partials/header.php';
include __DIR__ . '/../src/partials/head.php';

$type = $_GET['id'] ?? ''; // Nếu không có tham số 'type' thì mặc định là 'thuoc'
// Lấy danh sách khách hàng từ CSDL
$stmt = $pdo->query("SELECT MaKH, TenKH FROM KhachHang ORDER BY TenKH ASC");
$khachHangs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="text-center">Thêm mới</h2>
<?php if ($type == 'formThuoc'): ?>
<!-- Form Thêm Thuốc -->
<form action="your_action.php" method="POST">
    <div class="mb-3">
        <label for="tenThuoc" class="form-label">Tên Thuốc</label>
        <input type="text" class="form-control" id="tenThuoc" name="tenThuoc" required>
    </div>
    <div class="mb-3">
        <label for="maLoai" class="form-label">Mã Loại</label>
        <input type="text" class="form-control" id="maLoai" name="maLoai" required>
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
<form action="process_add_loai.php" method="POST">
    <div class="mb-3">
        <label for="tenLoai" class="form-label">Tên Loại</label>
        <input type="text" class="form-control" id="tenLoai" name="tenLoai" required>
    </div>
    <div class="mb-3">
        <label for="donViTinh" class="form-label">Đơn Vị Tính</label>
        <input type="text" class="form-control" id="donViTinh" name="donViTinh" required>
    </div>
    <div class="d-flex justify-content-center gap-3">
        <a href="index.php" class="btn btn-danger w-50 text-center">Hủy</a>
        <button type="submit" class="btn btn-primary w-50">Lưu</button>
    </div>
</form>

<?php elseif ($type == 'formNCC'): ?>
<!-- Form Thêm Nhà Cung Cấp -->
<form action="process_add_ncc.php" method="POST">
    <div class="mb-3">
        <label for="tenNCC" class="form-label">Tên Nhà Cung Cấp</label>
        <input type="text" class="form-control" id="tenNCC" name="tenNCC" required>
    </div>
    <div class="mb-3">
        <label for="soDienThoai" class="form-label">Số Điện Thoại</label>
        <input type="text" class="form-control" id="soDienThoai" name="soDienThoai" required>
    </div>
    <div class="d-flex justify-content-center gap-3">
        <a href="index.php" class="btn btn-danger w-50 text-center">Hủy</a>
        <button type="submit" class="btn btn-primary w-50">Lưu</button>
    </div>
</form>

<?php elseif ($type == 'formHangSX'): ?>
<!-- Form Thêm Hãng Sản Xuất -->
<form action="process_add_hangsx.php" method="POST">
    <div class="mb-3">
        <label for="tenHang" class="form-label">Tên Hãng Sản Xuất</label>
        <input type="text" class="form-control" id="tenHang" name="tenHang" required>
    </div>
    <div class="mb-3">
        <label for="quocGia" class="form-label">Quốc Gia</label>
        <input type="text" class="form-control" id="quocGia" name="quocGia" required>
    </div>
    <div class="d-flex justify-content-center gap-3">
        <a href="index.php" class="btn btn-danger w-50 text-center">Hủy</a>
        <button type="submit" class="btn btn-primary w-50">Lưu</button>
    </div>
</form>

<?php elseif ($type == 'formKH'): ?>
<!-- Form Thêm Khách Hàng -->
<form action="process_add_KH.php" method="POST">
    <div class="mb-3">
        <label for="TenKH" class="form-label">Tên Khách Hàng</label>
        <input type="text" class="form-control" id="TenKH" name="TenKH" required>
    </div>
    <div class="mb-3">
        <label for="SoDienThoai" class="form-label">Số Điện Thoại</label>
        <input type="text" class="form-control" id="SoDienThoai" name="SoDienThoai" required pattern="\d{10,15}"
            title="Nhập số điện thoại hợp lệ">
    </div>
    <div class="mb-3">
        <label for="DiaChi" class="form-label">Địa Chỉ</label>
        <textarea class="form-control" id="DiaChi" name="DiaChi" required></textarea>
    </div>
    <div class="d-flex justify-content-center gap-3">
        <a href="index.php" class="btn btn-danger w-50 text-center">Hủy</a>
        <button type="submit" class="btn btn-primary w-50">Lưu</button>
    </div>
</form>

<?php elseif ($type == 'formHD'): ?>
<form action="process_add_HD.php" method="POST">
    <div class="mb-3">
        <label for="MaKH" class="form-label">Khách Hàng</label>
        <select class="form-control" id="MaKH" name="MaKH" required onchange="toggleNewCustomerFields(this)">
            <option value="">-- Chọn Khách Hàng --</option>
            <?php foreach ($khachHangs as $kh) : ?>
            <option value="<?= $kh['MaKH'] ?>"><?= htmlspecialchars($kh['TenKH']) ?></option>
            <?php endforeach; ?>
            <option value="new">Thêm khách hàng mới</option>
        </select>
    </div>

    <!-- Trường nhập thông tin khách hàng mới -->
    <div id="newCustomerFields" style="display: none;">
        <div class="mb-3">
            <label for="TenKH" class="form-label">Tên Khách Hàng</label>
            <input type="text" class="form-control" id="TenKH" name="TenKH">
        </div>
        <div class="mb-3">
            <label for="SoDienThoai" class="form-label">Số Điện Thoại</label>
            <input type="text" class="form-control" id="SoDienThoai" name="SoDienThoai" pattern="\d{10,15}"
                title="Nhập số điện thoại hợp lệ">
        </div>
        <div class="mb-3">
            <label for="DiaChi" class="form-label">Địa Chỉ</label>
            <textarea class="form-control" id="DiaChi" name="DiaChi"></textarea>
        </div>
    </div>

    <div class="mb-3">
        <label for="NgayLap" class="form-label">Ngày Lập</label>
        <input type="date" class="form-control" id="NgayLap" name="NgayLap" value="<?= date('Y-m-d') ?>" required>
    </div>
    <div class="mb-3">
        <label for="TongTien" class="form-label">Tổng Tiền</label>
        <input type="number" class="form-control" id="TongTien" name="TongTien" min="0" step="0.01" required>
    </div>

    <div class="d-flex justify-content-center gap-3">
        <a href="index.php" class="btn btn-danger w-50 text-center">Hủy</a>
        <button type="submit" class="btn btn-primary w-50">Lưu</button>
    </div>
</form>


<?php elseif ($type == 'formNhanVien'): ?>
<!-- Form Thêm Tài Khoản Nhân viên / Admin -->
<form action="your_action.php" method="POST">
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
<script>
function toggleNewCustomerFields(select) {
    var newCustomerFields = document.getElementById("newCustomerFields");
    if (select.value === "new") {
        newCustomerFields.style.display = "block";
        document.getElementById("TenKH").setAttribute("required", "required");
        document.getElementById("SoDienThoai").setAttribute("required", "required");
        document.getElementById("DiaChi").setAttribute("required", "required");
    } else {
        newCustomerFields.style.display = "none";
        document.getElementById("TenKH").removeAttribute("required");
        document.getElementById("SoDienThoai").removeAttribute("required");
        document.getElementById("DiaChi").removeAttribute("required");
    }
}
</script>