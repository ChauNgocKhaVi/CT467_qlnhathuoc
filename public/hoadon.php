<!-- Hóa đơn -->
<div class="account-info">
    <div class="container-fluid" id="hoaDon">
        <h2 class="section-title bg-light p-2 rounded potta-one-regular ">Hóa Đơn</h2>
        <!-- Hiển thị thông báo -->
        <?php if (!empty($successHD)): ?>
            <div class="alert alert-success alert-message"><?php echo htmlspecialchars($successHD); ?></div>
        <?php endif; ?>
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
                <a href="add.php?id=formHD" id="HoaDon" class="btn btn-primary">Thêm mới</a>
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
                        <td>
                            <a href="index.php?MaHD=<?php echo htmlspecialchars($hoadon['MaHD']); ?>" class="btn btn-info">
                                Xem chi tiết
                            </a>
                        </td>
                        <td>
                            <a href="edit_hoadon.php?MaHD=<?php echo $hoadon['MaHD']; ?>" class="btn btn-primary">Sửa</a>
                            <a href="delete_hoadon.php?MaHD=<?php echo $hoadon['MaHD']; ?>" class="btn btn-danger"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa hóa đơn này?');">Xóa</a>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (!empty($chiTietHD)): ?>
            <h3 class="mt-4">Chi Tiết Hóa Đơn #<?php echo htmlspecialchars($maHD); ?></h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã Thuốc</th>
                        <th>Tên Thuốc</th>
                        <th>Số Lượng</th>
                        <th>Giá Bán</th>
                        <th>Thành Tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($chiTietHD as $ct): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ct['MaThuoc']); ?></td>
                            <td><?php echo htmlspecialchars($ct['TenThuoc']); ?></td>
                            <td><?php echo htmlspecialchars($ct['SoLuongBan']); ?></td>
                            <td><?php echo number_format($ct['GiaBan'], 2); ?> VNĐ</td>
                            <td><?php echo number_format($ct['ThanhTien'], 2); ?> VNĐ</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>