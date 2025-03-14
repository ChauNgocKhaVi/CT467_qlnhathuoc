<style>
    /* Điều chỉnh form xuất file */
    #nhapExcel {
        margin: 30px auto;
        padding: 30px;
    }

    .form-check-label {
        font-size: 18px;
    }

    .btn-lg {
        font-size: 18px;
    }

    #importForm {
        width: 700px;
        border: 2px solid #ccc;
        padding: 20px;
        border-radius: 8px;
    }

    #importForm div {
        font-size: 30px;
    }

    #importForm div label {
        font-size: 30px;
    }
</style>

<!-- Xuất file Excel -->
<div class="account-info">
    <div class="container-fluid" id="nhapExcel">
        <h2 class="section-title bg-light p-2 rounded potta-one-regular">Nhập File từ Excel</h2>
        <h3 class="text-center" style="font-size: 50px">Chọn danh sách file </h3>
        <form id="importForm" action="import.php" method="POST" enctype="multipart/form-data">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="import[]" value="thuoc" id="thuoc">
                <label class="form-check-label" for="thuoc">Danh sách Thuốc</label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="import[]" value="loai" id="loai">
                <label class="form-check-label" for="loai">Danh sách Loại Thuốc</label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="import[]" value="nhacungcap" id="ncc">
                <label class="form-check-label" for="ncc">Danh sách Nhà Cung Cấp</label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="import[]" value="hangsanxuat" id="hsx">
                <label class="form-check-label" for="hsx">Danh sách Hãng Sản Xuất</label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="import[]" value="khachhang" id="kh">
                <label class="form-check-label" for="kh">Danh sách Khách Hàng</label>
            </div>

            <div class="mt-3">
                <input type="file" name="excelFile" class="form-control" required>
            </div>

            <div class="d-flex justify-content-center mt-3">
                <button type="submit" class="btn btn-success mt-3"
                    style="width: 200px; height: 50px; font-size: 20px">Nhập Dữ Liệu</button>
            </div>
        </form>

    </div>
</div>