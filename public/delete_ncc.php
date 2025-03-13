<?php
require_once __DIR__ . '/../src/bootstrap.php';

if (isset($_GET['MaNCC'])) {
    $maNCC = $_GET['MaNCC'];

    // Kiểm tra xem nhà cung cấp có tồn tại không
    $stmt = $pdo->prepare("SELECT * FROM NhaCungCap WHERE MaNCC = :maNCC");
    $stmt->execute(['maNCC' => $maNCC]);
    $ncc = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ncc) {
        die("Nhà cung cấp không tồn tại!");
    }

    // Thực hiện xóa
    $stmt = $pdo->prepare("DELETE FROM NhaCungCap WHERE MaNCC = :maNCC");
    $stmt->execute(['maNCC' => $maNCC]);

    header("Location: index.php?successNCC=Xóa thành công");
    exit();
}
?>