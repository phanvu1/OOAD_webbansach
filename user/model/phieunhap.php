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
?>