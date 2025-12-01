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

function getBannerById($id) {
    $conn = connectdb(); // Kết nối cơ sở dữ liệu
    if ($conn) {
        try {
            // Truy vấn lấy banner theo ID
            $stmt = $conn->prepare("SELECT * FROM banner WHERE idbanner = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Trả về kết quả dưới dạng mảng
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Ghi log lỗi nếu xảy ra lỗi
            error_log("Lỗi khi lấy thông tin banner: " . $e->getMessage());
        }
    }
    return null; // Nếu không tìm thấy hoặc có lỗi
}

function updateBanner($id, $hinhanh, $mota) {
    $conn = connectdb(); // Kết nối cơ sở dữ liệu
    if ($conn) {
        try {
            // Cập nhật thông tin banner
            $stmt = $conn->prepare("UPDATE banner SET hinhanh = :hinhanh, mota = :mota WHERE idbanner = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':hinhanh', $hinhanh, PDO::PARAM_STR);
            $stmt->bindParam(':mota', $mota, PDO::PARAM_STR);

            // Thực hiện câu lệnh
            return $stmt->execute();
        } catch (PDOException $e) {
            // Ghi log lỗi nếu xảy ra lỗi
            error_log("Lỗi khi cập nhật banner: " . $e->getMessage());
        }
    }
    return false; // Trả về false nếu có lỗi hoặc kết nối không thành công
}

function deleteBannerById($id) {
    $conn = connectDB(); 

    $sql = "DELETE FROM banner WHERE idbanner = :id";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute(); 
}


?>
