<?php
namespace CT467\Labs;
use PDO;

class KhachHang
{
    private $db;
    private $maKH;
    private $tenKH;
    private $soDienThoai;
    private $diaChi;

    /**
     * Constructor - Khởi tạo đối tượng KhachHang
     * 
     * @param PDO|null $pdo - Kết nối cơ sở dữ liệu
     */
    public function __construct(?PDO $pdo)
    {
        $this->db = $pdo;
    }
}
?>