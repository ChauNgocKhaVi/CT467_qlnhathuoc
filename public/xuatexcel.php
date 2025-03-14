<style>
    /* Điều chỉnh form xuất file */
    #xuatFile {
        margin: 30px auto;
        padding: 30px;
    }

    .form-check-label {
        font-size: 18px;
    }

    .btn-lg {
        font-size: 18px;
    }

    #exportForm {
        width: 700px;
        border: 2px solid #ccc;
        padding: 20px;
        border-radius: 8px;
    }

    #exportForm div {
        font-size: 30px;
    }

    #exportForm div label {
        font-size: 30px;
    }
</style>

<!-- Xuất file Excel -->
<div class="account-info">
    <div class="container-fluid" id="xuatFile">
        <h2 class="section-title bg-light p-2 rounded potta-one-regular">Xuất File Excel</h2>
        <h3 class="text-center" style="font-size: 50px">Chọn danh sách file </h3>
        <form id="exportForm" action="export.php" method="POST">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="export[]" value="thuoc" id="thuoc">
                <label class="form-check-label" for="thuoc">Danh sách Thuốc</label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="export[]" value="loai" id="loai">
                <label class="form-check-label" for="loai">Danh sách Loại Thuốc</label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="export[]" value="nhacungcap" id="ncc">
                <label class="form-check-label" for="ncc">Danh sách Nhà Cung Cấp</label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="export[]" value="hangsanxuat" id="hsx">
                <label class="form-check-label" for="hsx">Danh sách Hãng Sản Xuất</label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="export[]" value="khachhang" id="kh">
                <label class="form-check-label" for="kh">Danh sách Khách Hàng</label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="export[]" value="hoadon" id="hd">
                <label class="form-check-label" for="hd">Danh sách Hóa Đơn (kèm chi tiết)</label>
            </div>

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-success mt-3"
                    style="width: 200px; height: 50px; font-size: 20px">Xuất File Excel</button>
            </div>
        </form>
    </div>
</div>

<div id="previewTables" class="container mt-2"></div> <!-- Hiển thị bảng xem trước -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $(".form-check-input").change(function () {
            let selectedTables = [];

            $(".form-check-input:checked").each(function () {
                selectedTables.push($(this).val());
            });

            if (selectedTables.length > 0) {
                $.ajax({
                    url: "preview.php",
                    type: "POST",
                    data: { tables: JSON.stringify(selectedTables) }, // Sửa thành JSON
                    dataType: "html",
                    success: function (response) {
                        console.log("Response from preview.php:", response); // Debug
                        $("#previewTables").html(response);
                    },
                    error: function (xhr, status, error) {
                        console.log("AJAX Error:", error);
                    }
                });
            } else {
                $("#previewTables").html(""); // Xóa bảng nếu không chọn gì
            }
        });
    });
</script>