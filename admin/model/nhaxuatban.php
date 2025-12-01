<?php
function getDataNhaXuatBan($page, $limit){
    $conn = connectdb();
    if($conn){
        //tính trang bắt đầu phân trang
        $offset = ($page -1)*$limit;
        //đếm 
        $sqlCount = "SELECT count(*) AS total FROM nhaxuatban";
        $sttm = $conn->prepare($sqlCount);
        $sttm->execute();
        $totalRecords = $sttm->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords/$limit);

        //load data
        $sql = "SELECT * FROM nhaxuatban LIMIT :limit OFFSET :offset";
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

function delDataNhaXuatBanById($idnhaxuatban){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "UPDATE nhaxuatban SET trangthai = 0 WHERE idnhaxuatban = :idnhaxuatban";
            $sttm = $conn ->prepare($sql);
            $sttm->bindValue(":idnhaxuatban", $idnhaxuatban, PDO::PARAM_INT);
            $sttm->execute();
            $result = $sttm->rowCount();
            if($result >0){
                return true;
            }

        }catch(PDOException $e){
            error_log("Lỗi khi cập nhật trạng thái nhà xuất bản!".$e->getMessage());
        }
    }
    return false;
}

function getComboboxNXB() {
    $conn = connectdb();
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT idnhaxuatban, tennxb FROM nhaxuatban WHERE trangthai = 1");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            error_log("Lỗi khi lấy danh sách nhà xuất bản: " . $e->getMessage());
        }
    }
    return false;
}

function themNXB($tenNXB, $email, $sodienthoai, $diachi) {
    $conn = connectdb(); // Kết nối cơ sở dữ liệu
    if ($conn) {
        try {
            // Chuẩn bị câu lệnh SQL để thêm dữ liệu
            $stmt = $conn->prepare("
                INSERT INTO nhaxuatban (tennxb, email, sodienthoai, diachi, trangthai) 
                VALUES (:tenNXB, :email, :sodienthoai, :diachi, 1)
            ");

            // Gán giá trị cho các tham số
            $stmt->bindParam(':tenNXB', $tenNXB, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':sodienthoai', $sodienthoai, PDO::PARAM_STR);
            $stmt->bindParam(':diachi', $diachi, PDO::PARAM_STR);

            // Thực thi truy vấn
            if ($stmt->execute()) {
                return true; // Thêm thành công
            }
        } catch (PDOException $e) {
            // Ghi log lỗi (nếu xảy ra)
            error_log("Lỗi khi thêm nhà xuất bản: " . $e->getMessage());
        }
    }
    return false; // Thêm thất bại
}

function getTenNhaXuatBanTheoId($idnhaxuatban){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "SELECT tennxb FROM nhaxuatban WHERE idnhaxuatban = :idnhaxuatban";
            $sttm = $conn->prepare($sql);
            $sttm->bindValue('idnhaxuatban', $idnhaxuatban, PDO::PARAM_INT);
            $sttm->execute();
            $tennxb = $sttm->fetch(PDO::FETCH_ASSOC)['tennxb'];
            return $tennxb;
            
        }catch(PDOException $e){
            error_log(("Lỗi khi gọi tên tác giả theo id ".$e->getMessage()));
        }
    }
    return null;
}

function getDataNhaXuaBanTheoId($idnhaxuatban){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "SELECT * from nhaxuatban WHERE idnhaxuatban = :idnhaxuatban ";
            $sttm =$conn->prepare($sql);
            $sttm->bindValue(':idnhaxuatban', $idnhaxuatban, PDO::PARAM_INT);
            $sttm->execute()>0;
            $success = $sttm->rowCount()>0;
            if($success){
                return $sttm->fetch(PDO::FETCH_ASSOC);
            }else{
                return null;
            }

        }catch(PDOException $e){
            error_log("Không tìm thấy thông tin cụ thể của nhà xuất bản!", $e->getMessage());
        }
    }
    return null;
}

function updateNhaXuatBanById($idnhaxuatban, $trangthai){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "UPDATE nhaxuatban SET trangthai= :trangthai WHERE idnhaxuatban=:idnhaxuatban";
            $sttm =$conn->prepare($sql);
            $sttm->bindValue(':trangthai', $trangthai, PDO::PARAM_INT);
            $sttm->bindValue(':idnhaxuatban', $idnhaxuatban, PDO::PARAM_INT);
            if ($sttm->execute()) {
                return true; 
            }

        }catch(PDOException $e){
            error_log("Lỗi khi cập nhật nhà xuất bản: ".$e->getMessage());
            return false;
        }
    }
    return false;
}

