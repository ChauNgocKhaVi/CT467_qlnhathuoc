<?php
session_start();
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/functions.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
} elseif (!empty($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Xóa session sau khi hiển thị
}

$thuocList = layTatCaThuoc($pdo); // Gọi hàm lấy danh sách thuốc
$loaiList = layTatCaLoaiThuoc($pdo); // Gọi hàm lấy danh sách loại thuốc
$nccList = layTatCaNhaCungCap($pdo); // Gọi hàm lấy danh sách nhà cung cấp
$hsxList = layTatCaHangSanXuat($pdo); // Gọi hàm lấy danh sách hãng sản xuất
$KHList = layTatCaKhachHang($pdo); // Gọi hàm lấy danh sách khách hàng
$hoadonList = layHoaDonBanThuoc($pdo); // Gọi hàm lấy danh sách hóa đơn
$nvList = layTatCaNhanVien($pdo); // Gọi hàm lấy danh sách nhân viên

include __DIR__ . '/../src/partials/head.php';
include __DIR__ . '/../src/partials/header.php';
?>

<style>
    .sidebar {
        background: linear-gradient(135deg, #74ebd5, #acb6e5);
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 5px 8px rgba(0, 0, 0, 0.1);
    }

    .nav-link {
        color: #000;
        padding: 12px 15px;
        margin-bottom: 5px;
        border-radius: 5px;
        display: block;
        transition: background-color 0.3s, color 0.3s;
    }

    .nav-item {
        list-style: none;
    }

    .nav-link:hover {
        background-color: #e9ecef;
        /* Màu xám nhạt */
        color: #000;
        /* Giữ nguyên màu chữ */
    }

    .nav-link.active {
        background-color: #073A4B;
        color: white;
    }

    .row {
        display: flex;
        justify-content: space-between;
    }

    .account-info {
        display: flex;
        align-items: center;
        position: relative;
    }

    .section-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        position: relative;
    }

    .section-title::after {
        content: '';
        display: block;
        width: 100px;
        height: 4px;
        background-color: rgb(9, 25, 208);
        /* Màu hồng */
        margin-top: 5px;
    }

    .d-flex .form-control {
        width: 150px;
    }

    #searchBtn {
        height: 35px;
        margin-top: 6px;
        /* Điều chỉnh cho phù hợp */
    }
</style>

