<?php
require_once '../../model/connectDB.php';
require_once '../../model/thongke.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['start-date']) && isset($_POST['end-date'])) {
        $start_date = $_POST['start-date'];
        $end_date = $_POST['end-date'];
        $top_kh = isset($_POST['top-kh']) && is_numeric($_POST['top-kh']) ? (int)$_POST['top-kh'] : null;
        $sort_order = isset($_POST['sort-order']) && in_array($_POST['sort-order'], ['ASC', 'DESC']) 
                      ? $_POST['sort-order'] : 'DESC';

        if (empty($start_date) || empty($end_date)) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng chọn khoảng thời gian']);
            exit;
        }

        $result = getStatistics($start_date, $end_date, $top_kh, $sort_order);

        if ($result['status'] == 'success') {
            echo json_encode([
                'status' => 'success',
                'data' => $result['data']
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => $result['message']
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Thiếu tham số start-date hoặc end-date'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Phương thức yêu cầu không hợp lệ'
    ]);
}
?>
