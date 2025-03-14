<?php // Lấy tổng số lượng thuốc của mã loại 1
$tongSoLuong = tongSoLuongThuocTheoLoai($pdo, 1);
?>

<!-- Loại thuốc -->
<div class="container-fluid-fluid" id="loai">
    <h2 class="section-title bg-light p-2 rounded potta-one-regular">Danh Sách Loại</h2>
    <!-- Hiển thị thông báo -->
    <?php if (!empty($successLoai)): ?>
        <div class="alert alert-success alert-message"><?php echo htmlspecialchars($successLoai); ?></div>
    <?php endif; ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Tìm kiếm -->
        <div class="d-flex">
            <button id="searchBtn" class="btn btn-primary me-2">Tìm kiếm</button>
            <input type="text" id="maloai" class="form-control me-2" placeholder="Mã loại" />
            <input type="text" id="tenloai" class="form-control me-2" placeholder="Tên loại" />
            <input type="text" id="DonViTinh" class="form-control me-2" placeholder="Đơn vị tính" />
        </div>

        <!-- Thêm mới -->
        <div class="d-flex justify-content-end">
            <a href="add.php?id=formLoai" id="Loai" class="btn btn-primary">Thêm mới</a>
        </div>
    </div>

    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th class="text-center">Mã Loại</th>
                <th class="text-center">Tên Loại</th>
                <th class="text-center">Đơn vị tính</th>
                <th class="text-center">Số lượng thuốc trong kho</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($loaiList as $loai):
                $TongSoLuong = tongSoLuongThuocTheoLoai($pdo, $loai['MaLoai']); 
                ?>
                <tr>
                    <td class="text-center"><?php echo htmlspecialchars($loai['MaLoai']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($loai['TenLoai']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($loai['DonViTinh']); ?></td>
                    <td class="text-center"><?php echo number_format($TongSoLuong); ?></td> <!-- Định dạng số lượng -->
                    <td>
                        <a href="edit_loai.php?MaLoai=<?php echo $loai['MaLoai']; ?>" class="btn btn-primary">Sửa</a>
                        <a href="delete_loai.php?MaLoai=<?php echo $loai['MaLoai']; ?>" class="btn btn-danger"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Lấy tất cả các ô input
        let inputs = document.querySelectorAll("#maloai, #tenloai, #DonViTinh");

        // Gắn sự kiện 'input' cho từng ô
        inputs.forEach(input => {
            input.addEventListener("input", function () {
                let maloai = document.getElementById("maloai").value.trim().toLowerCase();
                let tenloai = document.getElementById("tenloai").value.trim().toLowerCase();
                let donvitinh = document.getElementById("DonViTinh").value.trim().toLowerCase();

                let rows = document.querySelectorAll("tbody tr");

                rows.forEach(row => {
                    let maLoaiText = row.cells[0]?.textContent.trim().toLowerCase() || "";
                    let tenLoaiText = row.cells[1]?.textContent.trim().toLowerCase() || "";
                    let donViTinhText = row.cells[2]?.textContent.trim().toLowerCase() ||
                        "";

                    let matchMaLoai = maloai === "" || maLoaiText.includes(maloai);
                    let matchTenLoai = tenloai === "" || tenLoaiText.includes(tenloai);
                    let matchDonViTinh = donvitinh === "" || donViTinhText.includes(
                        donvitinh);

                    row.style.display = (matchMaLoai && matchTenLoai && matchDonViTinh) ?
                        "" : "none";
                });
            });
        });
    });
</script>