<div class="container-fluid mt-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2">
            <div class="sidebar">
                <ul class="nav flex-column mt-3">
                    <li class="nav-item">
                        <a class="nav-link" href="#thuoc" id="showThuoc"><strong>Quản lý thuốc</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#loai" id="showLoai"><strong>Quản lý loại thuốc</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#ncc" id="showNCC"><strong>Quản lý nhà cung cấp</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#hsx" id="showHSX"><strong>Quản lý hãng sản xuất</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#khachHang" id="showKhachHang"><strong>Quản lý khách hàng</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#hoaDon" id="showHoadon"><strong>Quản lý hóa đơn</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#thongKe" id="showThongKe"><strong>Thống kê doanh thu</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tonKho" id="showTonKho"><strong>Báo cáo tồn kho</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#nhapThuoc" id="showNhapThuoc"><strong>Nhập thuốc từ
                                Excel</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#xuatFile" id="showXuatFile"><strong>Xuất danh sách thuốc ra
                                Excel</strong></a>
                    </li>
                    <?php
                    echo '<li class="nav-item">
                                <a class="nav-link" href="#nhanVien" id="showNhanVien"><strong>Quản lý nhân viên</strong></a>
                            </li>'
                        ?>
                </ul>
            </div>
        </div>

        <div class="col-md-10">
            <!-- Thuốc -->
            <div class="account-info">
                <div class="container-fluid" id="thuoc">
                    <h2 class="section-title bg-light p-2 rounded potta-one-regular">Danh Sách Thuốc</h2>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <!-- Tìm kiếm -->
                        <div class="d-flex">
                            <button id="searchBtn" class="btn btn-primary me-2">Tìm kiếm</button>
                            <input type="text" id="maThuoc" class="form-control me-2" placeholder="Mã thuốc" />
                            <input type="text" id="maLoai" class="form-control me-2" placeholder="Mã loại" />
                            <input type="text" id="maHangSX" class="form-control me-2" placeholder="Mã hãng SX" />
                            <input type="text" id="tenThuoc" class="form-control me-2" placeholder="Tên thuốc" />
                            <input type="number" id="donGia" class="form-control me-2" placeholder="Đơn giá" />
                            <input type="number" id="soLuongTon" class="form-control me-2" placeholder="Số lượng tồn" />
                            <input type="date" id="hanSuDung" class="form-control me-2" placeholder="Hạn sử dụng" />
                        </div>

                        <!-- Thêm mới -->
                        <div class="d-flex justify-content-end">
                            <a href="add.php?id=formThuoc" id="Thuoc" class="btn btn-primary">Thêm mới</a>
                        </div>
                    </div>

                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center">Mã Thuốc</th>
                                <th>Tên Thuốc</th>
                                <th>Loại</th>
                                <th>Hãng Sản Xuất</th>
                                <th>Nhà Cung Cấp</th>
                                <th>Công dụng</th>
                                <th>Giá (VNĐ)</th>
                                <th class="text-center">Số Lượng</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($thuocList as $thuoc): ?>
                                <tr>
                                    <td class="text-center"><?php echo htmlspecialchars($thuoc['MaThuoc']); ?></td>
                                    <td><?php echo htmlspecialchars($thuoc['TenThuoc']); ?></td>
                                    <td><?php echo htmlspecialchars($thuoc['TenLoai']); ?></td>
                                    <td><?php echo htmlspecialchars($thuoc['TenHang']); ?></td>
                                    <td><?php echo htmlspecialchars($thuoc['TenNCC']); ?></td>
                                    <td><?php echo htmlspecialchars($thuoc['CongDung']); ?></td>
                                    <td><?php echo number_format($thuoc['Gia'], 0, ',', '.'); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($thuoc['SoLuong']); ?></td>
                                    <td>
                                        <a href="#" class="btn btn-primary">Sửa</a>
                                        <a href="#" class="btn btn-danger">Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Loại thuốc -->
            <div class="container-fluid-fluid" id="loai">
                <h2 class="section-title bg-light p-2 rounded potta-one-regular">Danh Sách Loại</h2>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Tìm kiếm -->
                    <div class="d-flex">
                        <button id="searchBtn" class="btn btn-primary me-2">Tìm kiếm</button>
                        <input type="text" id="maloai" class="form-control me-2" placeholder="Mã loại" />
                        <input type="text" id="tenloai" class="form-control me-2" placeholder="Tên loại" />
                        <input type="text" id="DonViTinh" class="form-control me-2" placeholder="Đơn vị tính" />
                    </div>

                    <!-- Thêm mới -->
                    <div class="d-flex justify-content-end">
                        <a href="add.php?id=formLoai" id="Loai" class="btn btn-primary">Thêm mới</a>
                    </div>
                </div>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">Mã Loại</th>
                            <th>Tên Loại</th>
                            <th>Đơn vị tính</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($loaiList as $loai): ?>
                            <tr>
                                <td class="text-center"><?php echo htmlspecialchars($loai['MaLoai']); ?></td>
                                <td><?php echo htmlspecialchars($loai['TenLoai']); ?></td>
                                <td><?php echo htmlspecialchars($loai['DonViTinh']); ?></td>
                                <td>
                                    <a href="#" class="btn btn-primary">Sửa</a>
                                    <a href="#" class="btn btn-danger">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Nhà cung cấp -->
            <div class="container-fluid-fluid" id="ncc">
                <h2 class="section-title bg-light p-2 rounded potta-one-regular">Danh Sách Nhà Cung Cấp</h2>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Tìm kiếm -->
                    <div class="d-flex">
                        <button id="searchBtn" class="btn btn-primary me-2">Tìm kiếm</button>
                        <input type="text" id="maNCC" class="form-control me-2" placeholder="Mã Nhà cung cấp" />
                        <input type="text" id="tenNCC" class="form-control me-2" placeholder="Mã Nhà cung cấp" />
                        <input type="text" id="SoDienThoai" class="form-control me-2" placeholder="Số điện thoại" />
                    </div>

                    <!-- Thêm mới -->
                    <div class="d-flex justify-content-end">
                        <a href="add.php?id=formNCC" id="NCC" class="btn btn-primary">Thêm mới</a>
                    </div>
                </div>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">Mã Nhà Cung Cấp</th>
                            <th>Tên Nhà Cung Cấp</th>
                            <th>Số Điện Thoại/th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($nccList as $ncc): ?>
                            <tr>
                                <td class="text-center"><?php echo htmlspecialchars($ncc['MaNCC']); ?></td>
                                <td><?php echo htmlspecialchars($ncc['TenNCC']); ?></td>
                                <td><?php echo htmlspecialchars($ncc['SoDienThoai']); ?></td>
                                <td>
                                    <a href="#" class="btn btn-primary">Sửa</a>
                                    <a href="#" class="btn btn-danger">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Hãng sản xuất -->
            <div class="container-fluid-fluid" id="hsx">
                <h2 class="section-title bg-light p-2 rounded potta-one-regular">Danh Sách Hãng Sản Xuất</h2>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Tìm kiếm -->
                    <div class="d-flex">
                        <button id="searchBtn" class="btn btn-primary me-2">Tìm kiếm</button>
                        <input type="text" id="maHangSX" class="form-control me-2" placeholder="Mã hãng sản xuất" />
                        <input type="text" id="TenHang" class="form-control me-2" placeholder="Tên hãng sản xuất" />
                        <input type="text" id="QuocGia" class="form-control me-2" placeholder="Quốc gia" />
                    </div>

                    <!-- Thêm mới -->
                    <div class="d-flex justify-content-end">
                        <a href="add.php?id=formHangSX" id="HangSX" class="btn btn-primary">Thêm mới</a>
                    </div>
                </div>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">Mã Hãng Sản Xuất</th>
                            <th>Tên Hãng Sản Xuất</th>
                            <th>Quốc Gia</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($hsxList as $hsx): ?>
                            <tr>
                                <td class="text-center"><?php echo htmlspecialchars($hsx['MaHangSX']); ?></td>
                                <td><?php echo htmlspecialchars($hsx['TenHang']); ?></td>
                                <td><?php echo htmlspecialchars($hsx['QuocGia']); ?></td>
                                <td>
                                    <a href="#" class="btn btn-primary">Sửa</a>
                                    <a href="#" class="btn btn-danger">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Khách hàng -->
            <div class="container-fluid-fluid" id="khachHang">
                <h2 class="section-title bg-light p-2 rounded potta-one-regular">Danh Sách Khách Hàng</h2>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Tìm kiếm -->
                    <div class="d-flex">
                        <button id="searchBtn" class="btn btn-primary me-2">Tìm kiếm</button>
                        <input type="text" id="MaKH" class="form-control me-2" placeholder="Mã khách hàng" />
                        <input type="text" id="TenKH" class="form-control me-2" placeholder="Tên khách hàng" />
                        <input type="text" id="SoDienThoai" class="form-control me-2" placeholder="Số điện thoại" />
                    </div>
                </div>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">Mã Khách Hàng</th>
                            <th>Tên Khách Hàng</th>
                            <th>Số Điện Thoại</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($KHList as $KH): ?>
                            <tr>
                                <td class="text-center"><?php echo htmlspecialchars($KH['MaKH']); ?></td>
                                <td><?php echo htmlspecialchars($KH['TenKH']); ?></td>
                                <td><?php echo htmlspecialchars($KH['SoDienThoai']); ?></td>
                                <td>
                                    <a href="#" class="btn btn-primary">Sửa</a>
                                    <a href="#" class="btn btn-danger">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Hóa đơn -->
            <div class="account-info">
                <div class="container-fluid" id="hoaDon">
                    <h2 class="section-title bg-light p-2 rounded potta-one-regular ">Hóa Đơn</h2>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <!-- Tìm kiếm -->
                        <div class="d-flex">
                            <button id="searchBtn" class="btn btn-primary me-2">Tìm kiếm</button>
                            <input type="text" id="MaHD" class="form-control me-2" placeholder="Mã hóa đơn" />
                            <input type="text" id="MaKH" class="form-control me-2" placeholder="Mã khách hàng" />
                            <input type="text" id="NgayLap" class="form-control me-2" placeholder="Ngày lập" />
                            <input type="text" id="TongTien" class="form-control me-2" placeholder="Tổng tiền" />
                        </div>

                        <!-- Thêm mới -->
                        <div class="d-flex justify-content-end">
                            <a href="add.php?id=hoaDon" id="HoaDon" class="btn btn-primary">Thêm mới</a>
                        </div>
                    </div>
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Mã Hóa Đơn</th>
                                <th>Tên Khách Hàng</th>
                                <th>Số Điện Thoại</th>
                                <th>Ngày Lập</th>
                                <th>Tổng tiền</th>
                                <th>Chi Tiết</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($hoadonList as $hoadon): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($hoadon['MaHD']); ?></td>
                                    <td><?php echo htmlspecialchars($hoadon['TenKH']); ?></td>
                                    <td><?php echo htmlspecialchars($hoadon['SoDienThoai']); ?></td>
                                    <td><?php echo htmlspecialchars($hoadon['NgayLap']); ?></td>
                                    <td><?php echo htmlspecialchars($hoadon['TongTien']); ?></td>
                                    <td>Xem chi tiết</td>
                                    <td>
                                        <a href="#" class="btn btn-primary">Sửa</a>
                                        <a href="#" class="btn btn-danger">Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Nhân viên -->
            <div class="account-info">
                <div class="container-fluid" id="nhanVien">
                    <h2 class="section-title bg-light p-2 rounded potta-one-regular ">Nhân viên</h2>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <!-- Tìm kiếm -->
                        <div class="d-flex">
                            <button id="searchBtn" class="btn btn-primary me-2">Tìm kiếm</button>
                            <input type="text" id="MaND" class="form-control me-2" placeholder="Mã nhân viên" />
                            <input type="text" id="HoTen" class="form-control me-2" placeholder="Họ và Tên" />
                            <input type="text" id="TenDangNhap" class="form-control me-2" placeholder="Tên đăng nhập" />
                            <input type="text" id="Email" class="form-control me-2" placeholder="Email" />
                            <input type="text" id="SoDienThoai" class="form-control me-2" placeholder="Số điện thoại" />
                            <input type="text" id="VaiTro" class="form-control me-2" placeholder="Vai trò" />
                            <input type="text" id="TrangThai" class="form-control me-2" placeholder="Trạng thái" />
                        </div>

                        <!-- Thêm mới -->
                        <div class="d-flex justify-content-end">
                            <a href="add.php?id=formNhanVien" id="NhanVien" class="btn btn-primary">Thêm mới</a>
                        </div>
                    </div>
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Mã Nhân Viên</th>
                                <th>Họ và Tên</th>
                                <th>Số Điện Thoại</th>
                                <th>Tên đăng nhập</th>
                                <th>Email</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($nvList as $nv): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($nv['MaND']); ?></td>
                                    <td><?php echo htmlspecialchars($nv['HoTen']); ?></td>
                                    <td><?php echo htmlspecialchars($nv['TenDangNhap']); ?></td>
                                    <td><?php echo htmlspecialchars($nv['Email']); ?></td>
                                    <td><?php echo htmlspecialchars($nv['SoDienThoai']); ?></td>
                                    <td><?php echo htmlspecialchars($nv['VaiTro']); ?></td>
                                    <td><?php echo htmlspecialchars($nv['TrangThai']); ?></td>
                                    <td>
                                        <a href="#" class="btn btn-primary">Sửa</a>
                                        <a href="#" class="btn btn-danger">Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Thống kê danh thu -->
            <!-- Báo cáo tồn kho -->
            <!-- Nhập thuốc từ Excel -->
            <!-- Xuất file Excel -->
        </div>
    </div>
