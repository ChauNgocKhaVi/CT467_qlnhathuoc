<div class="account-info">
    <div class="container-fluid" id="hoaDon">
        <h2 class="section-title bg-light p-2 rounded potta-one-regular ">Hóa Đơn</h2>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Tìm kiếm -->
            <div class="d-flex">
                <button id="searchBtn" class="btn btn-primary me-2">Tìm kiếm</button>
                <input type="text" id="MaHD" class="form-control me-2" placeholder="Mã hóa đơn" />
                <input type="text" id="MaKH" class="form-control me-2" placeholder="Mã khách hàng" />
                <input type="text" id="NgayLap" class="form-control me-2" placeholder="Ngày lập" />
                <input type="text" id="TongTien" class="form-control me-2" placeholder="Tổng tiền" />
            </div>

            <!-- Thêm mới -->
            <div class="d-flex justify-content-end">
                <a href="add.php?id=formHoaDon" id="HoaDon" class="btn btn-primary">Thêm mới</a>
            </div>
        </div>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Mã Hóa Đơn</th>
                    <th>Tên Khách Hàng</th>
                    <th>Số Điện Thoại</th>
                    <th>Ngày Lập</th>
                    <th>Tổng tiền</th>
                    <th>Chi Tiết</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hoadonList as $hoadon): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($hoadon['MaHD']); ?></td>
                        <td><?php echo htmlspecialchars($hoadon['TenKH']); ?></td>
                        <td><?php echo htmlspecialchars($hoadon['SoDienThoai']); ?></td>
                        <td><?php echo htmlspecialchars($hoadon['NgayLap']); ?></td>
                        <td><?php echo htmlspecialchars($hoadon['TongTien']); ?></td>
                        <td>Xem chi tiết</td>
                        <td>
                            <a href="#" class="btn btn-primary">Sửa</a>
                            <a href="#" class="btn btn-danger">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>