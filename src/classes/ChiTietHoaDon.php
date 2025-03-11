<?php 
namespace CT467\Labs;
use PDO;

class ChiTietHoaDon {
    private $db;
    private $maHD;
    private $maThuoc;
    private $soLuongBan;
    private $giaBan;

    public function __construct(?PDO $pdo)
    {
        $this->db = $pdo;
    }

}
?>
