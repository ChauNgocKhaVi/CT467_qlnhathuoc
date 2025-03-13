<div class="container-fluid-fluid" id="ncc">
    <h2 class="section-title bg-light p-2 rounded potta-one-regular">Danh Sách Nhà Cung Cấp</h2>
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
                <th>Số Điện Thoại/th>
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
                        <a href="#" class="btn btn-primary">Sửa</a>
                        <a href="#" class="btn btn-danger">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>