<?php
include_once '../../model/connectDB.php';
include_once '../../model/taikhoan.php';

if (isset($_POST['tenDangNhap'])) {
    $tenDN = $_POST['tenDangNhap'];

    if (kiemTraTenDangNhapTonTai($tenDN)) {
        echo "exists";
    } else {
        echo "ok";
    }
}
?>
