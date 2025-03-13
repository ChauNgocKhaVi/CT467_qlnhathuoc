<!-- Thông báo thuốc sắp hết hạn -->
<div class="account-info">
    <div class="container-fluid" id="thongBao">
        <h2 class="section-title bg-light p-2 rounded potta-one-regular">Thông báo thuốc sắp hết hạn</h2>

        <?php if (!empty($thongBaoList)): ?>
            <div class="alert alert-warning">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Mã Thuốc</th>
                            <th>Tên Thuốc</th>
                            <th>Ngày Hết Hạn</th>
                            <th>Trạng Thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($thongBaoList as $tb): ?>
                            <tr data-id="<?php echo htmlspecialchars($tb['MaThuoc']); ?>">
                                <td class="text-center"><?php echo htmlspecialchars($tb['MaThuoc']); ?></td>
                                <td><?php echo htmlspecialchars($tb['TenThuoc']); ?></td>
                                <td><?php echo htmlspecialchars($tb['ThoiGianThongBao']); ?></td>
                                <td class="trang-thai">
                                    <?php if ($tb['TrangThai'] === 'chua_xem'): ?>
                                        <span class="badge bg-warning text-dark">Chưa xem</span>
                                        <?php $coThongBaoChuaXem = true; ?>
                                    <?php else: ?>
                                        <span class="badge bg-success">Đã xem</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Chỉ hiển thị nút nếu có thông báo chưa xem -->
                <?php if ($coThongBaoChuaXem): ?>
                    <div class="d-flex justify-content-center">
                        <button id="btnXacNhanThongBao" class="btn btn-success" style="width:200px">Đánh dấu đã đọc</button>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p class="text-muted">Không có thuốc nào sắp hết hạn.</p>
        <?php endif; ?>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const btnXacNhan = document.getElementById("btnXacNhanThongBao");

        if (btnXacNhan) {
            btnXacNhan.addEventListener("click", function () {
                fetch("update_thongbao.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire("Thành công!", "Tất cả thông báo đã được đánh dấu là đã đọc.", "success");

                            // Cập nhật giao diện
                            document.querySelectorAll(".trang-thai").forEach(td => {
                                td.innerHTML = `<span class="badge bg-success">Đã xem</span>`;
                            });

                            // Ẩn nút xác nhận sau khi cập nhật
                            btnXacNhan.style.display = "none";
                        } else {
                            Swal.fire("Lỗi!", "Không thể cập nhật thông báo.", "error");
                        }
                    })
                    .catch(error => console.error("Lỗi:", error));
            });
        }
    });
</script>