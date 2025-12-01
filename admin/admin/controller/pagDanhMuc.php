<?php
//trả về danh sách sản phẩm dưới dạng JSON và phân trang
require_once '../../model/connectDB.php';
require_once '../../model/danhmuc.php';

header('Content-Type:application/json');

$page = isset($_GET['page']) ? (int)$_GET['page']: 1;
if($page < 1) $page = 1;

$limit = 7;

// Kiểm tra nếu có từ khóa tìm kiếm
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $keyword = trim($_GET['search']);
    $result = searchDanhMuc($keyword, $page, $limit);
} else {
    $result = getDataDanhMuc($page, $limit);
}

//$result chứa data và totalPages
echo json_encode([
    'currentPage'=>$result['currentPages'],
    'data'=>$result['data'],
    'totalPages'=>$result['totalPages']
]);
exit;

?>
