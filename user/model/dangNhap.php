<?php
    function checkuser($user, $pass) {
        $conn = connectdb();
        if (!$conn) {
            return null;
        }
    
        $sttm = $conn->prepare("
            SELECT idnhanvien, TaiKhoan, MatKhau, TrangThai 
            FROM taikhoan_nhanvien 
            WHERE TaiKhoan = :user AND MatKhau = :pass
        ");
        $sttm->bindParam(':user', $user);
        $sttm->bindParam(':pass', $pass);
        $sttm->execute();
    
        if ($sttm->rowCount() > 0) {
            return $sttm->fetch(PDO::FETCH_ASSOC); // Trả về toàn bộ thông tin tài khoản
        }
    
        return null;
    }

    function checkUsernameExist($username) {
        $conn = connectdb();
        $stmt = $conn->prepare("SELECT * FROM taikhoan_nhanvien WHERE TaiKhoan = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về thông tin nếu tồn tại
    }
    function checktaikhoanKH($userKH, $passKH) {
        $conn = connectdb();
        if (!$conn) {
            return null;
        }
    
        // Truy vấn để kiểm tra trạng thái tài khoản
        $sttm = $conn->prepare("
            SELECT tk.tendangnhap, tk.matkhau, tk.idkhachhang, tk.trangthai
            FROM taikhoan_khachhang tk
            WHERE tk.tendangnhap = :username
        ");
    
        // Bind các tham số
        $sttm->bindParam(':username', $userKH);
    
        // Thực thi truy vấn
        $sttm->execute();
    
        if ($sttm->rowCount() > 0) {
            $row = $sttm->fetch(PDO::FETCH_ASSOC);
    
            // Kiểm tra trạng thái tài khoản
            if ($row['trangthai'] != 1) {
                return "Tài khoản của bạn đã bị khóa!";
            }
    
            // Kiểm tra mật khẩu nếu trạng thái là 1
            if ($row['matkhau'] === $passKH) {
                // Tạo mảng thông tin người dùng, thêm idtaikhoan
                $user_info = array(
                    'idkhachhang'  => $row['idkhachhang'], // Thêm idkhachhang
                    'tendangnhap'  => $row['tendangnhap'],
                    'matkhau'      => $row['matkhau'],
                );
                return $user_info;
            } else {
                return "Mật khẩu không đúng!";
            }
        }
    
        return "Tài khoản không tồn tại!";
    }
?>
