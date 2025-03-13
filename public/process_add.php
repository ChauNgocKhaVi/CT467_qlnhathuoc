<?php
require_once __DIR__ . '/../src/bootstrap.php'; // Kết nối database
require_once __DIR__ . '/../src/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';

    // Xử lý thêm thuốc
    if ($action === "addthuoc") {
        $tenThuoc = $_POST['tenThuoc'] ?? '';
        $maLoai = $_POST['maLoai'] ?? '';
        $maHangSX = $_POST['MaHangSX'] ?? '';
        $maNCC = $_POST['MaNCC'] ?? '';
        $CongDung = $_POST['CongDung'] ?? '';
        $donGia = $_POST['donGia'] ?? 0;
        $soLuongTon = $_POST['soLuongTon'] ?? 0;
        $hanSuDung = $_POST['hanSuDung'] ?? '';

        if (!empty($tenThuoc) && !empty($maLoai) && !empty($maHangSX) && !empty($maNCC) && !empty($donGia) && !empty($hanSuDung)) {
            $query = "INSERT INTO Thuoc (MaLoai, MaHangSX, MaNCC, TenThuoc, CongDung, DonGia, SoLuongTon, HanSuDung) 
                    VALUES (:maLoai, :maHangSX, :maNCC, :tenThuoc, :CongDung, :donGia, :soLuongTon, :hanSuDung)";
            $stmt = $pdo->prepare($query);

            $stmt->bindParam(':maLoai', $maLoai, PDO::PARAM_INT);
            $stmt->bindParam(':maHangSX', $maHangSX, PDO::PARAM_INT);
            $stmt->bindParam(':maNCC', $maNCC, PDO::PARAM_INT);
            $stmt->bindParam(':tenThuoc', $tenThuoc, PDO::PARAM_STR);
            $stmt->bindParam(':CongDung', $CongDung, PDO::PARAM_STR);
            $stmt->bindParam(':donGia', $donGia, PDO::PARAM_STR);
            $stmt->bindParam(':soLuongTon', $soLuongTon, PDO::PARAM_INT);
            $stmt->bindParam(':hanSuDung', $hanSuDung, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "<script>alert('Thêm thuốc thành công!'); window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Thêm thuốc thất bại!'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Vui lòng nhập đầy đủ thông tin!'); window.history.back();</script>";
        }
    }

    // Xử lý thêm nhân viên
    elseif ($action === "addnhanvien") {
        $hoTen = $_POST['HoTen'] ?? '';
        $tenDangNhap = $_POST['TenDangNhap'] ?? '';
        $email = $_POST['Email'] ?? '';
        $matKhau = $_POST['MatKhau'] ?? '';
        $matKhauConfirm = $_POST['MatKhauConfirm'] ?? '';
        $soDienThoai = $_POST['SoDienThoai'] ?? '';
        $vaiTro = $_POST['VaiTro'] ?? 'nhanvien';
        $trangThai = $_POST['TrangThai'] ?? 'active';

        if (empty($hoTen) || empty($tenDangNhap) || empty($email) || empty($matKhau) || empty($soDienThoai)) {
            echo "<script>alert('Vui lòng nhập đầy đủ thông tin!'); window.history.back();</script>";
            exit();
        }

        if ($matKhau !== $matKhauConfirm) {
            echo "<script>alert('Mật khẩu xác nhận không khớp!'); window.history.back();</script>";
            exit();
        }

        // Gọi hàm thêm nhân viên
        $result = themNhanVien($pdo, $hoTen, $tenDangNhap, $email, $matKhau, $soDienThoai, $vaiTro, $trangThai);

        if ($result === true) {
            echo "<script>alert('Thêm nhân viên thành công!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('$result'); window.history.back();</script>";
        }
    }

    // Nếu action không hợp lệ
    else {
        echo "<script>alert('Hành động không hợp lệ!'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Phương thức không hợp lệ!'); window.history.back();</script>";
}
?>