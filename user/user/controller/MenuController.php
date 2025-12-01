<?php
include_once '../../model/connectDB.php';

// Hàm lấy danh mục
function getDanhMucList() {
    $conn = connectdb();
    $query = "SELECT * FROM danhmuc WHERE trangthai = 1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Hàm lấy danh mục con
function getSubCategories($iddanhmuc) {
    $conn = connectdb();
    $query = "SELECT * FROM chitietdanhmuc WHERE iddanhmuc = :iddanhmuc";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':iddanhmuc', $iddanhmuc);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy dữ liệu danh mục
$danhmuc_list = getDanhMucList();
?>