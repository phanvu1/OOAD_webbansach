<?php
//trả về danh sách json 
require_once '../../model/connectDB.php';
require_once '../../model/nhaxuatban.php';

header('Content-Type:application/json');
$page = isset($_GET['page']) ? (int)$_GET['page']: 1;
if($page < 1) $page =1;
$limit = 7;

// Kiểm tra nếu có từ khóa tìm kiếm
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $keyword = trim($_GET['search']);
    $result = searchNXB($keyword, $page, $limit);
} else {
    $result = getDataNhaXuatBan($page, $limit);
}

echo json_encode([
    'data' => $result['data'],
    'currentPage' =>$result['currentPage'],
    'totalPages'=> $result['totalPages']
]);
exit;

?>
