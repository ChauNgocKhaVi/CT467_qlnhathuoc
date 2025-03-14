<!-- Khách hàng -->
<div class="container-fluid-fluid" id="khachHang">
    <h2 class="section-title bg-light p-2 rounded potta-one-regular">Danh Sách Khách Hàng</h2>
    <!-- Hiển thị thông báo -->
    <?php if (!empty($successKH)): ?>
    <div class="alert alert-success alert-message"><?php echo htmlspecialchars($successKH); ?></div>
    <?php endif; ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Tìm kiếm -->
        <div class="d-flex">
            <button id="searchBtn" class="btn btn-primary me-2">Tìm kiếm</button>
            <input type="text" id="MaKH" class="form-control me-2" placeholder="Mã khách hàng" />
            <input type="text" id="TenKH" class="form-control me-2" placeholder="Tên khách hàng" />
            <input type="text" id="SoDienThoai" class="form-control me-2" placeholder="Số điện thoại" />
        </div>

        <!-- Thêm mới -->
        <div class="d-flex justify-content-end">
            <a href="add.php?id=formKH" id="khachHang" class="btn btn-primary">Thêm mới</a>
        </div>
    </div>


    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th class="text-center">Mã Khách Hàng</th>
                <th>Tên Khách Hàng</th>
                <th>Số Điện Thoại</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($KHList as $KH): ?>
            <tr>
                <td class="text-center"><?php echo htmlspecialchars($KH['MaKH']); ?></td>
                <td><?php echo htmlspecialchars($KH['TenKH']); ?></td>
                <td><?php echo htmlspecialchars($KH['SoDienThoai']); ?></td>
                <td>
                    <a href="edit_khachHang.php?MaKH=<?php echo $KH['MaKH']; ?>" class="btn btn-primary">Sửa</a>
                    <a href="delete_khachHang.php?MaKH=<?php echo $KH['MaKH']; ?>" class="btn btn-danger"
                        onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này?');">Xóa</a>
                </td>

            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    let inputs = document.querySelectorAll("#MaKH, #TenKH, #SoDienThoai");

    inputs.forEach(input => {
        input.addEventListener("input", function() {
            let maKH = document.getElementById("MaKH").value.trim().toLowerCase();
            let tenKH = document.getElementById("TenKH").value.trim().toLowerCase();
            let soDienThoai = document.getElementById("SoDienThoai").value.trim().toLowerCase();

            let rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                let maKHText = row.cells[0]?.textContent.trim().toLowerCase() || "";
                let tenKHText = row.cells[1]?.textContent.trim().toLowerCase() || "";
                let soDienThoaiText = row.cells[2]?.textContent.trim().toLowerCase() ||
                    "";

                let matchMaKH = maKH === "" || maKHText.includes(maKH);
                let matchTenKH = tenKH === "" || tenKHText.includes(tenKH);
                let matchSoDienThoai = soDienThoai === "" || soDienThoaiText.includes(
                    soDienThoai);

                row.style.display = (matchMaKH && matchTenKH && matchSoDienThoai) ? "" :
                    "none";
            });
        });
    });
});
</script>