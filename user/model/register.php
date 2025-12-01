<?php
function register($ten, $email, $sdt, $tendangnhap, $matkhau) {
    $conn = connectdb();
    
    try {
        // Bắt đầu transaction
        $conn->beginTransaction();

        // Kiểm tra email hoặc tên đăng nhập đã tồn tại
        $stmt = $conn->prepare("SELECT idkhachhang FROM khachhang WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $conn->rollBack();
            return 'email_exists'; 
        }

        $stmt = $conn->prepare("SELECT idkhachhang FROM taikhoan_khachhang WHERE tendangnhap = ?");
        $stmt->execute([$tendangnhap]);
        if ($stmt->rowCount() > 0) {
            $conn->rollBack();
            return 'username_exists'; 
        }

        $stmt = $conn->prepare("SELECT idkhachhang FROM khachhang WHERE sodienthoai = ?");
        $stmt->execute([$sdt]);
        if ($stmt->rowCount() > 0) {
            $conn->rollBack();
            return 'phone_exists'; 
        }

        // Thêm vào bảng khachhang
        $stmt = $conn->prepare("INSERT INTO khachhang (ten, email, sodienthoai) VALUES (?, ?, ?)");
        $stmt->execute([$ten, $email, $sdt]);
        
        // Lấy idkhachhang vừa tạo
        $idkhachhang = $conn->lastInsertId();

        // // Mã hóa mật khẩu
        // $hashed_matkhau = password_hash($matkhau, PASSWORD_DEFAULT);

        // Thêm vào bảng taikhoan_khachhang
        $stmt = $conn->prepare("INSERT INTO taikhoan_khachhang (idkhachhang, tendangnhap, matkhau) VALUES (?, ?, ?)");
        $stmt->execute([$idkhachhang, $tendangnhap, $matkhau]);

        $conn->commit();
        return $idkhachhang;

    } catch(PDOException $e) {
        $conn->rollBack();
        return false; 
    }
}

?>
