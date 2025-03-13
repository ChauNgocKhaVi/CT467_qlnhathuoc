<div class="account-info">
    <div class="container-fluid" id="thuoc">
        <h2 class="section-title bg-light p-2 rounded potta-one-regular">Danh Sách Thuốc</h2>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Tìm kiếm -->
            <div class="d-flex">
                <button id="searchBtn" class="btn btn-primary me-2">Tìm kiếm</button>
                <input type="text" id="maThuoc" class="form-control me-2" placeholder="Mã thuốc" />
                <input type="text" id="tenThuoc" class="form-control me-2" placeholder="Tên thuốc" />
                <!-- Dropdown Mã Loại -->
                <div class="dropdown me-2">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownLoai"
                        data-bs-toggle="dropdown">
                        Chọn Mã Loại
                    </button>
                    <ul class="dropdown-menu" id="loaiList">
                        <li><a class="dropdown-item" href="#" data-value="">Tất cả</a></li>
                        <?php
                        $uniqueLoai = [];
                        foreach ($thuocList as $thuoc) {
                            if (!in_array($thuoc['TenLoai'], $uniqueLoai)) {
                                $uniqueLoai[] = $thuoc['TenLoai'];
                                echo '<li><a class="dropdown-item" href="#" data-value="' . htmlspecialchars($thuoc['TenLoai']) . '">' . htmlspecialchars($thuoc['TenLoai']) . '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>

                <!-- Dropdown Hãng Sản Xuất -->
                <div class="dropdown me-2">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownHang"
                        data-bs-toggle="dropdown">
                        Chọn Hãng SX
                    </button>
                    <ul class="dropdown-menu" id="hangList">
                        <li><a class="dropdown-item" href="#" data-value="">Tất cả</a></li>
                        <?php
                        $uniqueHang = [];
                        foreach ($thuocList as $thuoc) {
                            if (!in_array($thuoc['TenHang'], $uniqueHang)) {
                                $uniqueHang[] = $thuoc['TenHang'];
                                echo '<li><a class="dropdown-item" href="#" data-value="' . htmlspecialchars($thuoc['TenHang']) . '">' . htmlspecialchars($thuoc['TenHang']) . '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>

                <!-- Dropdown Nhà Cung Cấp -->
                <div class="dropdown me-2">
                    <button class="btn btn-secondary dropdown-toggle" style="width:230px;" type="button" id="dropdownNCC"
                        data-bs-toggle="dropdown">
                        Chọn Nhà Cung Cấp
                    </button>
                    <ul class="dropdown-menu" id="nccList" style="width:230px;">
                        <li><a class="dropdown-item" href="#" data-value="">Tất cả</a></li>
                        <?php
                        $uniqueNCC = [];
                        foreach ($thuocList as $thuoc) {
                            if (!in_array($thuoc['TenNCC'], $uniqueNCC)) {
                                $uniqueNCC[] = $thuoc['TenNCC'];
                                echo '<li><a class="dropdown-item" href="#" data-value="' . htmlspecialchars($thuoc['TenNCC']) . '">' . htmlspecialchars($thuoc['TenNCC']) . '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
                <input type="number" id="donGia" class="form-control me-2" placeholder="Đơn giá" />
                <input type="number" id="soLuongTon" class="form-control me-2" placeholder="SL Tồn kho" />
                <input type="date" id="hanSuDung" class="form-control me-2" placeholder="Hạn sử dụng" />
            </div>

            <!-- Thêm mới -->
            <div class="d-flex justify-content-end">
                <a href="add.php?id=formThuoc" id="Thuoc" class="btn btn-primary">Thêm mới</a>
            </div>
        </div>

        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th class="text-center">Mã Thuốc</th>
                    <th>Tên Thuốc</th>
                    <th>Loại</th>
                    <th>Hãng Sản Xuất</th>
                    <th>Nhà Cung Cấp</th>
                    <th>Công dụng</th>
                    <th>Giá (VNĐ)</th>
                    <th class="text-center">Số Lượng</th>
                    <th class="text-center">HSD</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($thuocList as $thuoc): ?>
                    <tr>
                        <td class="text-center"><?php echo htmlspecialchars($thuoc['MaThuoc']); ?></td>
                        <td><?php echo htmlspecialchars($thuoc['TenThuoc']); ?></td>
                        <td><?php echo htmlspecialchars($thuoc['TenLoai']); ?></td>
                        <td><?php echo htmlspecialchars($thuoc['TenHang']); ?></td>
                        <td><?php echo htmlspecialchars($thuoc['TenNCC']); ?></td>
                        <td><?php echo htmlspecialchars($thuoc['CongDung']); ?></td>
                        <td><?php echo number_format($thuoc['Gia'], 0, ',', '.'); ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($thuoc['SoLuong']); ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($thuoc['HanSuDung']); ?></td>
                        <td class="text-center">
                            <a href="#" class="btn btn-primary">Sửa</a>
                            <a href="#" class="btn btn-danger">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchBtn = document.getElementById("searchBtn");

        const dropdownLoai = document.getElementById("dropdownLoai");
        const dropdownHang = document.getElementById("dropdownHang");
        const dropdownNCC = document.getElementById("dropdownNCC");

        const loaiItems = document.querySelectorAll("#loaiList .dropdown-item");
        const hangItems = document.querySelectorAll("#hangList .dropdown-item");
        const nccItems = document.querySelectorAll("#nccList .dropdown-item");

        const maThuocInput = document.getElementById("maThuoc");
        const tenThuocInput = document.getElementById("tenThuoc");
        const donGiaInput = document.getElementById("donGia");
        const soLuongTonInput = document.getElementById("soLuongTon");
        const hanSuDungInput = document.getElementById("hanSuDung");

        const tableRows = document.querySelectorAll("tbody tr");

        let selectedLoai = "";
        let selectedHang = "";
        let selectedNCC = "";

        function filterTable() {
            tableRows.forEach(row => {
                let cellMaThuoc = row.cells[0].textContent.trim();
                let cellTenThuoc = row.cells[1].textContent.trim();
                let cellLoai = row.cells[2].textContent.trim();
                let cellHang = row.cells[3].textContent.trim();
                let cellNCC = row.cells[4].textContent.trim();
                let cellGia = row.cells[6].textContent.trim().replace(/\D/g, ''); // Lấy số trong giá tiền
                let cellSoLuong = parseInt(row.cells[7].textContent.trim().replace(/\D/g, ''), 10) || 0; // Chuyển đổi số lượng thành số nguyên
                let cellHSD = new Date(row.cells[8].textContent.trim()); // Chuyển đổi ngày hạn sử dụng

                let inputSoLuong = soLuongTonInput.value.trim();
                let inputHSD = hanSuDungInput.value.trim();

                let matchMaThuoc = maThuocInput.value === "" || cellMaThuoc.includes(maThuocInput.value);
                let matchTenThuoc = tenThuocInput.value === "" || cellTenThuoc.toLowerCase().includes(tenThuocInput.value.toLowerCase());
                let matchLoai = selectedLoai === "" || cellLoai === selectedLoai;
                let matchHang = selectedHang === "" || cellHang === selectedHang;
                let matchNCC = selectedNCC === "" || cellNCC === selectedNCC;
                let matchGia = donGiaInput.value === "" || parseInt(cellGia) <= parseInt(donGiaInput.value);
                let matchSoLuong = inputSoLuong === "" || cellSoLuong === parseInt(inputSoLuong, 10);
                let matchHSD = inputHSD === "" || cellHSD >= new Date(inputHSD);

                if (matchMaThuoc && matchTenThuoc && matchLoai && matchHang && matchNCC && matchGia && matchSoLuong && matchHSD) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        // Lọc theo dropdown Loại
        loaiItems.forEach(item => {
            item.addEventListener("click", function (event) {
                event.preventDefault();
                selectedLoai = this.getAttribute("data-value");
                dropdownLoai.textContent = this.textContent;
                filterTable();
            });
        });

        // Lọc theo dropdown hãng SX
        hangItems.forEach(item => {
            item.addEventListener("click", function (event) {
                event.preventDefault();
                selectedHang = this.getAttribute("data-value");
                dropdownHang.textContent = this.textContent;
                filterTable();
            });
        });

        // Lọc theo dropdown NCC
        nccItems.forEach(item => {
            item.addEventListener("click", function (event) {
                event.preventDefault();
                selectedNCC = this.getAttribute("data-value");
                dropdownNCC.textContent = this.textContent;
                filterTable();
            });
        });

        // Lọc theo input tìm kiếm
        maThuocInput.addEventListener("input", filterTable);
        tenThuocInput.addEventListener("input", filterTable);
        donGiaInput.addEventListener("input", filterTable);
        soLuongTonInput.addEventListener("input", filterTable);
        hanSuDungInput.addEventListener("input", filterTable);

        // Lọc khi bấm nút tìm kiếm
        searchBtn.addEventListener("click", function () {
            filterTable();
        });
    });

</script>