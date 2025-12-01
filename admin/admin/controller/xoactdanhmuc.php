<?php
require_once '../../model/connectDB.php';
require_once '../../model/ctdanhmuc.php';

$id = isset($_GET['idchitietdanhmuc']) ? intval($_GET['idchitietdanhmuc']) : null;
$iddanhmuc = isset($_GET['iddanhmuc']) ? intval($_GET['iddanhmuc']) : null;

if ($id && $iddanhmuc) {
    if (delCTDMById($id)) {
        echo "<script>alert('Xóa chi tiết danh mục thành công'); window.location.href='../controller/index.php?pg=chitietdanhmuc&iddanhmuc={$iddanhmuc}';</script>";
    } else {
        echo "<script>alert('Xóa thất bại'); window.location.href='../controller/index.php?pg=chitietdanhmuc&iddanhmuc={$iddanhmuc}';</script>";
    }
} else {
    echo "<script>alert('Yêu cầu không hợp lệ'); window.location.href='../controller/index.php?pg=danhmuc';</script>";
}