</div>

<?php if (!empty($success_message)): ?>
    <div id="success-alert" class="alert alert-success text-center">
        <?php echo htmlspecialchars($success_message); ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            setTimeout(function () {
                $("#success-alert").fadeOut(500);
            }, 5000);
        });
    </script>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuItems = [
            { linkId: 'showThuoc', sectionId: 'thuoc' },
            { linkId: 'showLoai', sectionId: 'loai' },
            { linkId: 'showHoadon', sectionId: 'hoaDon' },
            { linkId: 'showThongKe', sectionId: 'thongKe' },
            { linkId: 'showTonKho', sectionId: 'tonKho' },
            { linkId: 'showNCC', sectionId: 'ncc' },
            { linkId: 'showHSX', sectionId: 'hsx' },
            { linkId: 'showNhapThuoc', sectionId: 'nhapThuoc' },
            { linkId: 'showKhachHang', sectionId: 'khachHang' },
            { linkId: 'showXuatFile', sectionId: 'xuatFile' },
            { linkId: 'showNhanVien', sectionId: 'nhanVien' }
        ];

        function hideAllSections() {
            menuItems.forEach(item => {
                const section = document.getElementById(item.sectionId);
                if (section) {
                    section.style.display = 'none';
                }
            });
        }

        function removeActiveClass() {
            menuItems.forEach(item => {
                const link = document.getElementById(item.linkId);
                if (link) {
                    link.classList.remove('active');
                }
            });
        }

        function showSection(sectionId, linkId) {
            hideAllSections();
            removeActiveClass();

            const section = document.getElementById(sectionId);
            const link = document.getElementById(linkId);

            if (section) {
                section.style.display = 'block';
            }
            if (link) {
                link.classList.add('active');
            }
        }

        // Ẩn tất cả sections trước
        hideAllSections();

        // Hiển thị mặc định phần "thuoc" và đánh dấu menu active
        showSection('thuoc', 'showThuoc');

        menuItems.forEach(item => {
            const link = document.getElementById(item.linkId);
            if (link) {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    showSection(item.sectionId, item.linkId);
                });
            }
        });
    });
</script>

<script>
    // Hiển thị thông báo đăng nhập thành công nếu có
    document.addEventListener('DOMContentLoaded', function () {
        <?php if (!empty($successMessage)): ?>
            Swal.fire({
                title: "Đăng nhập thành công!",
                text: "<?php echo htmlspecialchars($successMessage); ?>",
                icon: "success",
                confirmButtonText: "OK"
            });
        <?php endif; ?>
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php include __DIR__ . '/../src/partials/footer.php'; ?>