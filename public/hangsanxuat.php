<!-- Hãng sản xuất -->
<div class="container-fluid-fluid" id="hsx">
    <h2 class="section-title bg-light p-2 rounded potta-one-regular">Danh Sách Hãng Sản Xuất</h2>
    <!-- Hiển thị thông báo -->
    <?php if (!empty($successHangSX)): ?>
    <div class="alert alert-success alert-message"><?php echo htmlspecialchars($successHangSX); ?></div>
    <?php endif; ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Tìm kiếm -->
        <div class="d-flex">
            <button id="searchBtn" class="btn btn-primary me-2">Tìm kiếm</button>
            <input type="text" id="maHangSX" class="form-control me-2" placeholder="Mã hãng sản xuất" />
            <input type="text" id="TenHang" class="form-control me-2" placeholder="Tên hãng sản xuất" />
            <input type="text" id="QuocGia" class="form-control me-2" placeholder="Quốc gia" />
        </div>

        <!-- Thêm mới -->
        <div class="d-flex justify-content-end">
            <a href="add.php?id=formHangSX" id="HangSX" class="btn btn-primary">Thêm mới</a>
        </div>
    </div>

    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th class="text-center">Mã Hãng Sản Xuất</th>
                <th>Tên Hãng Sản Xuất</th>
                <th>Quốc Gia</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($hsxList as $hsx): ?>
            <tr>
                <td class="text-center"><?php echo htmlspecialchars($hsx['MaHangSX']); ?></td>
                <td><?php echo htmlspecialchars($hsx['TenHang']); ?></td>
                <td><?php echo htmlspecialchars($hsx['QuocGia']); ?></td>
                <td>
                    <a href="edit_hangsx.php?MaHangSX=<?php echo $hsx['MaHangSX']; ?>" class="btn btn-primary">Sửa</a>
                    <a href="delete_hangsx.php?MaHangSX=<?php echo $hsx['MaHangSX']; ?>" class="btn btn-danger"
                        onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    let inputs = document.querySelectorAll("#maHangSX, #TenHang, #QuocGia");

    inputs.forEach(input => {
        input.addEventListener("input", function() {
            let maHangSX = document.getElementById("maHangSX").value.trim().toLowerCase();
            let tenHang = document.getElementById("TenHang").value.trim().toLowerCase();
            let quocGia = document.getElementById("QuocGia").value.trim().toLowerCase();

            let rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                let maHangSXText = row.cells[0]?.textContent.trim().toLowerCase() || "";
                let tenHangText = row.cells[1]?.textContent.trim().toLowerCase() || "";
                let quocGiaText = row.cells[2]?.textContent.trim().toLowerCase() || "";

                let matchMaHangSX = maHangSX === "" || maHangSXText.includes(maHangSX);
                let matchTenHang = tenHang === "" || tenHangText.includes(tenHang);
                let matchQuocGia = quocGia === "" || quocGiaText.includes(quocGia);

                row.style.display = (matchMaHangSX && matchTenHang && matchQuocGia) ?
                    "" : "none";
            });
        });
    });
});
</script>