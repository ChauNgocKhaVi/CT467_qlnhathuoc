<div class="container-fluid" id="thongKe">
    <h2 class="section-title bg-light p-2 rounded potta-one-regular">Thống Kê Doanh Thu</h2>

    <!-- Hiển thị thông báo -->
    <?php if (!empty($successThongKe)): ?>
    <div class="alert alert-success alert-message"><?php echo htmlspecialchars($successThongKe); ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
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
                <th class="text-center">Ngày</th>
                <th class="text-center">Doanh Thu</th>
            </tr>
        </thead>
        <tbody id="thongKeTable">
            <?php foreach ($thongKeList as $thongke): ?>
            <tr>
                <td class="text-center"><?php echo date('Y-m-d', strtotime($thongke['Ngay'])); ?></td>
                <td class="text-center"><?php echo number_format($thongke['DoanhThu'], 0, ',', '.'); ?> VND</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("filterBtn").addEventListener("click", function() {
        let startDate = document.getElementById("startDate").value;
        let endDate = document.getElementById("endDate").value;
        let rows = document.querySelectorAll("#thongKeTable tr");

        console.log("Start Date:", startDate);
        console.log("End Date:", endDate);

        rows.forEach(row => {
            let dateText = row.cells[0].textContent
                .trim(); // Lấy ngày từ cột đầu tiên (đã chuẩn hóa YYYY-MM-DD)
            let rowDate = new Date(dateText); // Chuyển thành đối tượng Date hợp lệ

            console.log("Row Date:", rowDate);

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