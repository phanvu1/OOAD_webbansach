<?php
require_once '../../model/connectDB.php';
require_once '../../model/nhacungcap.php';

header('Content-Type:application/json');
$page = isset($_GET['page']) ? (int)$_GET['page'] :1;
if($page < 1) $page = 1;
$limit = 7;

if(isset($_GET['search']) && $_GET['search'] !== ''){
    $keyword = $_GET['search'];
    $result = searchNCC($page, $limit, $keyword);
}
else {
    $result = getDataNhaCungCap($page, $limit);
}

//trả về chuỗi json
echo json_encode([
    'data'=>$result['data'],
    'currentPage'=>$result['currentPage'],
    'totalPages'=>$result['totalPages']
]);
exit;

?>
