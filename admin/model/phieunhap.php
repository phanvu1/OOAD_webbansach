<?php
function getDataPhieuNhap($page, $limit){
    $conn = connectdb();
    if($conn){
        //lấy trang bắt đầu phân trang
        $offset = ($page -1)*$limit;
        //đếm số lượng phiếu nhập
        $sqlCount ="SELECT count(*) AS total FROM phieunhap ";
        $sttm = $conn->prepare($sqlCount);
        $sttm->execute();
        $totalRecords = $sttm->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords/$limit);

        //load data
        $sql = "SELECT * FROM phieunhap LIMIT :limit OFFSET :offset";
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

function delPhieuNhapByID($idphieunhap){
    $conn = connectdb();
    if($conn){
        $sql = 'UPDATE phieunhap SET trangthai =0 WHERE idphieunhap = :idphieunhap';
        $sttm = $conn->prepare($sql);
        $sttm->bindValue(':idphieunhap', $idphieunhap, PDO::PARAM_INT);
        $sttm->execute();
        $result = $sttm->rowCount();
        if($result >0){
            return true;
        }
    }
    return false;
}

function searchPhieuNhap($keyword, $page, $limit) {
    $offset = ($page - 1) * $limit;
    $conn = connectdb();
    $keyword = "%" . $keyword . "%";

    // Đếm tổng kết quả khớp
    $countSql = "SELECT COUNT(*) FROM phieunhap WHERE idphieunhap LIKE :keyword";
    $stmt = $conn->prepare($countSql);
    $stmt->bindValue(':keyword', $keyword);
    $stmt->execute();
    $total = $stmt->fetchColumn();

    $totalPages = ceil($total / $limit);

    $sql = "SELECT * FROM phieunhap WHERE idphieunhap LIKE :keyword LIMIT :limit OFFSET :offset";
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

function addPhieuNhap($idnhacungcap, $idnhanvien, $ngaynhap, $tongtien) {
    $conn = connectdb();
    if ($conn) {
        $sql = 'INSERT INTO phieunhap (idnhacungcap, idnhanvien, ngaynhap, tongtien) VALUES (?, ?, ?, ?)';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$idnhacungcap, $idnhanvien, $ngaynhap, $tongtien]);
        // Lấy ID vừa được tạo
        return $conn->lastInsertId();
    }
    return null;
}
function addCTPhieuNhap($idphieunhap, $idsach, $soluong,  $gia, $loinhuan){
    $conn = connectdb();
    if($conn){
        $sql = 'INSERT INTO chitietphieunhap(idphieunhap, idsach, soluong, gia, loinhuan) VALUE (?,?,?,?,?)';
        $sttm = $conn->prepare($sql);
        return $sttm->execute([$idphieunhap, $idsach, $soluong, $gia, $loinhuan]);
    }
    return null;
}

function getDataPhieuNhapTheoID($idphieunhap){
    $conn = connectdb();
    if($conn){
        $sql = "SELECT * FROM phieunhap where idphieunhap = :idphieunhap";
        $sttm = $conn->prepare($sql);
        $sttm->bindValue(':idphieunhap', $idphieunhap, PDO::PARAM_INT);
        $sttm->execute();
        $result = $sttm ->rowCount() ;
        if($result >0){
            return $sttm ->fetch(PDO::FETCH_ASSOC);
        }
    }
    return null;
}

function getDataChiTietPhieuNhapTheoID($idphieunhap){
    $conn = connectdb();
    if($conn){
        $sql = "SELECT * FROM chitietphieunhap WHERE idphieunhap = :idphieunhap";
        $sttm = $conn->prepare($sql);
        $sttm->bindValue(':idphieunhap', $idphieunhap, PDO::PARAM_INT);
        $sttm->execute();
        $result = $sttm ->rowCount();
        if($result >0){
            return $sttm->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    return null;
}

?>
