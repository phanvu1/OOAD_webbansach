<?php 
    function getCuaHang() {
        $conn = connectdb(); // Kết nối cơ sở dữ liệu
        if ($conn) {
            try {
                // Truy vấn lấy tất cả banner
                $stmt = $conn->prepare("
                    SELECT * FROM thongtincuahang
                ");
                $stmt->execute();

                // Trả về kết quả dưới dạng mảng
                return $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                // Ghi log lỗi nếu xảy ra lỗi
                error_log("Lỗi khi lấy danh sách thông tin cửa hàng: " . $e->getMessage());
            }
        }

        // Trả về mảng rỗng nếu kết nối thất bại
        return [];
    }

    function getBanner() {
        $conn = connectdb(); // Kết nối cơ sở dữ liệu
        if ($conn) {
            try {
                // Truy vấn lấy tất cả banner
                $stmt = $conn->prepare("
                    SELECT * FROM banner
                ");
                $stmt->execute();

                // Trả về kết quả dưới dạng mảng
                return $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                // Ghi log lỗi nếu xảy ra lỗi
                error_log("Lỗi khi lấy danh sách banner: " . $e->getMessage());
            }
        }

        // Trả về mảng rỗng nếu kết nối thất bại
        return [];
    }

    function getCuaHangTheoId($id) {
        $conn = connectdb();
        if ($conn) {
            try {
                $stmt = $conn->prepare("SELECT * FROM thongtincuahang WHERE idthongtin = ?");
                $stmt->execute([$id]);
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Lỗi khi lấy thông tin cửa hàng theo ID: " . $e->getMessage());
            }
        }
        return null;
    }    

    function updateCuaHang($id, $diachi, $sodienthoai, $email, $facebook, $tiktok) {
        $conn = connectdb(); // Kết nối cơ sở dữ liệu
        if ($conn) {
            try {
                $stmt = $conn->prepare("
                    UPDATE thongtincuahang 
                    SET diachi = ?, sodienthoai = ?, email = ?, facebook = ?, tiktok = ?
                    WHERE idthongtin = ?
                ");
                return $stmt->execute([$diachi, $sodienthoai, $email, $facebook, $tiktok, $id]);
            } catch (PDOException $e) {
                error_log("Lỗi khi cập nhật thông tin cửa hàng: " . $e->getMessage());
            }
        }
        return false; // Trả về false nếu cập nhật thất bại
    }    

    function updateChuyenKhoan($id, $tenNH, $stk, $tenChuTK, $anhQrCk) {
        $conn = connectdb();
        if ($conn) {
            try {
                $stmt = $conn->prepare("
                    UPDATE thongtincuahang
                    SET tenNH = ?, stk = ?, tenChuTK = ?, anhQrCk = ?
                    WHERE idthongtin = ?
                ");
                return $stmt->execute([$tenNH, $stk, $tenChuTK, $anhQrCk, $id]);
            } catch (PDOException $e) {
                error_log("Lỗi khi cập nhật chuyển khoản: " . $e->getMessage());
            }
        }
        return false;
    }    
?>
