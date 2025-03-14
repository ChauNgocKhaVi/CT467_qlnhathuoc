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

// Kiểm tra xem có thông báo thành công không
if (isset($_SESSION['success_message_import'])) {
    $successMessageImport = $_SESSION['success_message_import'];
    unset($_SESSION['success_message_import']); // Xóa session sau khi hiển thị thông báo
}

$vaitro = ($_SESSION['VaiTro']);

$thuocList = layTatCaThuoc($pdo); // Gọi hàm lấy danh sách thuốc
$loaiList = layTatCaLoaiThuoc($pdo); // Gọi hàm lấy danh sách loại thuốc

foreach ($loaiList as &$loai) { // Dùng tham chiếu (&)
    // Gọi hàm để lấy tổng số lượng thuốc trong kho cho loại này
    $tongSoLuong = tongSoLuongThuocTheoLoai($pdo, $loai['MaLoai']);
    $loai['TongSoLuong'] = $tongSoLuong; // Lưu kết quả vào mảng $loai
}
unset($loai); // Đảm bảo không còn tham chiếu sau vòng lặp
$nccList = layTatCaNhaCungCap($pdo); // Gọi hàm lấy danh sách nhà cung cấp
$hsxList = layTatCaHangSanXuat($pdo); // Gọi hàm lấy danh sách hãng sản xuất
$KHList = layTatCaKhachHang($pdo); // Gọi hàm lấy danh sách khách hàng
$hoadonList = layHoaDonBanThuoc($pdo); // Gọi hàm lấy danh sách hóa đơn
$nvList = layTatCaNhanVien($pdo); // Gọi hàm lấy danh sách nhân viên

capNhatThongBaoThuocHetHan($pdo); // Gọi hàm cập nhật thông báo thuốc sắp hết hạn
$thongBaoList = layThongBaoThuocHetHan($pdo); // Gọi hàm lấy danh sách thông báo thuốc sắp hết hạn
$thuocSapHetHan = layThongBaoThuocHetHan($pdo);
$thuocSapHetHanIds = array_column($thuocSapHetHan, 'MaThuoc'); // Lấy danh sách mã thuốc hết hạn


$chiTietHD = [];
$maHD = '';

if (isset($_GET['MaHD']) && !empty($_GET['MaHD'])) {
    $maHD = $_GET['MaHD'];
    $chiTietHD = layChiTietHoaDon($pdo, $maHD);
}

$successLoai = $_GET['successLoai'] ?? ''; // Lấy thông báo thành công
$successNCC = $_GET['successNCC'] ?? ''; // Lấy thông báo thành công
$successHangSX = $_GET['successHangSX'] ?? ''; // Lấy thông báo thành công
$successKH = $_GET['successKH'] ?? ''; // Lấy thông báo thành công
$successHD = $_GET['successHD'] ?? ''; // Lấy thông báo thành công

include __DIR__ . '/../src/partials/head.php';
include __DIR__ . '/../src/partials/header.php';
?>

<!-- Nếu có thông báo, hiển thị alert -->
<?php if (isset($successMessageImport)): ?>
    <script>
        alert("<?php echo $successMessageImport; ?>");
        // Sau 2 giây sẽ tự động chuyển hướng về index.php
        setTimeout(function () {
            window.location.href = "index.php";
        }, 2000);
    </script>
