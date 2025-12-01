<?php
//trả về danh sách json 
require_once '../../model/connectDB.php';
require_once '../../model/tacgia.php';

header('Content-Type:application/json');
$page = isset($_GET['page']) ? (int)$_GET['page']: 1;
if($page < 1) $page =1;
$limit = 3;

// Kiểm tra nếu có từ khóa tìm kiếm
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $keyword = trim($_GET['search']);
    $result = searchTacGia($keyword, $page, $limit);
} else {
    $result = getDataTacGia($page, $limit);
}

echo json_encode(value: [
    'data' => $result['data'],
    'totalPages'=> $result['totalPages'],
    'currentPage' =>$result['currentPage']
]);
exit;

?>
