<?php
require_once '../../model/connectDB.php';
require_once '../../model/taikhoankhachhang.php';

header('Content-Type:application/json');
$page = isset($_GET['page']) ? (int)$_GET['page']: 1;
if($page < 0) $page = 1;
$limit = 7;

if(isset($_GET['search']) && $_GET['search'] !== ''){
    $keyword = $_GET['search'];
    $result = searchTKKH($keyword, $page, $limit);
}
else {
    $result = getDataTaiKhoanKhachHang($page, $limit);
}

//trả về dãy json
echo json_encode([
    'data'=>$result['data'],
    'currentPage'=>$result['currentPage'],
    'totalPages'=>$result['totalPages']
]);
exit;
?>