<?php endif; ?>

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

    .dropdown .btn {
        width: 180px;
        /* Độ rộng tương tự input */
        text-align: left;
        /* Canh trái nội dung */
        background-color: white;
        /* Màu nền giống input */
        color: #495057;
        /* Màu chữ mặc định của input */
        border: 1px solid #ced4da;
        /* Viền giống input */
        padding: 0.375rem 0.75rem;
        /* Padding giống input */
        height: 38px;
        /* Chiều cao giống input */
        border-radius: 5px;
        /* Bo góc tương tự input */
        margin-top: 5px;
    }

    /* Khi hover */
    .dropdown .btn:hover,
    .d-flex .form-control:hover {
        background-color: #f8f9fa;
        /* Giống input khi hover */
        border-color: #bdbdbd;
        /* Viền nhẹ hơn */
    }

    /* Khi nhấn vào */
    .dropdown .btn:focus,
    .dropdown .btn:active,
    .d-flex .form-control:focus,
    .d-flex .form-control:active {
        background-color: #fff;
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        /* Hiệu ứng focus */
    }

    /* Tạo dropdown menu đẹp hơn */
    .dropdown-menu {
        width: 180px;
        /* Độ rộng dropdown bằng nút */
    }

    /* Định dạng item trong dropdown */
    .dropdown-menu .dropdown-item {
        padding: 8px 15px;
    }

    /* Khi chọn một item */
    .dropdown-menu .dropdown-item:hover {
        background-color: #007bff;
        color: white;
    }

    #searchBtn {
        height: 35px;
        margin-top: 6px;
        /* Điều chỉnh cho phù hợp */
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-center">
        <h1>Quản trị viên</h1>
    </div>
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2">
            <div class="sidebar">
                <ul class="nav flex-column mt-3">
                    <li class="nav-item">
                        <a class="nav-link" href="#thuoc" id="showThuoc"><strong>Quản lý thuốc</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?tab=loai" id="showLoai"><strong>Quản lý loại
                                thuốc</strong></a>
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
                        <a class="nav-link" href="#thongBao" id="showThongBao"><strong>Thông báo thuốc sắp hết
                                hạn</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#nhapExcel" id="showNhapExcel"><strong>Nhập từ file Excel</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#xuatFile" id="showXuatFile"><strong>Xuất file </strong></a>
                    </li>
                    <?php
                    if ($vaitro == 'admin') {
                        echo '<li class="nav-item">
                                <a class="nav-link" href="#nhanVien" id="showNhanVien"><strong>Quản lý nhân viên</strong></a>
                            </li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <div class="col-md-10">
            <?php
            include __DIR__ . '/thuoc.php';
            include __DIR__ . '/loaithuoc.php';
            include __DIR__ . '/nhacungcap.php';
            include __DIR__ . '/hangsanxuat.php';
            include __DIR__ . '/khachhang.php';
            include __DIR__ . '/hoadon.php';
            include __DIR__ . '/nhanvien.php';
            ?>

            <!-- Thống kê danh thu -->

            <!-- Thông báo Thuốc sắp hết hạn -->
            <?php include __DIR__ . '/thongbao.php'; ?>

            <!-- Nhập thuốc từ Excel -->
            <?php include __DIR__ . '/nhapExcel.php'; ?>

            <!-- Xuất file Excel -->
            <?php include __DIR__ . '/xuatexcel.php'; ?>
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

<!-- Show -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuItems = [
            { linkId: 'showThuoc', sectionId: 'thuoc' },
            { linkId: 'showLoai', sectionId: 'loai' },
            { linkId: 'showHoadon', sectionId: 'hoaDon' },
            { linkId: 'showThongKe', sectionId: 'thongKe' },
            { linkId: 'showThongBao', sectionId: 'thongBao' },
            { linkId: 'showNCC', sectionId: 'ncc' },
            { linkId: 'showHSX', sectionId: 'hsx' },
            { linkId: 'showNhapExcel', sectionId: 'nhapExcel' },
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

            // Lưu ID tab vào LocalStorage
            localStorage.setItem("activeTab", sectionId);
            localStorage.setItem("activeLink", linkId);

            // Cập nhật URL chỉ còn "index.php"
            history.replaceState(null, "", "index.php");
        }

        // Ẩn tất cả sections trước
        hideAllSections();

        // Lấy tab & menu sidebar active từ LocalStorage
        const savedTab = localStorage.getItem("activeTab");
        const savedLink = localStorage.getItem("activeLink");

        // Nếu có tab được lưu => Hiển thị tab đó + active menu
        if (savedTab && document.getElementById(savedTab) && savedLink && document.getElementById(savedLink)) {
            showSection(savedTab, savedLink);
        } else {
            // Nếu không có tab nào lưu, mặc định mở tab "thuốc"
            showSection('thuoc', 'showThuoc');
        }

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

<script>
    // Tự động ẩn thông báo sau 1 giây
    setTimeout(function () {
        let alerts = document.querySelectorAll(".alert-message");

        alerts.forEach(function (alert) {
            alert.style.transition = "opacity 0.5s";
            alert.style.opacity = "0";
            setTimeout(() => alert.style.display = "none", 500);
        });
    }, 1000); // 1000ms = 1 giây
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php include __DIR__ . '/../src/partials/footer.php'; ?>