<?php
require_once 'connectDB.php';

// Lấy thông tin người dùng theo idkhachhang
function get_user_by_id($idkhachhang) {
    $conn = connectdb();
    
    try {
        $stmt = $conn->prepare("
            SELECT k.idkhachhang, k.ten, k.email, k.sodienthoai, t.tendangnhap
            FROM khachhang k
            JOIN taikhoan_khachhang t ON k.idkhachhang = t.idkhachhang
            WHERE k.idkhachhang = ?
        ");
        $stmt->execute([$idkhachhang]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        $stmt = $conn->prepare("
            SELECT COALESCE(SUM(tongtien), 0) as tongchitieu
            FROM hoadon
            WHERE idkhachhang = ?
        ");
        $stmt->execute([$idkhachhang]);
        $tongchitieu = $stmt->fetch(PDO::FETCH_ASSOC)['tongchitieu'];

        $user['tongchitieu'] = $tongchitieu;
        return $user;
    } catch (PDOException $e) {
        return false;
    }
}

// Cập nhật thông tin cá nhân
function update_personal_info($idkhachhang, $ten, $email, $sodienthoai) {
    $conn = connectdb();
    
    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("SELECT idkhachhang FROM khachhang WHERE email = ? AND idkhachhang != ?");
        $stmt->execute([$email, $idkhachhang]);
        if ($stmt->rowCount() > 0) {
            $conn->rollBack();
            return false; 
        }

        $stmt = $conn->prepare("UPDATE khachhang SET ten = ?, email = ?, sodienthoai = ? WHERE idkhachhang = ?");
        $stmt->execute([$ten, $email, $sodienthoai, $idkhachhang]);

        $conn->commit();
        return true;
    } catch (PDOException $e) {
        $conn->rollBack();
        return false;
    }
}

// Kiểm tra mật khẩu hiện tại
function check_current_password($idkhachhang, $matkhau) {
    $conn = connectdb();
    
    try {
        $stmt = $conn->prepare("SELECT matkhau FROM taikhoan_khachhang WHERE idkhachhang = ?");
        $stmt->execute([$idkhachhang]);
        $current_password = $stmt->fetch(PDO::FETCH_ASSOC)['matkhau'];
        return $current_password === $matkhau; // So sánh plain text
    } catch (PDOException $e) {
        return false;
    }
}

// Cập nhật mật khẩu mới
function update_password($idkhachhang, $matkhau) {
    $conn = connectdb();
    
    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("UPDATE taikhoan_khachhang SET matkhau = ? WHERE idkhachhang = ?");
        $stmt->execute([$matkhau, $idkhachhang]);

        $conn->commit();
        return true;
    } catch (PDOException $e) {
        $conn->rollBack();
        return false;
    }
}
?>