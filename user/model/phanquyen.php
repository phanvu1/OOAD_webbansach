<?php
function getDataPhanQuyen($page, $limit){
    $conn = connectdb();
    if($conn){
        //tính trang bắt đầu phân trang
        $offset = ($page -1)*$limit;
        //đếm 
        $sqlCount = "SELECT count(*) AS total FROM nhomquyen";
        $sttm = $conn->prepare($sqlCount);
        $sttm->execute();
        $totalRecords = $sttm->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords/$limit);

        //load data
        $sql = "SELECT * FROM nhomquyen LIMIT :limit OFFSET :offset";
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

function delDataPhanQuyenById($idquyen){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "UPDATE nhomquyen SET trangthai = 0 WHERE idquyen = :idquyen";
            $sttm = $conn ->prepare($sql);
            $sttm->bindValue(":idquyen", $idquyen, PDO::PARAM_INT);
            $sttm->execute();
            $result = $sttm->rowCount();
            if($result >0){
                return true;
            }

        }catch(PDOException $e){
            error_log("Lỗi khi cập nhật trạng thái nhóm quyền!".$e->getMessage());
        }
    }
    return false;
}

function getComboboxQuyen() {
    $conn = connectdb();
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT Quyen FROM phanquyen");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về danh sách toàn bộ quyền
        } catch (PDOException $e) {
            error_log("Lỗi khi lấy danh sách quyền: " . $e->getMessage());
        }
    }
    return false;
}

?>