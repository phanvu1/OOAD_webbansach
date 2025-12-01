<?php
//trả về chuỗi json 
require_once '../../model/connectDB.php';
require_once '../../model/phieunhap.php';

header('Content-type: application/json');
$page = isset($_GET['page']) ? (int)$_GET['page'] :1;
if($page< 1) $page = 1;
$limit = 7;
$startDate = isset($_GET['start']) ? $_GET['start'] : null;
$endDate = isset($_GET['end']) ? $_GET['end'] : null;
$keyword = isset($_GET['search']) ? trim($_GET['search']) : null;

if (!empty($keyword)) {
    $result = searchPhieuNhap($keyword, $page, $limit);
}
elseif (!empty($startDate) || !empty($endDate)) {
    $result = getDataPhieuNhapTheoKhoangThoiGian($page, $limit, $startDate, $endDate);
}
else {
    $result = getDataPhieuNhap($page, $limit);
}
 
echo json_encode([
    'data' => $result['data'],
    'currentPage' =>$result['currentPage'],
    'totalPages'=>$result['totalPages']
]);
exit;
?>
