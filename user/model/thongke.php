<?php
    function getStatistics($start_date, $end_date) {
        $conn = connectdb();
        if (!$conn) {
            return ['status' => 'error', 'message' => 'Lỗi kết nối database'];
        }

        try {
            // Thực hiện truy vấn để lấy thống kê tổng số đơn hàng và tổng tiền mua
            $sql = "SELECT khachhang.ten, COUNT(hoadon.idhoadon) AS so_don, SUM(hoadon.tongtien) AS tong_mua
                    FROM hoadon
                    JOIN khachhang ON hoadon.idkhachhang = khachhang.idkhachhang
                    WHERE hoadon.ngayxuat BETWEEN :start_date AND :end_date
                    GROUP BY khachhang.idkhachhang, khachhang.ten
                    ORDER BY tong_mua DESC";
            
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
            $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);

            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Trả về kết quả nếu thành công
            return ['status' => 'success', 'data' => $result];
        } catch (PDOException $e) {
            // Trả về thông báo lỗi nếu có lỗi
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
?>
