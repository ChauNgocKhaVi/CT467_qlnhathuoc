<!-- Nhà cung cấp -->
<div class="container-fluid-fluid" id="ncc">
    <h2 class="section-title bg-light p-2 rounded potta-one-regular">Danh Sách Nhà Cung Cấp</h2>
    <!-- Hiển thị thông báo -->
    <?php if (!empty($successNCC)): ?>
    <div class="alert alert-success alert-message"><?php echo htmlspecialchars($successNCC); ?></div>
    <?php endif; ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Tìm kiếm -->
        <div class="d-flex">
            <button id="searchBtn" class="btn btn-primary me-2">Tìm kiếm</button>
            <input type="text" id="maNCC" class="form-control me-2" placeholder="Mã Nhà cung cấp" />
            <input type="text" id="tenNCC" class="form-control me-2" placeholder="Mã Nhà cung cấp" />
            <input type="text" id="SoDienThoai" class="form-control me-2" placeholder="Số điện thoại" />
        </div>

        <!-- Thêm mới -->
        <div class="d-flex justify-content-end">
            <a href="add.php?id=formNCC" id="NCC" class="btn btn-primary">Thêm mới</a>
        </div>
    </div>

    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th class="text-center">Mã Nhà Cung Cấp</th>
                <th>Tên Nhà Cung Cấp</th>
                <th>Số Điện Thoại</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($nccList as $ncc): ?>
            <tr>
                <td class="text-center"><?php echo htmlspecialchars($ncc['MaNCC']); ?></td>
                <td><?php echo htmlspecialchars($ncc['TenNCC']); ?></td>
                <td><?php echo htmlspecialchars($ncc['SoDienThoai']); ?></td>
                <td>
                    <a href="edit_ncc.php?MaNCC=<?php echo $ncc['MaNCC']; ?>" class="btn btn-primary">Sửa</a>
                    <a href="delete_ncc.php?MaNCC=<?php echo $ncc['MaNCC']; ?>" class="btn btn-danger"
                        onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a>
                </td>

            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    let inputs = document.querySelectorAll("#maNCC, #tenNCC, #SoDienThoai");

    inputs.forEach(input => {
        input.addEventListener("input", function() {
            let maNCC = document.getElementById("maNCC").value.trim().toLowerCase();
            let tenNCC = document.getElementById("tenNCC").value.trim().toLowerCase();
            let soDienThoai = document.getElementById("SoDienThoai").value.trim().toLowerCase();

            let rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                let maNCCText = row.cells[0]?.textContent.trim().toLowerCase() || "";
                let tenNCCText = row.cells[1]?.textContent.trim().toLowerCase() || "";
                let soDienThoaiText = row.cells[2]?.textContent.trim().toLowerCase() ||
                    "";

                let matchMaNCC = maNCC === "" || maNCCText.includes(maNCC);
                let matchTenNCC = tenNCC === "" || tenNCCText.includes(tenNCC);
                let matchSoDienThoai = soDienThoai === "" || soDienThoaiText.includes(
                    soDienThoai);

                row.style.display = (matchMaNCC && matchTenNCC && matchSoDienThoai) ?
                    "" : "none";
            });
        });
    });
});
</script>