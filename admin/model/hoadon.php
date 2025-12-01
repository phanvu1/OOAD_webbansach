<?php
function getDataHoaDon($page, $limit){
    $conn = connectdb();
    if($conn){
        //lấy trang bắt đầu phân trang
        $offset = ($page -1)*$limit;
        //đếm số lượng phiếu nhập
        $sqlCount ="SELECT count(*) AS total FROM hoadon";
        $sttm = $conn->prepare($sqlCount);
        $sttm->execute();
        $totalRecords = $sttm->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords/$limit);

        //load data
        $sql = "SELECT hoadon.*, thongtinnhanhang.thanhpho 
            FROM hoadon
            JOIN thongtinnhanhang ON hoadon.iddiachi = thongtinnhanhang.iddiachi
            LIMIT :limit OFFSET :offset";
        $sttm = $conn->prepare($sql);
        $sttm->bindValue(':limit', $limit, PDO::PARAM_INT);
        $sttm->bindValue(':offset', $offset, PDO::PARAM_INT);
        $sttm->execute();
        $data = $sttm->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data'=>$data,
            'currentPage'=>$page,
            'totalPages'=>$totalPages
        ];

    }
    return [
        'data'=>[],
        'currentPage'=>1,
        'totalPages'=>[]
    ];
}

function getDataHoaDonFiltered($page, $limit, $startDate = null, $endDate = null, $city = null, $status = null) {
    $conn = connectdb();
    if ($conn) {
        $offset = ($page - 1) * $limit;
        $conditions = [];
        $params = [];

        // Tạo điều kiện động
        if (!empty($startDate) && !empty($endDate)) {
            $conditions[] = "hd.ngayxuat BETWEEN :startDate AND :endDate";
            $params[':startDate'] = $startDate;
            $params[':endDate'] = $endDate;
        }

        if (!empty($city)) {
            $conditions[] = "ttnh.thanhpho LIKE :city";
            $params[':city'] = '%' . $city . '%';  // cho phép tìm gần đúng
        }               

        if ($status !== null && $status !== "") {
            $conditions[] = "hd.trangthai = :status";
            $params[':status'] = $status;
        }

        $whereSQL = count($conditions) > 0 ? 'WHERE ' . implode(' AND ', $conditions) : '';

        // Đếm tổng
        $sqlCount = "
            SELECT COUNT(*) FROM hoadon hd
            JOIN thongtinnhanhang ttnh ON hd.iddiachi = ttnh.iddiachi
            $whereSQL
        ";
        $stmtCount = $conn->prepare($sqlCount);
        foreach ($params as $key => $value) {
            $stmtCount->bindValue($key, $value);
        }
        $stmtCount->execute();
        $totalRecords = $stmtCount->fetchColumn();
        $totalPages = ceil($totalRecords / $limit);

        // Lấy dữ liệu
        $sql = "
            SELECT hd.*, ttnh.thanhpho FROM hoadon hd
            JOIN thongtinnhanhang ttnh ON hd.iddiachi = ttnh.iddiachi
            $whereSQL
            ORDER BY hd.ngayxuat DESC
            LIMIT :limit OFFSET :offset
        ";
        $stmt = $conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $data,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ];
    }

    return [
        'data' => [],
        'currentPage' => 1,
        'totalPages' => 0
    ];
}

function getDataHoaDonByID($idhoadon){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "SELECT * from hoadon WHERE idhoadon = :idhoadon ";
            $sttm =$conn->prepare($sql);
            $sttm->bindValue(':idhoadon', $idhoadon, PDO::PARAM_INT);
            $sttm->execute()>0;
            $success = $sttm->rowCount()>0;
            if($success){
                return $sttm->fetch(PDO::FETCH_ASSOC);
            }else{
                return null;
            }

        }catch(PDOException $e){
            error_log("Không tìm thấy thông tin cụ thể của hóa đơn!", $e->getMessage());
        }
    }
    return null;
}

function searchHoaDon($keyword, $page, $limit) {
    $offset = ($page - 1) * $limit;
    $conn = connectdb();
    $keyword = "%" . $keyword . "%";

    // Đếm tổng kết quả khớp
    $countSql = "SELECT COUNT(*) FROM hoadon WHERE idhoadon LIKE :keyword";
    $stmt = $conn->prepare($countSql);
    $stmt->bindValue(':keyword', $keyword);
    $stmt->execute();
    $total = $stmt->fetchColumn();

    $totalPages = ceil($total / $limit);

    $sql = "SELECT * FROM hoadon WHERE idhoadon LIKE :keyword LIMIT :limit OFFSET :offset";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':keyword', $keyword);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'data' => $data,
        'currentPage' => $page,
        'totalPages' => $totalPages
    ];
}

function updateHoaDonById($idhoadon, $idnhanvien, $trangthai){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "UPDATE hoadon SET trangthai= :trangthai, idnhanvien = :idnhanvien WHERE idhoadon=:idhoadon";
            $sttm =$conn->prepare($sql);
            $sttm->bindValue(':trangthai', $trangthai, PDO::PARAM_INT);
            $sttm->bindValue(':idnhanvien', $idnhanvien, PDO::PARAM_INT);
            $sttm->bindValue(':idhoadon', $idhoadon, PDO::PARAM_INT);
            $sttm->execute();
            $success = $sttm->rowCount();
            if($success >0){
                return true;
            }

        }catch(PDOException $e){
            error_log("Lỗi cập nhật hóa đơn!". $e->getMessage());
        }
    }
    return false;
}

function getSoLuongKhachMuaHang() {
    $conn = connectdb();
    $sql = "SELECT COUNT(DISTINCT idkhachhang) AS soKhach FROM hoadon WHERE trangthai IN (1, 2)";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['soKhach'];
}

?>
