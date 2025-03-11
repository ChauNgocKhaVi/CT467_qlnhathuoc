<?php
namespace CT467\Labs;
use PDO;

class Thuoc
{
    private $db;
    private $maThuoc;
    private $maLoai;
    private $maHangSX;
    private $maNCC;
    private $tenThuoc;
    private $congDung;
    private $donGia;
    private $soLuongTon;
    private $hanSuDung;

    /**
     * Constructor - Khởi tạo đối tượng Thuoc
     * 
     * @param PDO|null $pdo - Kết nối cơ sở dữ liệu
     */
    public function __construct(?PDO $pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Điền dữ liệu từ form vào đối tượng
     * 
     * @param array $data - Dữ liệu từ form
     */
    public function fill(array $data): void
    {
        $this->maLoai = $data['maLoai'] ?? null;
        $this->maHangSX = $data['maHangSX'] ?? null;
        $this->maNCC = $data['maNCC'] ?? null;
        $this->tenThuoc = trim($data['tenThuoc'] ?? '');
        $this->congDung = trim($data['congDung'] ?? '');
        $this->donGia = (float) ($data['donGia'] ?? 0);
        $this->soLuongTon = (int) ($data['soLuongTon'] ?? 0);
        $this->hanSuDung = $data['hanSuDung'] ?? null;
    }

    /**
     * Thêm thuốc mới vào database bằng Stored Procedure
     */
    public function themThuoc(): bool
    {
        $sql = "CALL ThemThuoc(:maLoai, :maHangSX, :maNCC, :tenThuoc, :congDung, :donGia, :soLuongTon, :hanSuDung)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':maLoai' => $this->maLoai,
            ':maHangSX' => $this->maHangSX,
            ':maNCC' => $this->maNCC,
            ':tenThuoc' => $this->tenThuoc,
            ':congDung' => $this->congDung,
            ':donGia' => $this->donGia,
            ':soLuongTon' => $this->soLuongTon,
            ':hanSuDung' => $this->hanSuDung
        ]);
    }

    /**
     * Cập nhật thông tin thuốc bằng Stored Procedure
     */
    public function suaThuoc($id): bool
    {
        $sql = "CALL SuaThuoc(:id, :maLoai, :maHangSX, :maNCC, :tenThuoc, :congDung, :donGia, :soLuongTon, :hanSuDung)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':maLoai' => $this->maLoai,
            ':maHangSX' => $this->maHangSX,
            ':maNCC' => $this->maNCC,
            ':tenThuoc' => $this->tenThuoc,
            ':congDung' => $this->congDung,
            ':donGia' => $this->donGia,
            ':soLuongTon' => $this->soLuongTon,
            ':hanSuDung' => $this->hanSuDung
        ]);
    }

    /**
     * Xóa thuốc khỏi database bằng Stored Procedure
     */
    public function xoaThuoc($id): bool
    {
        $sql = "CALL XoaThuoc(:id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Lấy danh sách thuốc theo loại bằng Stored Procedure
     */
    public function layThuocTheoLoai($loai): array
    {
        $sql = "CALL LayThuocTheoLoai(:loai)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':loai' => $loai]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Kiểm tra số lượng thuốc còn trong kho bằng FUNCTION
     */
    public function soLuongConLai($id): int
    {
        $sql = "SELECT so_luong_thuoc(:id) AS SoLuongTon";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int) $result['SoLuongTon'] : 0;
    }

    /**
     * Kiểm tra thuốc sắp hết hạn dựa trên Trigger
     */
    public function thuocSapHetHan(): array
    {
        $sql = "SELECT * FROM Thuoc WHERE ThuocSapHetHan = 1";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
