<?php
function getDataSanPhamPhieuNhap(){
    $conn = connectdb();
    if($conn){
        //load data
        $sql = "select * from sach WHERE trangthai =1";
        $sttm = $conn->prepare($sql);
        $sttm->execute();
        $data = $sttm->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data'=>$data
        ];
    }
    return ['data' => []];
}

function getDataSanPham($page =1, $limit=4){
    $conn = connectdb();
    if($conn){
        //tính trang bắt đầu phân trang
        $offset = ($page -1)*$limit;
        //đếm số sản phẩm
        $sqlCount = "select count(*) as total from sach ";
        $sttm = $conn->prepare($sqlCount);
        $sttm->execute();
        $totalRecords = $sttm->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords/$limit);
        //load data
        $sql = "select * from sach  LIMIT :limit OFFSET :offset";
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

function delSanPhamByID($idsach){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "UPDATE sach SET trangthai = 0 WHERE idsach = :idsach";
            $sttm = $conn->prepare($sql);
            $sttm->bindValue(':idsach', $idsach, PDO::PARAM_INT);
            $success = $sttm->execute();
            if($success && $sttm->rowCount() > 0){
                return true;
            }
        } catch(PDOException $e){
            error_log("Lỗi khi cập nhật trạng thái sách: " . $e->getMessage());
            return false;
        }
    }
    return false;
}

function getDataSanPhamTheoId($idsach){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "SELECT * from sach WHERE idsach = :idsach ";
            $sttm =$conn->prepare($sql);
            $sttm->bindValue(':idsach', $idsach, PDO::PARAM_INT);
            $sttm->execute()>0;
            $success = $sttm->rowCount()>0;
            if($success){
                return $sttm->fetch(PDO::FETCH_ASSOC);
            }else{
                return null;
            }

        }catch(PDOException $e){
            error_log("Không tìm thấy thông tin cụ thể của sản phẩm!", $e->getMessage());
        }
    }
    return null;
}


function updateSanPhamById($idsach, $trangthai){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "UPDATE sach SET trangthai= :trangthai WHERE idsach=:idsach";
            $sttm =$conn->prepare($sql);
            $sttm->bindValue(':trangthai', $trangthai, PDO::PARAM_INT);
            $sttm->bindValue(':idsach', $idsach, PDO::PARAM_INT);
            if ($sttm->execute()) {
                return true; 
            }

        }catch(PDOException $e){
            error_log("Lỗi khi cập nhật sản phẩm: ".$e->getMessage());
            return false;
        }
    }
    return false;
}

function themSach($tenSach, $idTacGia, $idNXB, $idCTDanhMuc, $moTa, $anhbia) {
    $conn = connectdb(); // Kết nối cơ sở dữ liệu
    if ($conn) {
        try {
            // Chuẩn bị câu lệnh SQL để thêm dữ liệu
            $stmt = $conn->prepare("
                INSERT INTO sach (tensach, idtacgia, idnhaxuatban, idctdanhmuc, gia, sltonkho, mota, anhbia, trangthai) 
                VALUES (:tenSach, :idTacGia, :idNXB, :idCTDanhMuc, 0.00, 0, :moTa, :anhbia, 1)
            ");

            // Gán giá trị cho các tham số
            $stmt->bindParam(':tenSach', $tenSach, PDO::PARAM_STR);
            $stmt->bindParam(':idTacGia', $idTacGia, PDO::PARAM_INT);
            $stmt->bindParam(':idNXB', $idNXB, PDO::PARAM_INT); 
            $stmt->bindParam(':idCTDanhMuc', $idCTDanhMuc, PDO::PARAM_INT);
            $stmt->bindParam(':moTa', $moTa, PDO::PARAM_STR);
            $stmt->bindParam(':anhbia', $anhbia, PDO::PARAM_STR);

            // Thực thi truy vấn
            if ($stmt->execute()) {
                return true; // Thêm thành công
            }
        } catch (PDOException $e) {
            // Ghi log lỗi (nếu xảy ra)
            error_log("Lỗi khi thêm sách: " . $e->getMessage());
        }
    }
    return false; // Thêm thất bại
}

//kiểm tra 1 mã id sách có tồn tại bên trang chi tiết hóa đơn hay không. Nếu có thì true-> không thì false
function kiemTraIdSachTrongChiTietHoaDon($idsach){
    $conn = connectdb();
    if($conn){
        $sql = "SELECT count(*) FROM chitiethoadon WHERE idsach =:idsach";
        $sttm =$conn->prepare($sql);
        $sttm->bindValue(':idsach', $idsach, PDO::PARAM_INT);
        $sttm->execute();
        return $sttm->fetchColumn() > 0;
    }
    return false;

}

function delSanPhamTheoIdComplete($idsach){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "DELETE FROM sach WHERE idsach = :idsach";
            $sttm = $conn->prepare($sql);
            $sttm->bindValue(':idsach', $idsach, PDO::PARAM_INT);
            $success = $sttm->execute();
            if($success && $sttm->rowCount() > 0){
                return true;
            }
        } catch(PDOException $e){
            error_log("Lỗi khi xóa sách: " . $e->getMessage());
            return false;
        }
    }
    return false;
}

