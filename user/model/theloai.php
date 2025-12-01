<?php
function getDataTheLoai($page, $limit){
    $conn = connectdb();
    if($conn){
        //tính trang bắt đầu phân trang
        $offset = ($page -1)*$limit;
        //đếm 
        $sqlCount = "SELECT count(*) AS total FROM theloai";
        $sttm = $conn->prepare($sqlCount);
        $sttm->execute();
        $totalRecords = $sttm->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords/$limit);

        //load data
        $sql = "SELECT * FROM theloai LIMIT :limit OFFSET :offset";
        $sttm = $conn->prepare($sql);
        $sttm->bindValue(':limit', $limit, PDO::PARAM_INT);
        $sttm->bindValue(':offset', $offset, PDO::PARAM_INT);
        $sttm-> execute();
        $data= $sttm->fetchAll(PDO::FETCH_ASSOC);

        return[
            'data'=>$data,
            'totalPages'=>$totalPages,
            'currentPage'=>$page

        ];

    }
    return[
        'data'=>[],
        'totalPages'=>[],
        'currentPage'=>1

    ];

}

function delDataTheoLoaiById($idtheloai){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "UPDATE theloai SET trangthai = 0 WHERE idtheloai = :idtheloai";
            $sttm = $conn ->prepare($sql);
            $sttm->bindValue(":idtheloai", $idtheloai, PDO::PARAM_INT);
            $sttm->execute();
            $result = $sttm->rowCount();
            if($result >0){
                return true;
            }

        }catch(PDOException $e){
            error_log("Lỗi khi cập nhật trạng thái thể loại!".$e->getMessage());
        }
    }
    return false;
}

function getComboboxTheLoai() {
    $conn = connectdb();
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT idtheloai, tentheloai FROM theloai");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            error_log("Lỗi khi lấy danh sách thể loại: " . $e->getMessage());
        }
    }
    return false;
}

function themTheLoai($tenTheLoai) {
    $conn = connectdb(); // Kết nối cơ sở dữ liệu
    if ($conn) {
        try {
            // Chuẩn bị câu lệnh SQL để thêm dữ liệu
            $stmt = $conn->prepare("
                INSERT INTO theloai (tentheloai, trangthai) 
                VALUES (:tenTheLoai, 1)
            ");

            // Gán giá trị cho các tham số
            $stmt->bindParam(':tenTheLoai', $tenTheLoai, PDO::PARAM_STR);

            // Thực thi truy vấn
            if ($stmt->execute()) {
                return true; // Thêm thành công
            }
        } catch (PDOException $e) {
            // Ghi log lỗi (nếu xảy ra)
            error_log("Lỗi khi thêm thể loại: " . $e->getMessage());
        }
    }
    return false; // Thêm thất bại
}


function getTenTheLoaiTheoId($idtheloai){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "SELECT tentheloai FROM theloai WHERE idtheloai = :idtheloai";
            $sttm = $conn->prepare($sql);
            $sttm->bindValue('idtheloai', $idtheloai, PDO::PARAM_INT);
            $sttm->execute();
            $temtheloai = $sttm->fetch(PDO::FETCH_ASSOC);
            return $temtheloai;
            
        }catch(PDOException $e){
            error_log(("Lỗi khi gọi tên thể loại theo id ".$e->getMessage()));
        }
    }
    return null;
}

function checkTrungTenTheLoai($tenTheLoai) {
    $conn = connectdb(); // Kết nối CSDL
    $sql = "SELECT COUNT(*) FROM theloai WHERE tentheloai = :tenTheLoai AND trangthai = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':tenTheLoai', $tenTheLoai, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn() > 0; // true nếu đã tồn tại
}

function getDataTheLoaiTheoId($idtheloai){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "SELECT * from theloai WHERE idtheloai = :idtheloai ";
            $sttm =$conn->prepare($sql);
            $sttm->bindValue(':idtheloai', $idtheloai, PDO::PARAM_INT);
            $sttm->execute()>0;
            $success = $sttm->rowCount()>0;
            if($success){
                return $sttm->fetch(PDO::FETCH_ASSOC);
            }else{
                return null;
            }

        }catch(PDOException $e){
            error_log("Không tìm thấy thông tin cụ thể của thể loại!", $e->getMessage());
        }
    }
    return null;
}

function updateTheLoaiById($idtheloai, $trangthai){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "UPDATE theloai SET trangthai= :trangthai WHERE idtheloai=:idtheloai";
            $sttm =$conn->prepare($sql);
            $sttm->bindValue(':trangthai', $trangthai, PDO::PARAM_INT);
            $sttm->bindValue(':idtheloai', $idtheloai, PDO::PARAM_INT);
            if ($sttm->execute()) {
                return true; 
            }

        }catch(PDOException $e){
            error_log("Lỗi khi cập nhật thể loại: ".$e->getMessage());
            return false;
        }
    }
    return false;
}
?>
