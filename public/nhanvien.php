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
                    <th class="text-center">Mã Nhân Viên</th>
                    <th>Họ và Tên</th>
                    <th>Số Điện Thoại</th>
                    <th>Tên đăng nhập</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($nvList as $nv): ?>
                    <tr>
                        <td class="text-center"><?php echo htmlspecialchars($nv['MaND']); ?></td>
                        <td><?php echo htmlspecialchars($nv['HoTen']); ?></td>
                        <td><?php echo htmlspecialchars($nv['SoDienThoai']); ?></td>
                        <td><?php echo htmlspecialchars($nv['TenDangNhap']); ?></td>
                        <td><?php echo htmlspecialchars($nv['Email']); ?></td>
                        <td><?php echo htmlspecialchars($nv['VaiTro']); ?></td>
                        <td><?php echo htmlspecialchars($nv['TrangThai']); ?></td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-primary btn-edit-nv me-2" data-id="<?= $nv['MaND'] ?>"
                                    data-hoten="<?= htmlspecialchars($nv['HoTen']) ?>"
                                    data-sdt="<?= htmlspecialchars($nv['SoDienThoai']) ?>"
                                    data-tendangnhap="<?= htmlspecialchars($nv['TenDangNhap']) ?>"
                                    data-email="<?= htmlspecialchars($nv['Email']) ?>"
                                    data-vaitro="<?= htmlspecialchars($nv['VaiTro']) ?>"
                                    data-trangthai="<?= htmlspecialchars($nv['TrangThai']) ?>">
                                    Sửa
                                </button>

                                <button class="btn btn-danger btn-delete-nv" data-id="<?= $nv['MaND'] ?>"
                                    data-ten="<?= htmlspecialchars($nv['HoTen']) ?>">
                                    Xóa
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal chỉnh sửa nhân viên -->
<div class="modal fade" id="editNhanVienModal" tabindex="-1" aria-labelledby="editNhanVienModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editNhanVienModalLabel">Chỉnh Sửa Nhân Viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editNhanVienForm">
                    <input type="hidden" id="editMaND" name="MaND">

                    <div class="mb-3">
                        <label for="editHoTen" class="form-label">Họ và Tên</label>
                        <input type="text" class="form-control" id="editHoTen" name="HoTen" required>
                    </div>

                    <div class="mb-3">
                        <label for="editSoDienThoai" class="form-label">Số Điện Thoại</label>
                        <input type="text" class="form-control" id="editSoDienThoai" name="SoDienThoai" required>
                    </div>

                    <div class="mb-3">
                        <label for="editTenDangNhap" class="form-label">Tên Đăng Nhập</label>
                        <input type="text" class="form-control" id="editTenDangNhap" name="TenDangNhap" required>
                    </div>

                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="Email" required>
                    </div>

                    <div class="mb-3">
                        <label for="editVaiTro" class="form-label">Vai Trò</label>
                        <select class="form-control" id="editVaiTro" name="VaiTro" required>
                            <option value="admin">Admin</option>
                            <option value="nhanvien">Nhân viên</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editTrangThai" class="form-label">Trạng Thái</label>
                        <select class="form-control" id="editTrangThai" name="TrangThai" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Mở modal sửa nhân viên -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const editButtons = document.querySelectorAll(".btn-edit-nv");

        editButtons.forEach(button => {
            button.addEventListener("click", function () {
                document.getElementById("editMaND").value = this.dataset.id;
                document.getElementById("editHoTen").value = this.dataset.hoten;
                document.getElementById("editSoDienThoai").value = this.dataset.sdt;
                document.getElementById("editTenDangNhap").value = this.dataset.tendangnhap;
                document.getElementById("editEmail").value = this.dataset.email;
                document.getElementById("editVaiTro").value = this.dataset.vaitro;
                document.getElementById("editTrangThai").value = this.dataset.trangthai;

                const editModal = new bootstrap.Modal(document.getElementById("editNhanVienModal"));
                editModal.show();
            });
        });

        document.getElementById("editNhanVienForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch("edit.php", {
                method: "POST",
                body: JSON.stringify(Object.fromEntries(formData)),
                headers: { "Content-Type": "application/json" }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire("Đã cập nhật!", "Thông tin nhân viên đã được cập nhật.", "success").then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire("Lỗi!", "Không thể cập nhật thông tin.", "error");
                    }
                })
                .catch(error => console.error("Lỗi:", error));
        });
    });

</script>

<!-- Xóa nhân viên -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const deleteButtons = document.querySelectorAll(".btn-delete-nv");

        deleteButtons.forEach(button => {
            button.addEventListener("click", function () {
                const maND = this.dataset.id;
                const tenNhanVien = this.dataset.ten;

                Swal.fire({
                    title: `Bạn có chắc muốn xóa nhân viên "${tenNhanVien}"?`,
                    text: "Hành động này không thể hoàn tác!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Xóa",
                    cancelButtonText: "Hủy",
                    buttonsStyling: false,
                    customClass: {
                        actions: 'swal2-actions',
                        confirmButton: 'swal2-confirm btn btn-danger',
                        cancelButton: 'swal2-cancel btn btn-primary'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("delete.php", {
                            method: "POST",
                            body: JSON.stringify({ MaND: maND }),
                            headers: { "Content-Type": "application/json" }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire("Đã xóa!", "Nhân viên đã bị xóa.", "success").then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire("Lỗi!", "Không thể xóa nhân viên.", "error");
                                }
                            })
                            .catch(error => console.error("Lỗi:", error));
                    }
                });
            });
        });
    });
</script>

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