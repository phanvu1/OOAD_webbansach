<?php
function getDataTaiKhoanKhachHang($page, $limit){
    $conn = connectdb();
    if($conn){
        //tính trang bắt đầu phân trang
        $offset = ($page -1)*$limit;
        //đếm 
        $sqlCount = "SELECT count(*) AS total FROM taikhoan_khachhang";
        $sttm = $conn->prepare($sqlCount);
        $sttm->execute();
        $totalRecords = $sttm->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords/$limit);

        //load data
        $sql = "SELECT * FROM taikhoan_khachhang LIMIT :limit OFFSET :offset";
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

function delDataTaiKhoanKhachHangById($idkhachhang){
    $conn = connectdb();
    if($conn){
        try{

            $sql = "UPDATE taikhoan_khachhang SET trangthai = 0 WHERE idkhachhang = :idkhachhang";
            $sttm = $conn ->prepare($sql);
            $sttm->bindValue(":idkhachhang", $idkhachhang, PDO::PARAM_INT);
            $sttm->execute();
            $result = $sttm->rowCount();
            if($result >0){
                return true;
            }

        }catch(PDOException $e){
            error_log("Lỗi khi cập nhật trạng thái tài khoản khách hàng!".$e->getMessage());
        }
    }
    return false;
}

function updateDataTaiKhoanKhachHangById($idkhachhang){
    $conn = connectdb();
    if($conn){
        try{

            $sql = "UPDATE taikhoan_khachhang SET trangthai = 1 WHERE idkhachhang = :idkhachhang";
            $sttm = $conn ->prepare($sql);
            $sttm->bindValue(":idkhachhang", $idkhachhang, PDO::PARAM_INT);
            $sttm->execute();
            $result = $sttm->rowCount();
            if($result >0){
                return true;
            }

        }catch(PDOException $e){
            error_log("Lỗi khi cập nhật trạng thái tài khoản khách hàng!".$e->getMessage());
        }
    }
    return false;
}

function searchTKKH($keyword, $page, $limit) {
    $offset = ($page - 1) * $limit;
    $conn = connectdb();
    $keyword = "%" . $keyword . "%";

    // Đếm tổng kết quả khớp
    $countSql = "SELECT COUNT(*) FROM taikhoan_khachhang WHERE tendangnhap LIKE :keyword OR idkhachhang LIKE :keyword";
    $stmt = $conn->prepare($countSql);
    $stmt->bindValue(':keyword', $keyword);
    $stmt->execute();
    $total = $stmt->fetchColumn();

    $totalPages = ceil($total / $limit);

    $sql = "SELECT * FROM taikhoan_khachhang WHERE tendangnhap LIKE :keyword OR idkhachhang LIKE :keyword 
            LIMIT :limit OFFSET :offset";
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
