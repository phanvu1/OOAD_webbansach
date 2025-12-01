<?php
//trả về chuỗi json 
require_once '../../model/connectDB.php';
require_once '../../model/sanpham.php';

header('Content-type: application/json');
$result1 = getDataSanPhamPhieuNhap();
 
echo json_encode([
    'data'=>$result1['data']
]);
exit;
