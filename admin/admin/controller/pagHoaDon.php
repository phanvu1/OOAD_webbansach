<?php
require_once '../../model/connectDB.php';
require_once '../../model/hoadon.php';

header('Content-Type: application/json');

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$limit = 7;
$startDate = isset($_GET['startDate']) ? trim($_GET['startDate']) : null;
$endDate = isset($_GET['endDate']) ? trim($_GET['endDate']) : null;
$keyword = isset($_GET['search']) ? trim($_GET['search']) : null;
$city = isset($_GET['city']) ? trim($_GET['city']) : null;
$status = isset($_GET['status']) ? trim($_GET['status']) : null;

if (!empty($keyword)) {
    $result = searchHoaDon($keyword, $page, $limit);
} else {
    $result = getDataHoaDonFiltered($page, $limit, $startDate, $endDate, $city, $status);
}

echo json_encode([
    'data' => $result['data'],
    'currentPage' => $result['currentPage'],
    'totalPages' => $result['totalPages']
]);
exit;
?>
