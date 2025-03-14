<?php
require_once __DIR__ . '/../src/bootstrap.php';

if (!isset($_GET['MaHD'])) {
    die("Thiếu mã hóa đơn!");
}
$maHD = $_GET['MaHD'];

// Lấy thông tin hóa đơn
$stmt = $pdo->prepare("SELECT * FROM HoaDon WHERE MaHD = :maHD");
$stmt->execute(['maHD' => $maHD]);
$hoaDon = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$hoaDon) {
    die("Hóa đơn không tồn tại!");
}

// Lấy danh sách khách hàng
$khachHangs = $pdo->query("SELECT MaKH, TenKH FROM KhachHang")->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách thuốc
$thuocList = $pdo->query("SELECT MaThuoc, TenThuoc FROM Thuoc")->fetchAll(PDO::FETCH_ASSOC);

// Lấy chi tiết hóa đơn
$stmt = $pdo->prepare("SELECT * FROM ChiTietHoaDon WHERE MaHD = :maHD");
$stmt->execute(['maHD' => $maHD]);
$chiTietHDs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdo->beginTransaction();
    try {
        // Cập nhật hóa đơn
        $stmt = $pdo->prepare("UPDATE HoaDon SET MaKH = :maKH, NgayLap = :ngayLap WHERE MaHD = :maHD");
        $stmt->execute([
            'maKH' => $_POST['MaKH'],
            'ngayLap' => $_POST['NgayLap'],
            'maHD' => $maHD
        ]);

        // Xóa chi tiết cũ
        $pdo->prepare("DELETE FROM ChiTietHoaDon WHERE MaHD = :maHD")->execute(['maHD' => $maHD]);

         // Thêm chi tiết mới & tính tổng tiền
        $tongTien = 0;
        foreach ($_POST['MaThuoc'] as $index => $maThuoc) {
            if (!empty($maThuoc)) {
                $soLuong = $_POST['SoLuongBan'][$index];
                $giaBan = $_POST['GiaBan'][$index];
                $tongTien += $soLuong * $giaBan;

                $stmt = $pdo->prepare("INSERT INTO ChiTietHoaDon (MaHD, MaThuoc, SoLuongBan, GiaBan) 
                                       VALUES (:maHD, :maThuoc, :soLuongBan, :giaBan)");
                $stmt->execute([
                    'maHD' => $maHD,
                    'maThuoc' => $maThuoc,
                    'soLuongBan' => $soLuong,
                    'giaBan' => $giaBan
                ]);
            }
        }

         // Cập nhật tổng tiền vào bảng HoaDon
        $stmt = $pdo->prepare("UPDATE HoaDon SET TongTien = :tongTien WHERE MaHD = :maHD");
        $stmt->execute([
            'tongTien' => $tongTien,
            'maHD' => $maHD
        ]);

        $pdo->commit();
        header("Location: index.php?success=Cập nhật hóa đơn thành công");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Lỗi: " . $e->getMessage());
    }
}

include __DIR__ . '/../src/partials/header.php';
include __DIR__ . '/../src/partials/head.php';
?>

