<?php
    function getStatistics($start_date, $end_date, $top_kh = null, $sort_order = 'DESC') {
        $conn = connectdb();
        if (!$conn) {
            return ['status' => 'error', 'message' => 'Lỗi kết nối database'];
        }
    
        try {
            // Kiểm tra sort_order hợp lệ
            $order = strtoupper($sort_order) === 'ASC' ? 'ASC' : 'DESC';
    
            $sql = "SELECT khachhang.ten, 
                        GROUP_CONCAT(hoadon.idhoadon ORDER BY hoadon.idhoadon) AS ds_don_hang, 
                        SUM(hoadon.tongtien) AS tong_mua,
                        hoadon.idkhachhang
                    FROM hoadon
                    JOIN khachhang ON hoadon.idkhachhang = khachhang.idkhachhang
                    WHERE hoadon.ngayxuat BETWEEN :start_date AND :end_date
                    AND hoadon.trangthai IN (1, 2)  
                    GROUP BY hoadon.idkhachhang, khachhang.ten
                    ORDER BY tong_mua $order";
    
            if ($top_kh !== null) {
                $sql .= " LIMIT " . intval($top_kh); // dùng intval để tránh bindParam lỗi
            }
    
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
            $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            $dsKhachHang = [];
    
            foreach ($result as $row) {
                $ma_kh = $row['idkhachhang'];
                $ten_kh = $row['ten'];
                $ds_don_hang = $row['ds_don_hang'];
                $tong_mua = $row['tong_mua'];
    
                if (!isset($dsKhachHang[$ma_kh])) {
                    $dsKhachHang[$ma_kh] = [
                        'ten' => $ten_kh,
                        'tong_mua' => 0,
                        'ds_don_hang' => []
                    ];
                }
    
                $dsKhachHang[$ma_kh]['tong_mua'] += $tong_mua;
                $dsKhachHang[$ma_kh]['ds_don_hang'] = explode(",", $ds_don_hang);
            }
    
            return ['status' => 'success', 'data' => array_values($dsKhachHang)];
    
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }    
?>
