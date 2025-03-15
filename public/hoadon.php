<!-- Hóa đơn -->
<div class="account-info">
    <div class="container-fluid" id="hoaDon">
        <h2 class="section-title bg-light p-2 rounded potta-one-regular ">Hóa Đơn</h2>
        <!-- Hiển thị thông báo -->
        <?php if (!empty($successHD)): ?>
        <div class="alert alert-success alert-message"><?php echo htmlspecialchars(string: $successHD); ?></div>
        <?php endif; ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Tìm kiếm -->
            <div class="d-flex">
                <button id="searchBtn" class="btn btn-primary me-2">Tìm kiếm</button>
                <input type="text" id="MaHD" class="form-control me-2" placeholder="Mã hóa đơn" />
                <input type="text" id="TenKH" class="form-control me-2" placeholder="Tên khách hàng" />
                <input type="date" id="NgayLap" class="form-control me-2" placeholder="Ngày lập" />
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
                    <th class="text-center">Mã Hóa Đơn</th>
                    <th>Tên Khách Hàng</th>
                    <th>Số Điện Thoại</th>
                    <th>Ngày Lập</th>
                    <th>Tổng tiền</th>
                    <th class="text-center">Chi Tiết</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hoadonList as $hoadon): ?>
                <tr>
                    <td class="text-center"><?php echo htmlspecialchars($hoadon['MaHD']); ?></td>
                    <td><?php echo htmlspecialchars($hoadon['TenKH']); ?></td>
                    <td><?php echo htmlspecialchars($hoadon['SoDienThoai']); ?></td>
                    <td><?php echo htmlspecialchars($hoadon['NgayLap']); ?></td>
                    <td><?php echo htmlspecialchars($hoadon['TongTien']); ?></td>
                    <td>
                        <!-- Nút mở modal -->
                        <button class="btn btn-info btn-chi-tiet" data-bs-toggle="modal" data-bs-target="#modalChiTiet"
                            data-mahd="<?php echo $hoadon['MaHD']; ?>">
                            Xem chi tiết
                        </button>
                    </td>
                    <td class="text-center">
                        <a href="edit_hoadon.php?MaHD=<?php echo $hoadon['MaHD']; ?>" class="btn btn-primary">Sửa</a>
                        <a href="delete_hoadon.php?MaHD=<?php echo $hoadon['MaHD']; ?>" class="btn btn-danger"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa hóa đơn này?')">Xóa </a>
                    </td>

                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Chi Tiết Hóa Đơn -->
<div class="modal fade" id="modalChiTiet" tabindex="-1" aria-labelledby="modalChiTietLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalChiTietLabel">Chi Tiết Hóa Đơn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                    <tbody id="chiTietContent">
                        <!-- Nội dung chi tiết hóa đơn sẽ được thêm vào bằng JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript để tải chi tiết hóa đơn khi mở modal -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const modalChiTiet = document.getElementById("modalChiTiet");
    const chiTietContent = document.getElementById("chiTietContent");

    document.querySelectorAll(".btn-chi-tiet").forEach(button => {
        button.addEventListener("click", function() {
            const maHD = this.getAttribute("data-mahd");

            // Gửi AJAX để lấy chi tiết hóa đơn
            fetch("get_chitiet_hoadon.php?MaHD=" + maHD)
                .then(response => response.json())
                .then(data => {
                    let content = "";
                    data.forEach(item => {
                        content += `
                            <tr>
                                <td>${item.MaThuoc}</td>
                                <td>${item.TenThuoc}</td>
                                <td>${item.SoLuongBan}</td>
                                <td>${new Intl.NumberFormat().format(item.GiaBan)} VNĐ</td>
                                <td>${new Intl.NumberFormat().format(item.ThanhTien)} VNĐ</td>
                            </tr>
                        `;
                    });
                    chiTietContent.innerHTML = content;
                })
                .catch(error => console.error("Lỗi khi tải chi tiết hóa đơn:", error));
        });
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    let inputs = document.querySelectorAll("#MaHD, #TenKH, #NgayLap, #TongTien");

    inputs.forEach(input => {
        input.addEventListener("input", function() {
            let maHD = document.getElementById("MaHD").value.trim().toLowerCase();
            let tenKH = document.getElementById("TenKH").value.trim().toLowerCase();
            let ngayLap = document.getElementById("NgayLap").value.trim().toLowerCase();
            let tongTien = document.getElementById("TongTien").value.trim().toLowerCase();

            let rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                let maHDText = row.cells[0]?.textContent.trim().toLowerCase() || "";
                let tenKHText = row.cells[1]?.textContent.trim().toLowerCase() || "";
                let ngayLapText = row.cells[3]?.textContent.trim().toLowerCase() || "";
                let tongTienText = row.cells[4]?.textContent.trim().toLowerCase() || "";

                let matchMaHD = maHD === "" || maHDText.includes(maHD);
                let matchTenKH = tenKH === "" || tenKHText.includes(tenKH);
                let matchNgayLap = ngayLap === "" || ngayLapText.includes(ngayLap);
                let matchTongTien = tongTien === "" || tongTienText.includes(tongTien);

                row.style.display = (matchMaHD && matchTenKH && matchNgayLap &&
                    matchTongTien) ? "" : "none";
            });
        });
    });
});
</script>