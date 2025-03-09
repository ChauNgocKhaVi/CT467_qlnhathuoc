<?php
namespace CT467\Labs;
use PDO; 
class User {
    private $pdo;

    public function __construct($pdo) {
        if (!$pdo) {
            echo ("Lỗi: Không có kết nối database!");
        }
        $this->pdo = $pdo;
    }

    public function register($hoten, $tendangnhap, $email, $sodienthoai, $password, $vaitro = 'khachhang') {
        // Kiểm tra tên đăng nhập (chữ cái đầu, chứa chữ cái, số, dấu . và _ từ 5-20 ký tự)
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9._]{4,19}$/', $tendangnhap)) {
            die("Tên đăng nhập không hợp lệ! (Phải bắt đầu bằng chữ cái, từ 5-20 ký tự)");
        }

        // Kiểm tra email hợp lệ
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Email không hợp lệ!");
        }

        // Kiểm tra số điện thoại (chỉ chứa số, 10-15 ký tự)
        if (!preg_match('/^[0-9]{10,15}$/', $sodienthoai)) {
            die("Số điện thoại không hợp lệ! (Chỉ chứa số, từ 10-15 ký tự)");
        }

        // Kiểm tra mật khẩu (tối thiểu 8 ký tự, ít nhất 1 chữ hoa, 1 chữ thường, 1 số, 1 ký tự đặc biệt)
        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
            die("Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt!");
        }

        // Mã hóa mật khẩu trước khi lưu
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Kiểm tra email hoặc tên đăng nhập đã tồn tại chưa
        $sql = "SELECT COUNT(*) FROM NguoiDung WHERE TenDangNhap = :tendangnhap OR Email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':tendangnhap' => $tendangnhap, ':email' => $email]);
        if ($stmt->fetchColumn() > 0) {
            die("Tên đăng nhập hoặc Email đã tồn tại!");
        }

        // Thêm người dùng vào database
        $sql = "INSERT INTO NguoiDung (HoTen, TenDangNhap, Email, SoDienThoai, MatKhau, VaiTro) 
                VALUES (:hoten, :tendangnhap, :email, :sodienthoai, :matkhau, :vaitro)";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':hoten' => $hoten,
            ':tendangnhap' => $tendangnhap,
            ':email' => $email,
            ':sodienthoai' => $sodienthoai,
            ':matkhau' => $hashedPassword,
            ':vaitro' => $vaitro
        ]);
    }

    public function login($usernameOrEmail, $password) {
        $sql = "SELECT * FROM NguoiDung WHERE TenDangNhap = :username OR Email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':username' => $usernameOrEmail, ':email' => $usernameOrEmail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['MatKhau'])) {
            return $user; // Trả về thông tin user nếu đăng nhập đúng
        }
        return false;
    }
}
?>