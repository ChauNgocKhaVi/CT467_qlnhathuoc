<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Nạp thư viện PhpSpreadsheet
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/functions.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['export'])) {
    $spreadsheet = new Spreadsheet();
    $sheetIndex = 0;

    foreach ($_POST['export'] as $table) {
        switch ($table) {
            case 'thuoc':
                $data = getDanhSachThuoc($pdo);
                $headers = ["Mã Thuốc", "Tên Thuốc", "Loại", "Hãng Sản Xuất", "Nhà Cung Cấp", "Công Dụng", "Giá", "Số Lượng", "Hạn Sử Dụng"];
                $sheetName = "Danh Sách Thuốc";
                break;

            case 'loai':
                $data = getDanhSachLoaiThuoc($pdo);
                $headers = ["Mã Loại", "Tên Loại"];
                $sheetName = "Loại Thuốc";
                break;

            case 'nhacungcap':
                $data = getDanhSachNhaCungCap($pdo);
                $headers = ["Mã NCC", "Tên NCC", "Số Điện Thoại"];
                $sheetName = "Nhà Cung Cấp";
                break;

            case 'hangsanxuat':
                $data = getDanhSachHangSanXuat($pdo);
                $headers = ["Mã Hãng", "Tên Hãng", "Quốc Gia"];
                $sheetName = "Hãng Sản Xuất";
                break;

            case 'khachhang':
                $data = getDanhSachKhachHang($pdo);
                $headers = ["Mã KH", "Họ Tên", "Số Điện Thoại"];
                $sheetName = "Khách Hàng";
                break;

            case 'hoadon':
                $data = getDanhSachHoaDon($pdo);
                $headers = ["Mã HĐ", "Ngày Lập", "Khách Hàng", "Tổng Tiền"];
                $sheetName = "Hóa Đơn";

                if (!empty($data)) {
                    foreach ($data as &$row) {
                        $maKH = $row['MaKH'] ?? 0; // Nếu MaKH null, đặt về 0
                        $row['Khách Hàng'] = getTenKhachHang($pdo, (int) $maKH); // Chuyển đổi thành số nguyên
                        unset($row['MaKH']); // Xóa cột MaKH để tránh nhầm lẫn
                    }
                }

                $chiTietHD = getDanhSachChiTietHoaDon($pdo);
                break;

            default:
                continue 2; // Bỏ qua nếu không phải bảng hợp lệ
        }

        // Tạo Sheet
        $spreadsheet->createSheet($sheetIndex);
        $sheet = $spreadsheet->setActiveSheetIndex($sheetIndex);
        $sheet->setTitle($sheetName);

        // Thêm tiêu đề cột
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '1', $header);
            $column++;
        }

        // Thêm dữ liệu vào bảng
        $row = 2;
        foreach ($data as $rowData) {
            $column = 'A';
            foreach ($rowData as $value) {
                $sheet->setCellValue($column . $row, $value);
                $column++;
            }
            $row++;
        }

        // Nếu là hóa đơn, thêm chi tiết hóa đơn
        if ($table === 'hoadon' && !empty($chiTietHD)) {
            $sheetIndex++;
            $spreadsheet->createSheet($sheetIndex);
            $sheetChiTiet = $spreadsheet->setActiveSheetIndex($sheetIndex);
            $sheetChiTiet->setTitle("Chi Tiết Hóa Đơn");

            $headersChiTiet = ["Mã HĐ", "Tên Thuốc", "Số Lượng", "Đơn Giá", "Thành Tiền"];

            $column = 'A';
            foreach ($headersChiTiet as $header) {
                $sheetChiTiet->setCellValue($column . '1', $header);
                $column++;
            }

            if (is_array($chiTietHD) && !empty($chiTietHD)) {
                $row = 2;
                foreach ($chiTietHD as $rowData) {
                    $column = 'A';
                    $rowData['MaThuoc'] = getTenThuoc($pdo, (int) ($rowData['MaThuoc'] ?? 0)); // Ép kiểu tránh lỗi
                    foreach ($rowData as $value) {
                        $sheetChiTiet->setCellValue($column . $row, $value);
                        $column++;
                    }
                    $row++;
                }
            }
        }

        $sheetIndex++;
    }

    // Xóa tất cả output trước khi xuất file
    ob_clean();
    ob_start();

    // Xuất file Excel
    $fileName = "danh_sach_" . time() . ".xlsx";
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=\"$fileName\"");

    $writer = new Xlsx($spreadsheet);
    $writer->save("php://output");

    ob_end_flush(); // Kết thúc output buffering
    exit;
}
?>