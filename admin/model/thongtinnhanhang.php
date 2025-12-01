<?php
    function getDataThongTinNhanHangByID($iddiachi){
        $conn = connectdb();
        if($conn){
            try{
                $sql = "SELECT * from thongtinnhanhang WHERE iddiachi = :iddiachi ";
                $sttm =$conn->prepare($sql);
                $sttm->bindValue(':iddiachi', $iddiachi, PDO::PARAM_INT);
                $sttm->execute()>0;
                $success = $sttm->rowCount()>0;
                if($success){
                    return $sttm->fetch(PDO::FETCH_ASSOC);
                }else{
                    return null;
                }
    
            }catch(PDOException $e){
                error_log("Không tìm thấy thông tin cụ thể của thông tin nhận hàng!", $e->getMessage());
            }
        }
        return null;
    }
?>