<?php
namespace CT467\Labs;
use PDO;

class HoaDon
{
    private $db;
    private $maHD;
    private $maKH;
    private $ngayLap;
    private $tongTien;

    /**
     * Constructor - Khởi tạo đối tượng HoaDon
     * 
     * @param PDO|null $pdo - Kết nối cơ sở dữ liệu
     */
    public function __construct(?PDO $pdo)
    {
        $this->db = $pdo;
    }

}
?>