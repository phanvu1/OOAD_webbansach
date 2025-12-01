<?php
session_start();
header('Content-Type: application/json'); // Đảm bảo trả về JSON
include_once "../../model/connectDB.php";
include_once "../../model/taikhoanUser.php";
// Kiểm tra đăng nhập
if (!isset($_SESSION['idkhachhang'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để thực hiện thao tác này!']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ!']);
    exit();
}

$action = $_POST['action'] ?? '';
$idkhachhang = $_SESSION['idkhachhang'];

if ($action === 'save_personal_info') {
    $ten = htmlspecialchars(trim($_POST['full_name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $sodienthoai = htmlspecialchars(trim($_POST['phone'] ?? ''));

    // Kiểm tra dữ liệu đầu vào
    if (empty($ten) || empty($email) || empty($sodienthoai)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin!']);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email không hợp lệ!']);
        exit();
    }

    if (!preg_match('/^[0-9]{10}$/', $sodienthoai)) {
        echo json_encode(['success' => false, 'message' => 'Số điện thoại không hợp lệ!']);
        exit();
    }

    try {
        if (update_personal_info($idkhachhang, $ten, $email, $sodienthoai)) {
            $user = get_user_by_id($idkhachhang);
            if ($user === null) {
                throw new Exception('Không thể lấy thông tin người dùng sau khi cập nhật!');
            }
            echo json_encode(['success' => true, 'message' => 'Cập nhật thông tin thành công!', 'user' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cập nhật thông tin thất bại. Email có thể đã tồn tại!']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()]);
    }
    exit();
}

if ($action === 'change_password') {
    $current_password = htmlspecialchars($_POST['current_password'] ?? '');
    $new_password = htmlspecialchars($_POST['new_password'] ?? '');
    $confirm_password = htmlspecialchars($_POST['confirm_password'] ?? '');

    // Kiểm tra dữ liệu đầu vào
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin!']);
        exit();
    }

    if ($new_password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu mới và xác nhận mật khẩu không khớp!']);
        exit();
    }

    if (strlen($new_password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu mới phải có ít nhất 6 ký tự!']);
        exit();
    }

    try {
        if (!check_current_password($idkhachhang, $current_password)) {
            echo json_encode(['success' => false, 'message' => 'Mật khẩu hiện tại không đúng!']);
            exit();
        }

        if (update_password($idkhachhang, $new_password)) {
            echo json_encode(['success' => true, 'message' => 'Đổi mật khẩu thành công!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Đổi mật khẩu thất bại!']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()]);
    }
    exit();
}

echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ!']);
exit();
?>