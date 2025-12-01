<?php
//trả về danh sách json 
require_once '../../model/connectDB.php';
require_once '../../model/khachhang.php';

header('Content-Type:application/json');
$page = isset($_GET['page']) ? (int)$_GET['page']: 1;
if($page < 1) $page =1;
$limit = 7;

if(isset($_GET['search']) && $_GET['search'] !== ''){
    $keyword = trim($_GET['search']);
    $result = searchKhachHang($page, $limit, $keyword);
}
else {
    $result = getDataKhachHang($page, $limit);
}

echo json_encode([
    'data' => $result['data'],
    'totalPages'=> $result['totalPages'],
    'currentPage' =>$result['currentPage']
]);
exit;

?>
