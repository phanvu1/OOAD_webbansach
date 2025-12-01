<?php
header('Content-Type: application/json');
require_once '../../model/connectDB.php';
require_once '../../model/SearchModel.php';

$searchModel = new SearchModel();

$keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
$category = isset($_POST['category']) && $_POST['category'] !== 'all' ? (int)$_POST['category'] : null;
$min_price = isset($_POST['min_price']) && is_numeric($_POST['min_price']) ? (float)$_POST['min_price'] : null;
$max_price = isset($_POST['max_price']) && is_numeric($_POST['max_price']) ? (float)$_POST['max_price'] : null;
$page = isset($_POST['page']) && is_numeric($_POST['page']) ? max(1, (int)$_POST['page']) : 1;

$items_per_page = 12; // Số sách mỗi trang
$offset = ($page - 1) * $items_per_page;

// Lấy sách cho trang hiện tại
$result = $searchModel->searchBooks($keyword, $category, $min_price, $max_price, $items_per_page, $offset);

// Thêm thông tin phân trang
$result['current_page'] = $page;
$result['total_pages'] = ceil($result['total_results'] / $items_per_page);

echo json_encode($result);
?>