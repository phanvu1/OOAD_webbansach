<?php
function getDataBanner($page, $limit){
    $conn = connectdb();
    if($conn){
        //tính trang bắt đầu phân trang
        $offset = ($page -1)*$limit;
        //đếm 
        $sqlCount = "select count(*) as total from banner";
        $sttm = $conn->prepare($sqlCount);
        $sttm->execute();
        $totalRecords = $sttm->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords/$limit);
        //load data
        $sql = "select * from banner LIMIT :limit OFFSET :offset";
        $sttm = $conn->prepare($sql);
        //lấy dữ liệu cho trang hiện tại
        $sttm->bindValue(':limit', $limit, PDO::PARAM_INT);
        $sttm->bindValue(':offset', $offset, PDO::PARAM_INT);
        $sttm->execute();
        $data = $sttm->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data'=>$data,
            'totalPages'=>$totalPages,
            'currentPages'=>$page
        ];
    }
    return ['data' => [], 'totalPages'=>0, 'currentPages'=>1];
}
    function getAllBanners() {
        $conn = connectdb();
        if ($conn) {
            $sql = "SELECT * FROM banner";
            $sttm = $conn->prepare($sql);
            $sttm->execute();
            return $sttm->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }
?>