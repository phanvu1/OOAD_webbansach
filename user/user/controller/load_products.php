<?php
// user/controller/load_products.php
// Tắt hiển thị lỗi để tránh làm hỏng JSON
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// Đặt tiêu đề phản hồi là JSON
header('Content-Type: application/json');

// Định nghĩa đường dẫn gốc
if (!defined('APP_PATH')) {
    define('APP_PATH', __DIR__ . '/../../'); // Đi lên 2 cấp để đến thư mục gốc (project/)
}

if (!defined('BASE_URL')) {
    define('BASE_URL', '/'); // Điều chỉnh nếu dự án nằm trong thư mục con
}

// Kết nối cơ sở dữ liệu
require_once __DIR__ . '/../../model/connectdb.php'; // Đi lên 2 cấp để đến model/

// Lấy tham số từ yêu cầu AJAX
$categoryId = isset($_POST['iddanhmuc']) ? (int)$_POST['iddanhmuc'] : 7;
$subCategoryId = isset($_POST['idchitietdanhmuc']) ? (int)$_POST['idchitietdanhmuc'] : 0;
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

try {
    // Kết nối cơ sở dữ liệu
    $conn = connectdb();

    // Truy vấn danh mục con
    $query = "SELECT * FROM chitietdanhmuc WHERE iddanhmuc = :iddanhmuc";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':iddanhmuc', $categoryId, PDO::PARAM_INT);
    $stmt->execute();
    $subCategories = $stmt->fetchAll(PDO::FETCH_OBJ);

    // Truy vấn tổng số sách
    $condition = "s.idctdanhmuc IN (SELECT idchitietdanhmuc FROM chitietdanhmuc WHERE iddanhmuc = :iddanhmuc) AND s.trangthai = 1";
    $params = [':iddanhmuc' => $categoryId];
    if ($subCategoryId > 0) {
        $condition = "s.idctdanhmuc = :idchitietdanhmuc AND s.trangthai = 1";
        $params = [':idchitietdanhmuc' => $subCategoryId];
    }

    $query = "SELECT COUNT(*) as total FROM sach s WHERE $condition";
    $stmt = $conn->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindParam($key, $value, PDO::PARAM_INT);
    }
    $stmt->execute();
    $totalBooks = $stmt->fetch(PDO::FETCH_OBJ)->total;
    $totalPages = ceil($totalBooks / $limit);

    // Truy vấn danh sách sách
    $query = "SELECT s.*, tg.tentacgia 
              FROM sach s 
              LEFT JOIN tacgia tg ON s.idtacgia = tg.idtacgia 
              WHERE $condition 
              LIMIT :offset, :limit";
    $stmt = $conn->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindParam($key, $value, PDO::PARAM_INT);
    }
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_OBJ);

    // Lấy tên danh mục
    $query = "SELECT tendanhmuc FROM danhmuc WHERE iddanhmuc = :iddanhmuc";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':iddanhmuc', $categoryId, PDO::PARAM_INT);
    $stmt->execute();
    $categoryName = $stmt->fetch(PDO::FETCH_OBJ)->tendanhmuc;

    // Lấy tên danh mục con (nếu có)
    $subCategoryName = 'Tất cả';
    if ($subCategoryId > 0) {
        $query = "SELECT tenchitietdanhmuc FROM chitietdanhmuc WHERE idchitietdanhmuc = :idchitietdanhmuc";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':idchitietdanhmuc', $subCategoryId, PDO::PARAM_INT);
        $stmt->execute();
        $subCategoryName = $stmt->fetch(PDO::FETCH_OBJ)->tenchitietdanhmuc;
    }

    // Trả về dữ liệu dạng JSON
    $response = [
        'status' => 'success',
        'categoryName' => htmlspecialchars($categoryName),
        'subCategoryName' => htmlspecialchars($subCategoryName),
        'subCategories' => $subCategories,
        'books' => $books,
        'totalPages' => $totalPages,
        'currentPage' => $page,
        'subCategoryId' => $subCategoryId
    ];
} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'message' => 'Lỗi: ' . $e->getMessage()
    ];
}

// Đảm bảo không có đầu ra nào khác trước khi trả về JSON
ob_clean();
echo json_encode($response);
exit;
?>