function checkTrungTenSach($tenSach) {
    $conn = connectdb();
    if ($conn) {
        try {
            $sql = "SELECT COUNT(*) FROM sach WHERE tensach = :tenSach";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':tenSach', $tenSach, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetchColumn();
            return $count > 0; // Trả về true nếu trùng tên
        } catch (PDOException $e) {
            error_log("Lỗi khi kiểm tra trùng tên sách: " . $e->getMessage());
            return false;
        }
    }
    return false;
}

function searchSanPham($keyword, $page, $limit) {
    $offset = ($page - 1) * $limit;
    $conn = connectdb();
    $keyword = "%" . $keyword . "%";

    // Đếm tổng kết quả khớp
    $countSql = "SELECT COUNT(*) FROM sach WHERE tensach LIKE :keyword OR idsach LIKE :keyword";
    $stmt = $conn->prepare($countSql);
    $stmt->bindValue(':keyword', $keyword);
    $stmt->execute();
    $total = $stmt->fetchColumn();

    $totalPages = ceil($total / $limit);

    // Lấy danh sách sản phẩm tìm kiếm
    $sql = "SELECT * FROM sach WHERE tensach LIKE :keyword OR idsach LIKE :keyword 
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

function updateSoLuongTheoId($idsach, $sltonkho){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "UPDATE sach SET sltonkho = :sltonkho WHERE idsach = :idsach";
            $sttm = $conn->prepare($sql);
            $sttm->bindValue(':sltonkho', $sltonkho, PDO::PARAM_INT);
            $sttm->bindValue(':idsach', $idsach, PDO::PARAM_INT);
            $success = $sttm->execute();
            if($success && $sttm->rowCount() > 0){
                return true;
            }
        } catch(PDOException $e){
            error_log("Lỗi khi xóa sách: " . $e->getMessage());
            return false;
        }
    }
    return false;
}

function updateDonGiaTheoId($idsach, $gia){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "UPDATE sach SET gia = :gia WHERE idsach = :idsach";
            $sttm = $conn->prepare($sql);
            $sttm->bindValue(':gia', $gia, PDO::PARAM_INT);
            $sttm->bindValue(':idsach', $idsach, PDO::PARAM_INT);
            $success = $sttm->execute();
            if($success && $sttm->rowCount() > 0){
                return true;
            }
        } catch(PDOException $e){
            error_log("Lỗi khi xóa sách: " . $e->getMessage());
            return false;
        }
    }
    return false;
}

function updateSlTonKhoTheoId($idsach, $sltonkho){
    $conn = connectdb();
    if($conn){
        $sql = "UPDATE sach SET sltonkho = :sltonkho WHERE idsach = :idsach";
        $sttm = $conn ->prepare($sql);
        $sttm ->bindValue(':sltonkho', $sltonkho, PDO::PARAM_INT);
        $sttm ->bindValue(':idsach', $idsach, PDO::PARAM_INT);
        $sttm->execute();

        if($sttm -> rowCount() >0){
            return true;
        }
    }
    return false;
}
?>