<div class="container d-flex justify-content-center align-items-center">
    <div class="col-md-6">
        <h2 class="text-center mb-4">Chỉnh sửa hóa đơn</h2>

        <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
        <?php endif; ?>

        <form method="POST" class="form-HD">
            <div class="mb-3">
                <label for="MaKH" class="form-label">Khách Hàng</label>
                <select class="form-control" id="MaKH" name="MaKH" required>
                    <option value="">-- Chọn Khách Hàng --</option>
                    <?php foreach ($khachHangs as $kh): ?>
                    <option value="<?= $kh['MaKH'] ?>" <?= ($hoaDon['MaKH'] == $kh['MaKH']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($kh['TenKH']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>

            </div>

            <!-- Trường nhập thông tin khách hàng mới -->
            <div id="newCustomerFields" style="display: none;">
                <div class="mb-3">
                    <label for="TenKH" class="form-label">Tên Khách Hàng</label>
                    <input type="text" class="form-control" id="TenKH" name="TenKH">
                </div>
                <div class="mb-3">
                    <label for="SoDienThoai" class="form-label">Số Điện Thoại</label>
                    <input type="text" class="form-control" id="SoDienThoai" name="SoDienThoai">
                </div>
                <div class="mb-3">
                    <label for="DiaChi" class="form-label">Địa Chỉ</label>
                    <input type="text" class="form-control" id="DiaChi" name="DiaChi">
                </div>
            </div>

            <div class="mb-3">
                <label for="NgayLap" class="form-label">Ngày Lập</label>
                <input type="date" class="form-control" id="NgayLap" name="NgayLap"
                    value="<?= htmlspecialchars($hoaDon['NgayLap']) ?>" required>

            </div>


            <!-- Chọn thuốc và nhập số lượng, giá bán -->
            <div class="mb-3">
                <label class="form-label">Chọn Thuốc</label>
                <div id="thuocContainer">
                    <?php foreach ($chiTietHDs as $chiTiet): ?>
                    <div class="thuoc-row d-flex gap-2 mb-2">
                        <select class="form-control w-50" name="MaThuoc[]">
                            <option value="">-- Chọn Thuốc --</option>
                            <?php foreach ($thuocList as $thuoc): ?>
                            <option value="<?= $thuoc['MaThuoc'] ?>"
                                <?= ($chiTiet['MaThuoc'] == $thuoc['MaThuoc']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($thuoc['TenThuoc']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" class="form-control w-25" name="SoLuongBan[]" placeholder="Số lượng"
                            min="1" required value="<?= $chiTiet['SoLuongBan'] ?>">
                        <input type="number" class="form-control w-25" name="GiaBan[]" placeholder="Giá bán" min="0"
                            step="0.01" required value="<?= $chiTiet['GiaBan'] ?>">
                        <button type="button" class="mt-2 btn btn-danger btn-remove-x"
                            onclick="removeThuocRow(this)">X</button>
                    </div>
                    <?php endforeach; ?>
                </div>

                <button type="button" class="btn btn-success mt-2" onclick="addThuocRow()">+ Thêm Thuốc</button>
            </div>
            <div class="mb-3">
                <label for="TongTien" class="form-label">Tổng Tiền</label>
                <input type="text" class="form-control" id="TongTien" name="TongTien" readonly
                    value="<?= htmlspecialchars($hoaDon['TongTien']) ?>">

            </div>


            <div class="d-flex justify-content-center gap-3">
                <a href="index.php" class="btn btn-danger w-50 text-center">Hủy</a>
                <button type="submit" class="btn btn-primary w-50">Lưu</button>
            </div>
        </form>

    </div>
</div>

<script>
function toggleNewCustomerFields(select) {
    var newCustomerFields = document.getElementById("newCustomerFields");
    if (select.value === "new") {
        newCustomerFields.style.display = "block";
        document.getElementById("TenKH").setAttribute("required", "required");
        document.getElementById("SoDienThoai").setAttribute("required", "required");
        document.getElementById("DiaChi").setAttribute("required", "required");
    } else {
        newCustomerFields.style.display = "none";
        document.getElementById("TenKH").removeAttribute("required");
        document.getElementById("SoDienThoai").removeAttribute("required");
        document.getElementById("DiaChi").removeAttribute("required");
    }
}

function addThuocRow() {
    var container = document.getElementById("thuocContainer");
    var newRow = container.firstElementChild.cloneNode(true);
    newRow.querySelectorAll("input, select").forEach(input => input.value = "");
    container.appendChild(newRow);
}

function removeThuocRow(button) {
    button.parentElement.remove();
}
document.addEventListener("input", function() {
    let total = 0;
    document.querySelectorAll("#thuocContainer .thuoc-row").forEach(row => {
        let quantity = row.querySelector("[name='SoLuongBan[]']").value || 0;
        let price = row.querySelector("[name='GiaBan[]']").value || 0;
        total += quantity * price;
    });
    document.getElementById("TongTien").value = total.toFixed(2);
});
</script>
<style>
.form-HD {
    width: 80%;
    max-width: 600px;
    margin: 0 auto;
}

.btn-remove-x {
    padding: 2px 6px;
    font-size: 14px;
    line-height: 1;
    width: 30px;
    /* Giảm chiều rộng */
    height: 30px;
    /* Giảm chiều cao */
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>