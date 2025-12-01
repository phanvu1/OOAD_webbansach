<?php
require_once '../../model/connectDB.php';
require_once '../../model/sanpham.php';

header('Content-Type: application/json');

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$limit = 4;

// Kiểm tra nếu có từ khóa tìm kiếm
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $keyword = trim($_GET['search']);
    $result = searchSanPham($keyword, $page, $limit);
} else {
    $result = getDataSanPham($page, $limit);
}

echo json_encode([
    'data' => $result['data'],
    'totalPages' => $result['totalPages'],
    'currentPage' => $page
]);
exit;
?>
