<?php
function getDataKhachHang($page, $limit){
    $conn = connectdb();
    if($conn){
        //tính trang bắt đầu phân trang
        $offset = ($page -1)*$limit;
        //đếm số sản phẩm 
        $sqlCount = "SELECT count(*) AS total FROM khachhang";
        $sttm = $conn->prepare($sqlCount);
        $sttm->execute();
        $totalRecords = $sttm->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords/$limit);

        //load data
        $sql = "SELECT * FROM khachhang LIMIT :limit OFFSET :offset";
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

function delDataKhachHangById($idkhachhang){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "UPDATE khachhang SET trangthai = 0 WHERE idkhachhang = :idkhachhang";
            $sttm = $conn ->prepare($sql);
            $sttm->bindValue(":idkhachhang", $idkhachhang, PDO::PARAM_INT);
            $sttm->execute();
            $result = $sttm->rowCount();
            if($result >0){
                return true;
            }

        }catch(PDOException $e){
            error_log("Lỗi khi cập nhật trạng thái khách hàng!".$e->getMessage());
        }
    }
    return false;
}

function themKhachHang($tenkh, $emailkh, $sodienthoaikh) {
    $conn = connectdb(); // Kết nối cơ sở dữ liệu
    if ($conn) {
        try {
            $stmt = $conn->prepare("
                INSERT INTO khachhang (ten, email, sodienthoai, trangthai) 
                VALUES (:ten, :email, :sodienthoai, 1)
            ");

            // Gán giá trị cho các tham số
            $stmt->bindParam(':ten', $tenkh, PDO::PARAM_STR);
            $stmt->bindParam(':email', $emailkh, PDO::PARAM_STR);
            $stmt->bindParam(':sodienthoai', $sodienthoaikh, PDO::PARAM_STR);

            // Thực thi truy vấn
            if ($stmt->execute()) {
                return true; // Thêm thành công
            }
        } catch (PDOException $e) {
            // Log lỗi (nếu cần)
            error_log("Lỗi khi thêm khách hàng: " . $e->getMessage());
        }
    }
    return false; // Thêm thất bại
}

function getDataKhachHangTheoId($idkhachhang){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "SELECT * from khachhang WHERE idkhachhang = :idkhachhang ";
            $sttm =$conn->prepare($sql);
            $sttm->bindValue(':idkhachhang', $idkhachhang, PDO::PARAM_INT);
            $sttm->execute()>0;
            $success = $sttm->rowCount()>0;
            if($success){
                return $sttm->fetch(PDO::FETCH_ASSOC);
            }else{
                return null;
            }

        }catch(PDOException $e){
            error_log("Không tìm thấy thông tin cụ thể của khách hàng!", $e->getMessage());
        }
    }
    return null;
}


function updateKhachHangById($idkhachhang, $trangthai){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "UPDATE khachhang SET trangthai= :trangthai WHERE idkhachhang=:idkhachhang";
            $sttm =$conn->prepare($sql);
            $sttm->bindValue(':trangthai', $trangthai, PDO::PARAM_INT);
            $sttm->bindValue(':idkhachhang', $idkhachhang, PDO::PARAM_INT);
            if ($sttm->execute()) {
                return true; 
            }

        }catch(PDOException $e){
            error_log("Lỗi khi cập nhật khách hàng: ".$e->getMessage());
            return false;
        }
    }
    return false;
}

function updateKhachHangFull($idkhachhang, $ten, $email, $sodienthoai, $trangthai){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "UPDATE khachhang SET ten= :ten, email= :email, sodienthoai= :sodienthoai, trangthai= :trangthai WHERE idkhachhang=:idkhachhang";
            $sttm =$conn->prepare($sql);
            $sttm->bindValue(':ten', $ten, PDO::PARAM_STR);
            $sttm->bindValue(':email', $email, PDO::PARAM_STR);
            $sttm->bindValue(':sodienthoai', $sodienthoai, PDO::PARAM_STR);
            $sttm->bindValue(':trangthai', $trangthai, PDO::PARAM_INT);
            $sttm->bindValue(':idkhachhang', $idkhachhang, PDO::PARAM_INT);
            if ($sttm->execute()) {
                return true; 
            }

        }catch(PDOException $e){
            error_log("Lỗi khi cập nhật khách hàng: ".$e->getMessage());
            return false;
        }
    }
    return false;
}

function checkTrungEmailVaSDTKH($emailKH, $sdtKH) {
    $conn = connectdb(); // Kết nối CSDL
    
    // Kiểm tra email 
    $sqlEmail = "SELECT COUNT(*) FROM khachhang WHERE email = :emailKH";
    $stmtEmail = $conn->prepare($sqlEmail);
    $stmtEmail->bindParam(':emailKH', $emailKH, PDO::PARAM_STR);
    $stmtEmail->execute();
    $existsEmail = $stmtEmail->fetchColumn() > 0; // true nếu tên nhà xuất bản đã tồn tại
    
    // Kiểm tra số điện thoại
    $sqlSDT = "SELECT COUNT(*) FROM khachhang WHERE sodienthoai = :sdtKH";
    $stmtSDT = $conn->prepare($sqlSDT);
    $stmtSDT->bindParam(':sdtKH', $sdtKH, PDO::PARAM_STR);
    $stmtSDT->execute();
    $existsSDT = $stmtSDT->fetchColumn() > 0; // true nếu số điện thoại đã tồn tại

    // Trả về kết quả kiểm tra cho từng trường
    return [
        'emailKH' => $existsEmail,
        'sdtKH' => $existsSDT
    ];
}

function searchKhachHang($page, $limit, $keyword){
    $offset = ($page - 1) * $limit;
    $conn = connectdb();
    $keyword = "%" . $keyword . "%";

    $countSql = "SELECT COUNT(*) FROM khachhang WHERE ten LIKE :keyword OR idkhachhang LIKE :keyword 
                    OR email LIKE :keyword OR sodienthoai LIKE :keyword";
    $stmt = $conn -> prepare($countSql);
    $stmt -> bindValue(':keyword', $keyword);
    $stmt -> execute();
    $total = $stmt->fetchColumn();

    $totalPages = ceil($total / $limit);

    $sql = "SELECT * FROM khachhang WHERE ten LIKE :keyword OR idkhachhang LIKE :keyword 
                    OR email LIKE :keyword OR sodienthoai LIKE :keyword LIMIT :limit OFFSET :offset";
    $stmt = $conn->prepare($sql);
    $stmt -> bindValue(':keyword', $keyword);
    $stmt -> bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt -> bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt -> execute();
    $data = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    return [
        'data' => $data,
        'totalPages' => $totalPages,
        'currentPage' => $page
    ];
}
?>
