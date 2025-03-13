<div class="container-fluid-fluid" id="khachHang">
    <h2 class="section-title bg-light p-2 rounded potta-one-regular">Danh Sách Khách Hàng</h2>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Tìm kiếm -->
        <div class="d-flex">
            <button id="searchBtn" class="btn btn-primary me-2">Tìm kiếm</button>
            <input type="text" id="MaKH" class="form-control me-2" placeholder="Mã khách hàng" />
            <input type="text" id="TenKH" class="form-control me-2" placeholder="Tên khách hàng" />
            <input type="text" id="SoDienThoai" class="form-control me-2" placeholder="Số điện thoại" />
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
                        <a href="#" class="btn btn-primary">Sửa</a>
                        <a href="#" class="btn btn-danger">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>