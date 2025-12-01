<?php
session_start();
require_once "../../model/connectDB.php";
require_once "../../model/register.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $sdt = trim($_POST['sdt'] ?? '');
    $tendangnhap = trim($_POST['username'] ?? '');
    $matkhau = $_POST['password'] ?? '';

    if (empty($ten) || empty($email) || empty($sdt) || empty($tendangnhap) || empty($matkhau)) {
        echo json_encode([
            'success' => false,
            'message' => 'Vui lòng điền đầy đủ thông tin!'
        ]);
        exit;
    }
    if (!preg_match('/^[a-z0-9]{1,20}$/', $tendangnhap)) {
        echo json_encode([
            'success' => false,
            'message' => 'Tên đăng nhập không hợp lệ! Chỉ dùng chữ cái thường (a-z), số (0-9), không dấu, không khoảng trắng, tối đa 20 ký tự.'
        ]);
        exit;
    }
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@(sgu\.edu\.vn|gmail\.com)$/', $email)) {
        echo json_encode([
            'success' => false,
            'message' => 'Email không hợp lệ!'
        ]);
        exit;
    }

    if (!preg_match('/^[0-9]{10}$/', $sdt)) {
        echo json_encode([
            'success' => false,
            'message' => 'Số điện thoại không hợp lệ!'
        ]);
        exit;
    }

    $result = register($ten, $email, $sdt, $tendangnhap, $matkhau);

    if ($result === 'email_exists') {
        echo json_encode([
            'success' => false,
            'message' => 'Email đã được đăng ký!'
        ]);
    } elseif ($result === 'username_exists') {
        echo json_encode([
            'success' => false,
            'message' => 'Tên đăng nhập đã tồn tại!'
        ]);
    } elseif ($result === 'phone_exists') {
        echo json_encode([
            'success' => false,
            'message' => 'Số điện thoại đã được đăng ký!'
        ]);
    } elseif ($result) {
        // Lưu thông tin vào session
        $_SESSION['username'] = $tendangnhap;
        $_SESSION['idkhachhang'] = $result;

        echo json_encode([
            'success' => true,
            'message' => 'Đăng ký thành công!',
            'idkhachhang' => $result,
            'username' => $tendangnhap
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Đã xảy ra lỗi trong quá trình đăng ký!'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Phương thức không hợp lệ!'
    ]);
}
?>
