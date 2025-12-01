<?php
    function getCTHoaDonByIDHoaDon($idhoadon){
        $conn = connectdb();
        if($conn){
            try {
                $sql = "SELECT cthd.idhoadon, s.tensach, s.idtacgia, s.idnhaxuatban, s.idctdanhmuc,
                    cthd.idsach, cthd.soluong, cthd.gia, (cthd.soluong * cthd.gia) AS thanhtien
                FROM chitiethoadon cthd
                JOIN sach s ON cthd.idsach = s.idsach
                WHERE cthd.idhoadon = :idhoadon";
                        
                $sttm = $conn->prepare($sql);
                $sttm->bindValue(':idhoadon', $idhoadon, PDO::PARAM_INT);
                $sttm->execute();
    
                $result = $sttm->fetchAll(PDO::FETCH_ASSOC);
                return $result;
    
            } catch(PDOException $e) {
                error_log("Lỗi khi lấy chi tiết hóa đơn: " . $e->getMessage());
            }
        }
        return [];
    }    
?>