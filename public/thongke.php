<div class="container-fluid" id="thongKe">
    <h2 class="section-title bg-light p-2 rounded potta-one-regular">Thống Kê Doanh Thu</h2>

    <!-- Hiển thị thông báo -->
    <?php if (!empty($successThongKe)): ?>
    <div class="alert alert-success alert-message"><?php echo htmlspecialchars($successThongKe); ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Dropdown menu chọn kiểu thống kê -->
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                data-bs-toggle="dropdown" aria-expanded="false">
                Chọn kiểu thống kê
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item dropdown-item1" href="#" data-type="ngay">Theo Ngày</a></li>
                <li><a class="dropdown-item dropdown-item1" href="#" data-type="tuan">Theo Tuần</a></li>
                <li><a class="dropdown-item dropdown-item1" href="#" data-type="thang">Theo Tháng</a></li>
                <li><a class="dropdown-item dropdown-item1" href="#" data-type="nam">Theo Năm</a></li>

            </ul>
        </div>

        <!-- Tìm kiếm theo khoảng thời gian -->
        <div class="d-flex">
            <button id="filterBtn" class="btn btn-primary me-2 mt-2">Tìm kiếm</button>
            <input type="date" id="startDate" class="form-control me-2" />
            <input type="date" id="endDate" class="form-control me-2" />
        </div>
    </div>

    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th class="text-center">
                    <?php 
                        if ($kieuThongKe == 'ngay') echo "Ngày";
                        elseif ($kieuThongKe == 'tuan') echo "Tuần";
                        elseif ($kieuThongKe == 'thang') echo "Tháng - Năm";
                        elseif ($kieuThongKe == 'nam') echo "Năm";
                    ?>
                </th>
                <th class="text-center">Doanh Thu</th>
            </tr>
        </thead>
        <tbody id="thongKeTable">
            <?php foreach ($thongKeList as $thongke): ?>
            <tr>
                <td class="text-center">
                    <?php
                        if ($kieuThongKe == 'ngay') {
                            echo date('Y-m-d', strtotime($thongke['ThoiGian'])); 
                        } elseif ($kieuThongKe == 'tuan' && isset($thongke['Tuan'])) {
                            echo "Tuần " . $thongke['Tuan'] . " (" . date('d/m', strtotime($thongke['NgayBatDau'])) . " - " . date('d/m', strtotime($thongke['NgayKetThuc'])) . ")";
                        } elseif ($kieuThongKe == 'thang') {
                            echo "Tháng " . $thongke['Thang'] . " Năm " . $thongke['Nam'];
                        }elseif ($kieuThongKe == 'nam') {
                            echo "Năm " . $thongke['Nam'];
                        }else {
                            echo "Không có dữ liệu";
                        }
                    ?>
                </td>
                <td class="text-center"><?php echo number_format($thongke['DoanhThu'], 0, ',', '.'); ?> VND</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Xử lý sự kiện khi chọn kiểu thống kê
    document.querySelectorAll(".dropdown-item1").forEach(item => {
        item.addEventListener("click", function(e) {
            e.preventDefault();
            let type = this.getAttribute("data-type");

            // Cập nhật URL và reload trang
            let url = new URL(window.location.href);
            url.searchParams.set("type", type);
            window.location.href = url.toString();
        });
    });


    // Lọc dữ liệu theo ngày
    document.getElementById("filterBtn").addEventListener("click", function() {
        let startDate = document.getElementById("startDate").value;
        let endDate = document.getElementById("endDate").value;
        let rows = document.querySelectorAll("#thongKeTable tr");

        rows.forEach(row => {
            let dateText = row.cells[0].textContent.trim();
            let rowDate = new Date(dateText);
            let isValid = true;

            if (startDate) {
                let start = new Date(startDate);
                if (rowDate < start) isValid = false;
            }

            if (endDate) {
                let end = new Date(endDate);
                if (rowDate > end) isValid = false;
            }

            row.style.display = isValid ? "" : "none";
        });
    });
});
</script>

<style>
#filterBtn {
    height: 35px;
    padding: 5px 15px;
    font-size: 14px;
}
</style>