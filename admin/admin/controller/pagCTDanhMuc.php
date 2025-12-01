<?php
require_once '../../model/connectDB.php';
require_once '../../model/ctdanhmuc.php';
require_once '../../model/danhmuc.php';

header('Content-Type: application/json');

// Lấy tham số từ URL
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 7;
$iddanhmuc = isset($_GET['iddanhmuc']) ? intval($_GET['iddanhmuc']) : null;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Kiểm tra và lấy dữ liệu nếu có idDanhMuc
if ($iddanhmuc) {
    try {
        $tenDanhMuc = getTenDanhMuc($iddanhmuc);

        if ($search !== '') {
            $result = searchCTDM($iddanhmuc, $search, $page, $limit);
        } else {
            $result = getDataCTDanhMuc($iddanhmuc, $page, $limit);
        }

        if ($result && isset($result['data']) && count($result['data']) > 0) {
            echo json_encode([
                'tenDanhMuc' => $tenDanhMuc,
                'data' => $result['data'],
                'totalPages' => $result['totalPages'],
                'currentPage' => $result['currentPage']
            ]);
        } else {
            echo json_encode([
                'tenDanhMuc' => $tenDanhMuc,
                'data' => [],
                'totalPages' => 0,
                'currentPage' => $page
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'error' => 'Lỗi khi lấy dữ liệu: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'error' => 'Danh mục không hợp lệ'
    ]);
}
exit;
