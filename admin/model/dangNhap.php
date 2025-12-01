<?php
    function checkuser($user, $pass) {
        $conn = connectdb();
        if (!$conn) {
            return null;
        }
    
        $sttm = $conn->prepare("
            SELECT *
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
?>