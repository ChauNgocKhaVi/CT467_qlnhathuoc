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
$hoadonList = layHoaDonBanThuoc($pdo); // Gọi hàm lấy danh sách hóa đơn


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
</style>

<div class="container-fluid mt-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="sidebar">
                <ul class="nav flex-column mt-3">
                    <li class="nav-item">
                        <a class="nav-link" href="#thuoc" id="showThuoc"><strong>Danh sách thuốc</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#phieuNhap" id="showPhieuNhap"><strong>Phiếu nhập thuốc</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#hoaDon" id="showHoadon"><strong>Hóa đơn bán thuốc</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#thongKe" id="showThongKe"><strong>Thống kê doanh thu</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tonKho" id="showTonKho"><strong>Báo cáo tồn kho</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#NCC" id="showNCC"><strong>Quản lý nhà cung cấp</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#HSX" id="showHSX"><strong>Quản lý hãng sản xuất</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#nhapThuoc" id="showNhapThuoc"><strong>Nhập thuốc từ
                                Excel</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#xuatFile" id="showXuatFile"><strong>Xuất danh sách thuốc ra
                                Excel</strong></a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-9">
            <div class="account-info">
                <div class="container" id="thuoc">
                    <h2 class="section-title bg-light p-2 rounded potta-one-regular">Danh Sách Thuốc</h2>
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Mã Thuốc</th>
                                <th>Tên Thuốc</th>
                                <th>Loại</th>
                                <th>Hãng Sản Xuất</th>
                                <th>Nhà Cung Cấp</th>
                                <th>Giá</th>
                                <th>Số Lượng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($thuocList as $thuoc): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($thuoc['MaThuoc']); ?></td>
                                    <td><?php echo htmlspecialchars($thuoc['TenThuoc']); ?></td>
                                    <td><?php echo htmlspecialchars($thuoc['TenLoai']); ?></td>
                                    <td><?php echo htmlspecialchars($thuoc['TenHang']); ?></td>
                                    <td><?php echo htmlspecialchars($thuoc['TenNCC']); ?></td>
                                    <td><?php echo number_format($thuoc['Gia'], 0, ',', '.'); ?> VNĐ</td>
                                    <td><?php echo htmlspecialchars($thuoc['SoLuong']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Truyện Hóa đơn -->
            <div class="account-info">
                <div class="container" id="hoaDon">
                    <h2 class="section-title bg-light p-2 rounded potta-one-regular ">Hóa Đơn</h2>
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Mã Hóa Đơn</th>
                                <th>Tên Khách Hàng</th>
                                <th>Số Điện Thoại</th>
                                <th>Ngày Lập</th>
                                <th>Tổng tiền</th>
                                <th>Chi Tiết</th>
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
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
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
            { linkId: 'showPhieuNhap', sectionId: 'phieuNhap' },
            { linkId: 'showHoadon', sectionId: 'hoaDon' },
            { linkId: 'showThongKe', sectionId: 'thongKe' },
            { linkId: 'showTonKho', sectionId: 'tonKho' },
            { linkId: 'showNCC', sectionId: 'NCC' },
            { linkId: 'showHSX', sectionId: 'HSX' },
            { linkId: 'showNhapThuoc', sectionId: 'nhapThuoc' },
            { linkId: 'showXuatFile', sectionId: 'xuatFile' }
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