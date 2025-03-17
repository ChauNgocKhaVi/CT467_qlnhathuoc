<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/functions.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['import'])) {
    if (!isset($_FILES['excelFile']) || $_FILES['excelFile']['error'] != 0) {
        die("Lỗi khi tải file lên.");
    }

    $importTables = $_POST['import']; // Lấy danh sách bảng cần nhập
    $filePath = $_FILES['excelFile']['tmp_name'];

    try {
        // Đọc file Excel
        $spreadsheet = IOFactory::load($filePath);
    } catch (Exception $e) {
        // Xử lý lỗi khi không thể đọc file
        die("Lỗi đọc file Excel: " . $e->getMessage());
    }

    $results = [];

    try {
        foreach ($importTables as $table) {
            switch ($table) {
                case 'loai':
                    $sheet = $spreadsheet->getSheetByName("Loại Thuốc");
                    if ($sheet) {
                        $results[$table] = importLoaiThuoc($pdo, $sheet);
                    }
                    break;

                case 'nhacungcap':
                    $sheet = $spreadsheet->getSheetByName("Nhà Cung Cấp");
                    if ($sheet) {
                        $results[$table] = importNhaCungCap($pdo, $sheet);
                    }
                    break;

                case 'hangsanxuat':
                    $sheet = $spreadsheet->getSheetByName("Hãng SX");
                    if ($sheet) {
                        $results[$table] = importHangSanXuat($pdo, $sheet);
                    }
                    break;

                // Sau đó là nhập thuốc
                case 'thuoc':
                    $sheet = $spreadsheet->getSheetByName("Thuốc");
                    if ($sheet) {
                        $results[$table] = importThuoc($pdo, $sheet);
                    }
                    break;

                // Sau khi nhập thuốc, nhập khách hàng
                case 'khachhang':
                    $sheet = $spreadsheet->getSheetByName("Khách Hàng");
                    if ($sheet) {
                        $results[$table] = importKhachHang($pdo, $sheet);
                    }
                    break;
            }
        }

        // Lưu thông báo thành công vào session
        $_SESSION['success_message_import'] = "Đã nhập thành công!";

        // Chuyển hướng về trang index.php
        header("Location: index.php");
        exit;

    } catch (Exception $e) {
        // Xử lý lỗi nếu có lỗi khi thực hiện các thao tác nhập liệu vào DB
        $_SESSION['error_message_import'] = "Có lỗi xảy ra khi nhập dữ liệu! Vui lòng thử lại sau.";
        header("Location: index.php");
        exit;
    }
}
?>