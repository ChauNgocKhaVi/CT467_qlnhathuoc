<?php
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/functions.php';

include __DIR__ . '/../src/partials/header.php';
include __DIR__ . '/../src/partials/head.php';

$type = $_GET['id'] ?? ''; // Nếu không có tham số 'type' thì mặc định là 'thuoc'

// Hiển thị lỗi nếu có
if (isset($_GET['errorKH'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['errorKH']) . '</div>';
}

// Hiển thị lỗi nếu có
if (isset($_GET['errorNCC'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['errorNCC']) . '</div>';
}

// Hiển thị lỗi nếu có
if (isset($_GET['errorLoai'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['errorLoai']) . '</div>';
}

// Hiển thị lỗi nếu có
if (isset($_GET['errorHSX'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['errorHSX']) . '</div>';
}

// Hiển thị lỗi nếu có
if (isset($_GET['error'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
}

// Lấy danh sách thuốc từ CSDL
$stmtThuoc = $pdo->query("SELECT MaThuoc, TenThuoc, DonGia FROM Thuoc");
$thuocList = $stmtThuoc->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách Loại Thuốc
$stmtLoai = $pdo->query("SELECT MaLoai, TenLoai FROM LoaiThuoc");
$loaiThuocList = $stmtLoai->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách Hãng Sản Xuất
$stmtHang = $pdo->query("SELECT MaHangSX, TenHang FROM HangSX");
$hangSXList = $stmtHang->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách Nhà Cung Cấp
$stmtNCC = $pdo->query("SELECT MaNCC, TenNCC FROM NhaCungCap");
$nccList = $stmtNCC->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách khách hàng từ CSDL
$stmt = $pdo->query("SELECT MaKH, TenKH FROM KhachHang ORDER BY TenKH ASC");
$khachHangs = $stmt->fetchAll(PDO::FETCH_ASSOC);




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
    <!-- Form Thêm Hóa Đơn -->
    <form action="process_add_HD.php" method="POST" class="form-HD">
        <div class="mb-3">
            <label for="MaKH" class="form-label">Khách Hàng</label>
            <select class="form-control" id="MaKH" name="MaKH" required onchange="toggleNewCustomerFields(this)">
                <option value="">-- Chọn Khách Hàng --</option>
                <?php foreach ($khachHangs as $kh): ?>
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
                <input type="text" class="form-control" id="SoDienThoai" name="SoDienThoai">
            </div>
            <div class="mb-3">
                <label for="DiaChi" class="form-label">Địa Chỉ</label>
                <input type="text" class="form-control" id="DiaChi" name="DiaChi">
            </div>
        </div>

        <div class="mb-3">
            <label for="NgayLap" class="form-label">Ngày Lập</label>
            <input type="date" class="form-control" id="NgayLap" name="NgayLap" value="<?= date('Y-m-d') ?>" required>
        </div>


        <!-- Chọn thuốc và nhập số lượng, giá bán -->
        <div class="mb-3">
            <label class="form-label">Chọn Thuốc</label>
            <div id="thuocContainer">
                <div class="thuoc-row d-flex gap-2 mb-2">
                    <select class="form-control w-25" name="MaThuoc[]" onchange="updateDonGia(this)">
                        <option value="">-- Chọn Thuốc --</option>
                        <?php foreach ($thuocList as $thuoc): ?>
                            <option value="<?= $thuoc['MaThuoc'] ?>" data-dongia="<?= $thuoc['DonGia'] ?>">
                                <?= htmlspecialchars($thuoc['TenThuoc']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" class="form-control w-25" name="DonGia[]" placeholder="Đơn Giá" readonly>
                    <input type="number" class="form-control w-25" name="SoLuongBan[]" placeholder="Số lượng" min="1"
                        required>
                    <input type="number" class="form-control w-25" name="GiaBan[]" placeholder="Giá bán" min="0" step="0.01"
                        required>
                    <button type="button" class="mt-2 btn btn-danger btn-remove-x" onclick="removeThuocRow(this)">X</button>
                </div>
            </div>
            <button type="button" class="btn btn-success mt-2" onclick="addThuocRow()">+ Thêm Thuốc</button>
        </div>

        <div class="mb-3">
            <label for="TongTien" class="form-label">Tổng Tiền</label>
            <input type="text" class="form-control" id="TongTien" name="TongTien" readonly>
        </div>


        <div class="d-flex justify-content-center gap-3">
            <a href="index.php" class="btn btn-danger w-50 text-center">Hủy</a>
            <button type="submit" class="btn btn-primary w-50">Lưu</button>
        </div>
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

<!-- Thêm hóa đơn -->
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

    document.addEventListener("DOMContentLoaded", function () {
        // Cập nhật giá bán khi thay đổi số lượng hoặc chọn thuốc
        function updateGiaBan(row) {
            let donGia = parseFloat(row.querySelector("[name='DonGia[]']").value) || 0;
            let soLuong = parseFloat(row.querySelector("[name='SoLuongBan[]']").value) || 0;
            let giaBanInput = row.querySelector("[name='GiaBan[]']");

            // Tính giá bán = Đơn giá * Số lượng
            let giaBan = donGia * soLuong;
            giaBanInput.value = giaBan.toFixed(2);

            updateTongTien(); // Cập nhật tổng tiền
        }

        // Cập nhật tổng tiền khi thay đổi dữ liệu
        function updateTongTien() {
            let total = 0;
            document.querySelectorAll("#thuocContainer .thuoc-row").forEach(row => {
                let giaBan = parseFloat(row.querySelector("[name='GiaBan[]']").value) || 0;
                total += giaBan;
            });
            document.getElementById("TongTien").value = total.toFixed(2);
        }

        // Khi chọn thuốc -> Tự động cập nhật Đơn giá & Giá bán
        function updateDonGia(selectElement) {
            let selectedOption = selectElement.options[selectElement.selectedIndex];
            let donGia = parseFloat(selectedOption.getAttribute("data-dongia")) || 0;

            let row = selectElement.closest(".thuoc-row");
            let donGiaInput = row.querySelector("[name='DonGia[]']");
            donGiaInput.value = donGia.toFixed(2);

            updateGiaBan(row); // Tự động cập nhật giá bán
        }

        // Khi thay đổi số lượng, tự động cập nhật Giá bán
        document.addEventListener("input", function (event) {
            if (event.target.matches("[name='SoLuongBan[]']")) {
                let row = event.target.closest(".thuoc-row");
                updateGiaBan(row);
            }
        });

        // Khi chọn thuốc, cập nhật Đơn giá và Giá bán
        document.querySelectorAll("#thuocContainer select[name='MaThuoc[]']").forEach(select => {
            select.addEventListener("change", function () {
                updateDonGia(this);
            });
        });

        // Thêm dòng thuốc mới
        function addThuocRow() {
            let container = document.getElementById("thuocContainer");
            let newRow = container.firstElementChild.cloneNode(true);

            // Xóa dữ liệu cũ
            newRow.querySelectorAll("input, select").forEach(input => input.value = "");

            // Gán sự kiện onchange mới cho select
            newRow.querySelector("select[name='MaThuoc[]']").addEventListener("change", function () {
                updateDonGia(this);
            });

            container.appendChild(newRow);
        }

        // Xóa dòng thuốc
        function removeThuocRow(button) {
            let container = document.getElementById("thuocContainer");
            if (container.children.length > 1) {
                button.parentElement.remove();
                updateTongTien(); // Cập nhật tổng tiền sau khi xóa
            }
        }

        // Gán sự kiện cho nút thêm thuốc
        document.querySelector(".btn-success").addEventListener("click", addThuocRow);
    });
</script>
<style>
    .form-HD {
        width: 80%;
        max-width: 600px;
        margin: 0 auto;
    }

    .btn-remove-x {
        padding: 2px 6px;
        font-size: 14px;
        line-height: 1;
        width: 30px;
        /* Giảm chiều rộng */
        height: 30px;
        /* Giảm chiều cao */
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>