function updateNhaXuatBanFull($idnhaxuatban, $tennxb, $email, $sodienthoai, $diachi, $trangthai){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "UPDATE nhaxuatban 
                    SET tennxb = :tennxb, email = :email, sodienthoai = :sodienthoai, diachi = :diachi, trangthai = :trangthai 
                    WHERE idnhaxuatban = :idnhaxuatban";
            $sttm = $conn->prepare($sql);
            $sttm->bindValue(':tennxb', $tennxb, PDO::PARAM_STR);
            $sttm->bindValue(':email', $email, PDO::PARAM_STR);
            $sttm->bindValue(':sodienthoai', $sodienthoai, PDO::PARAM_STR);
            $sttm->bindValue(':diachi', $diachi, PDO::PARAM_STR);
            $sttm->bindValue(':trangthai', $trangthai, PDO::PARAM_INT);
            $sttm->bindValue(':idnhaxuatban', $idnhaxuatban, PDO::PARAM_INT);
            if ($sttm->execute()) {
                return true;
            }
        }catch(PDOException $e){
            error_log("Lỗi khi cập nhật đầy đủ NXB: ".$e->getMessage());
            return false;
        }
    }
    return false;
}

function checkTrungTenAndEmailAndSDTAndDiaChi($tenNXB, $emailNXB, $SDTNXB) {
    $conn = connectdb(); // Kết nối CSDL
    
    // Kiểm tra tên nhà xuất bản
    $sqlTen = "SELECT COUNT(*) FROM nhaxuatban WHERE tennxb = :tenNXB";
    $stmtTen = $conn->prepare($sqlTen);
    $stmtTen->bindParam(':tenNXB', $tenNXB, PDO::PARAM_STR);
    $stmtTen->execute();
    $existsTen = $stmtTen->fetchColumn() > 0; // true nếu tên nhà xuất bản đã tồn tại
    
    // Kiểm tra email nhà xuất bản
    $sqlEmail = "SELECT COUNT(*) FROM nhaxuatban WHERE email = :emailNXB";
    $stmtEmail = $conn->prepare($sqlEmail);
    $stmtEmail->bindParam(':emailNXB', $emailNXB, PDO::PARAM_STR);
    $stmtEmail->execute();
    $existsEmail = $stmtEmail->fetchColumn() > 0; // true nếu email đã tồn tại
    
    // Kiểm tra số điện thoại
    $sqlSDT = "SELECT COUNT(*) FROM nhaxuatban WHERE sodienthoai = :SDTNXB";
    $stmtSDT = $conn->prepare($sqlSDT);
    $stmtSDT->bindParam(':SDTNXB', $SDTNXB, PDO::PARAM_STR);
    $stmtSDT->execute();
    $existsSDT = $stmtSDT->fetchColumn() > 0; // true nếu số điện thoại đã tồn tại

    // Trả về kết quả kiểm tra cho từng trường
    return [
        'tenNXB' => $existsTen,
        'emailNXB' => $existsEmail,
        'SDTNXB' => $existsSDT
    ];
}

function searchNXB($keyword, $page, $limit) {
    $offset = ($page - 1) * $limit;
    $conn = connectdb();
    $keyword = "%" . $keyword . "%";

    // Đếm tổng kết quả khớp
    $countSql = "SELECT COUNT(*) FROM nhaxuatban WHERE tennxb LIKE :keyword OR idnhaxuatban LIKE :keyword
                OR email LIKE :keyword OR sodienthoai LIKE :keyword";
    $stmt = $conn->prepare($countSql);
    $stmt->bindValue(':keyword', $keyword);
    $stmt->execute();
    $total = $stmt->fetchColumn();

    $totalPages = ceil($total / $limit);

    $sql = "SELECT * FROM nhaxuatban WHERE tennxb LIKE :keyword OR idnhaxuatban LIKE :keyword 
                OR email LIKE :keyword OR sodienthoai LIKE :keyword LIMIT :limit OFFSET :offset";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':keyword', $keyword);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'data' => $data,
        'totalPages' => $totalPages,
        'currentPage' => $page 
    ];
}
?>
