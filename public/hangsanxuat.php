<div class="container-fluid-fluid" id="hsx">
    <h2 class="section-title bg-light p-2 rounded potta-one-regular">Danh Sách Hãng Sản Xuất</h2>
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
                        <a href="#" class="btn btn-primary">Sửa</a>
                        <a href="#" class="btn btn-danger">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>