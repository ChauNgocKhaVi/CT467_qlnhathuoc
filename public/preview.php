<?php
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/functions.php';

header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['tables'])) {
    $tables = json_decode($_POST['tables'], true); // Giải mã JSON
    $output = "";

    foreach ($tables as $table) {
        switch ($table) {
            case 'thuoc':
                $data = getDanhSachThuoc($pdo);
                $headers = ["Mã Thuốc", "Tên Thuốc", "Loại", "Hãng Sản Xuất", "Nhà Cung Cấp", "Công Dụng", "Giá", "Số Lượng", "Hạn Sử Dụng"];
                break;
            case 'loai':
                $data = getDanhSachLoaiThuoc($pdo);
                $headers = ["Mã Loại", "Tên Loại"];
                break;
            case 'nhacungcap':
                $data = getDanhSachNhaCungCap($pdo);
                $headers = ["Mã NCC", "Tên NCC", "Số Điện Thoại"];
                break;
            case 'hangsanxuat':
                $data = getDanhSachHangSanXuat($pdo);
                $headers = ["Mã Hãng", "Tên Hãng"];
                break;
            case 'khachhang':
                $data = getDanhSachKhachHang($pdo);
                $headers = ["Mã KH", "Họ Tên", "Số Điện Thoại"];
                break;
            case 'hoadon':
                $data = getDanhSachHoaDon($pdo);
                $headers = ["Mã HĐ", "Ngày Lập", "Khách Hàng", "Tổng Tiền"];
                break;
            default:
                continue 2; // Nếu không khớp với bảng nào thì bỏ qua
        }

        if (empty($data)) {
            $output .= "<p>Không có dữ liệu cho bảng <b>$table</b></p>";
            continue;
        }

        // Tạo bảng HTML
        $output .= "<h3 class='mt-2 text-center'>" . ucfirst($table) . "</h3>";
        $output .= "<table class='table table-bordered mb-5'>";
        $output .= "<thead><tr>";
        foreach ($headers as $header) {
            $output .= "<th>" . htmlspecialchars($header) . "</th>";
        }
        $output .= "</tr></thead><tbody>";

        foreach ($data as $row) {
            $output .= "<tr>";
            foreach ($row as $value) {
                $output .= "<td>" . htmlspecialchars($value) . "</td>";
            }
            $output .= "</tr>";
        }

        $output .= "</tbody></table>";
    }

    echo $output;
    exit;
} else {
    echo "<p class='text-danger'>Không có dữ liệu được chọn!</p>";
    exit;
}
?>