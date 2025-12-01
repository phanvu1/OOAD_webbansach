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
    function addChiTietHoaDon($idhoadon, $idsach, $soluong, $gia) {
        try {
            $conn = connectdb();
            $sql = "INSERT INTO chitiethoadon (idhoadon, idsach, soluong, gia) VALUES (:idhoadon, :idsach, :soluong, :gia)";
            $sttm = $conn->prepare($sql);
            $sttm->bindValue(':idhoadon', $idhoadon, PDO::PARAM_INT);
            $sttm->bindValue(':idsach', $idsach, PDO::PARAM_INT);
            $sttm->bindValue(':soluong', $soluong, PDO::PARAM_INT);
            $sttm->bindValue(':gia', $gia, PDO::PARAM_STR); // Sửa thành :gia
            $result = $sttm->execute();
            return $result;
        } catch (\Throwable $th) {
            error_log("Lỗi khi thêm chi tiết hóa đơn: " . $th->getMessage());
            throw $th;
        }
        
    }
?>