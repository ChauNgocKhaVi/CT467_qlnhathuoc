<div class="account-info">
    <div class="container-fluid" id="nhanVien">
        <h2 class="section-title bg-light p-2 rounded potta-one-regular ">Danh sách nhân viên</h2>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Tìm kiếm -->
            <div class="d-flex">
                <button id="searchBtn" class="btn btn-primary me-2">Tìm kiếm</button>
                <input type="text" id="MaND" class="form-control me-2" placeholder="Mã nhân viên" />
                <input type="text" id="HoTen" class="form-control me-2" placeholder="Họ và Tên" />
                <input type="text" id="SoDienThoai" class="form-control me-2" placeholder="Số điện thoại" />
                <input type="text" id="TenDangNhap" class="form-control me-2" placeholder="Tên đăng nhập" />
                <input type="text" id="Email" class="form-control me-2" placeholder="Email" />

                <!-- Dropdown Vai Trò -->
                <div class="dropdown me-2">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownVaiTro"
                        data-bs-toggle="dropdown">
                        Chọn Vai Trò
                    </button>
                    <ul class="dropdown-menu" id="vaiTroList">
                        <li><a class="dropdown-item" href="#" data-value="">Tất cả</a></li>
                        <?php
                        $uniqueVaiTro = [];
                        foreach ($nvList as $nv) {
                            if (!in_array($nv['VaiTro'], $uniqueVaiTro)) {
                                $uniqueVaiTro[] = $nv['VaiTro'];
                                echo '<li><a class="dropdown-item" href="#" data-value="' . htmlspecialchars($nv['VaiTro']) . '">' . htmlspecialchars($nv['VaiTro']) . '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>

                <!-- Dropdown Trạng Thái -->
                <div class="dropdown me-2">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownTrangThai"
                        data-bs-toggle="dropdown">
                        Chọn Trạng Thái
                    </button>
                    <ul class="dropdown-menu" id="trangThaiList">
                        <li><a class="dropdown-item" href="#" data-value="">Tất cả</a></li>
                        <?php
                        $uniqueTrangThai = [];
                        foreach ($nvList as $nv) {
                            if (!in_array($nv['TrangThai'], $uniqueTrangThai)) {
                                $uniqueTrangThai[] = $nv['TrangThai'];
                                echo '<li><a class="dropdown-item" href="#" data-value="' . htmlspecialchars($nv['TrangThai']) . '">' . htmlspecialchars($nv['TrangThai']) . '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <!-- Thêm mới -->
            <div class="d-flex justify-content-end">
                <a href="add.php?id=formNhanVien" id="NhanVien" class="btn btn-primary">Thêm mới</a>
            </div>
        </div>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Mã Nhân Viên</th>
                    <th>Họ và Tên</th>
                    <th>Số Điện Thoại</th>
                    <th>Tên đăng nhập</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($nvList as $nv): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($nv['MaND']); ?></td>
                        <td><?php echo htmlspecialchars($nv['HoTen']); ?></td>
                        <td><?php echo htmlspecialchars($nv['SoDienThoai']); ?></td>
                        <td><?php echo htmlspecialchars($nv['TenDangNhap']); ?></td>
                        <td><?php echo htmlspecialchars($nv['Email']); ?></td>
                        <td><?php echo htmlspecialchars($nv['VaiTro']); ?></td>
                        <td><?php echo htmlspecialchars($nv['TrangThai']); ?></td>
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchBtn = document.getElementById("searchBtn");

        const dropdownVaiTro = document.getElementById("dropdownVaiTro");
        const dropdownTrangThai = document.getElementById("dropdownTrangThai");

        const vaiTroItems = document.querySelectorAll("#vaiTroList .dropdown-item");
        const trangThaiItems = document.querySelectorAll("#trangThaiList .dropdown-item");

        const maNDInput = document.getElementById("MaND");
        const hoTenInput = document.getElementById("HoTen");
        const tenDangNhapInput = document.getElementById("TenDangNhap");
        const emailInput = document.getElementById("Email");
        const soDienThoaiInput = document.getElementById("SoDienThoai");

        const tableRows = document.querySelectorAll("tbody tr");

        let selectedVaiTro = "";
        let selectedTrangThai = "";

        function filterTable() {
            tableRows.forEach(row => {
                // Kiểm tra nếu hàng không có đủ số cột
                if (row.cells.length < 7) {
                    return;
                }

                let cellMaND = row.cells[0] ? row.cells[0].textContent.trim() : "";
                let cellHoTen = row.cells[1] ? row.cells[1].textContent.trim() : "";
                let cellSoDienThoai = row.cells[2] ? row.cells[2].textContent.trim() : "";
                let cellTenDangNhap = row.cells[3] ? row.cells[3].textContent.trim() : "";
                let cellEmail = row.cells[4] ? row.cells[4].textContent.trim() : "";
                let cellVaiTro = row.cells[5] ? row.cells[5].textContent.trim() : "";
                let cellTrangThai = row.cells[6] ? row.cells[6].textContent.trim() : "";

                // Chuyển đổi Mã nhân viên thành chuỗi để tránh lỗi
                let inputMaND = maNDInput.value.trim();
                let matchMaND = inputMaND === "" || cellMaND.includes(inputMaND);

                let matchHoTen = hoTenInput.value === "" || cellHoTen.toLowerCase().includes(hoTenInput.value.toLowerCase());
                let matchSoDienThoai = soDienThoaiInput.value === "" || cellSoDienThoai.includes(soDienThoaiInput.value);
                let matchTenDangNhap = tenDangNhapInput.value === "" || cellTenDangNhap.includes(tenDangNhapInput.value);
                let matchEmail = emailInput.value === "" || cellEmail.includes(emailInput.value);
                let matchVaiTro = selectedVaiTro === "" || cellVaiTro === selectedVaiTro;
                let matchTrangThai = selectedTrangThai === "" || cellTrangThai === selectedTrangThai;

                if (matchMaND && matchHoTen && matchSoDienThoai && matchTenDangNhap && matchEmail && matchVaiTro && matchTrangThai) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        // Lọc theo dropdown Vai Trò
        vaiTroItems.forEach(item => {
            item.addEventListener("click", function (event) {
                event.preventDefault();
                selectedVaiTro = this.getAttribute("data-value");
                dropdownVaiTro.textContent = this.textContent;
                filterTable();
            });
        });

        // Lọc theo dropdown Trạng Thái
        trangThaiItems.forEach(item => {
            item.addEventListener("click", function (event) {
                event.preventDefault();
                selectedTrangThai = this.getAttribute("data-value");
                dropdownTrangThai.textContent = this.textContent;
                filterTable();
            });
        });

        // Lọc theo input tìm kiếm
        maNDInput.addEventListener("input", filterTable);
        hoTenInput.addEventListener("input", filterTable);
        tenDangNhapInput.addEventListener("input", filterTable);
        emailInput.addEventListener("input", filterTable);
        soDienThoaiInput.addEventListener("input", filterTable);

        // Lọc khi bấm nút tìm kiếm
        searchBtn.addEventListener("click", function () {
            filterTable();
        });

        // Khi trang tải, hiển thị tất cả nhân viên
        filterTable();
    });


</script>