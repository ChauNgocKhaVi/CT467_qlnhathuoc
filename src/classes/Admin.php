<?php
namespace CT467\Labs;
use PDO;

class Admin
{
    private $db;
    private $id;
    private $tenDangNhap;
    private $matKhau;
    private $hoTen;
    private $email;
    private $sdt;
    private $diaChi;
    private $phanQuyen;
    private $trangThai;

    public function __construct(?PDO $pdo)
    {
        $this->db = $pdo;
    }

    // Điền dữ liệu từ form
    public function fill(array $data): void
    {
        $this->tenDangNhap = trim($data['tenDangNhap'] ?? '');
        $this->matKhau = !empty($data['matKhau']) ? password_hash(trim($data['matKhau']), PASSWORD_DEFAULT) : $this->matKhau;
        $this->hoTen = trim($data['hoTen'] ?? '');
        $this->email = trim($data['email'] ?? '');
        $this->sdt = trim($data['sdt'] ?? '');
        $this->diaChi = trim($data['diaChi'] ?? '');
        $this->phanQuyen = $data['phanQuyen'] ?? 'nhanvien';
        $this->trangThai = $data['trangThai'] ?? 'active';
    }
}
?>