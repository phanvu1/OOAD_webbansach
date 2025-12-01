<?php
function getDataSanPhamPhieuNhap(){
    $conn = connectdb();
    if($conn){
        $sql = "select * from sach";
        $sttm = $conn->prepare($sql);
        $sttm->execute();
        $data = $sttm->fetchAll(PDO::FETCH_ASSOC);
        return ['data' => $data];
    }
    return ['data' => []];
}

function getDataSanPham($page = 1, $limit = 4){
    $conn = connectdb();
    if($conn){
        $offset = ($page - 1) * $limit;
        $sqlCount = "select count(*) as total from sach";
        $sttm = $conn->prepare($sqlCount);
        $sttm->execute();
        $totalRecords = $sttm->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords / $limit);
        $sql = "select * from sach LIMIT :limit OFFSET :offset";
        $sttm = $conn->prepare($sql);
        $sttm->bindValue(':limit', $limit, PDO::PARAM_INT);
        $sttm->bindValue(':offset', $offset, PDO::PARAM_INT);
        $sttm->execute();
        $data = $sttm->fetchAll(PDO::FETCH_ASSOC);
        return [
            'data' => $data,
            'totalPages' => $totalPages,
            'currentPages' => $page
        ];
    }
    return ['data' => [], 'totalPages' => 0, 'currentPages' => 1];
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
            $sql = "SELECT * from sach WHERE idsach = :idsach";
            $sttm = $conn->prepare($sql);
            $sttm->bindValue(':idsach', $idsach, PDO::PARAM_INT);
            $sttm->execute();
            $success = $sttm->rowCount() > 0;
            if($success){
                return $sttm->fetch(PDO::FETCH_ASSOC);
            } else {
                return null;
            }
        } catch(PDOException $e){
            error_log("Không tìm thấy thông tin cụ thể của sản phẩm: " . $e->getMessage());
        }
    }
    return null;
}

function updateSanPhamById($idsach, $trangthai){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "UPDATE sach SET trangthai = :trangthai WHERE idsach = :idsach";
            $sttm = $conn->prepare($sql);
            $sttm->bindValue(':trangthai', $trangthai, PDO::PARAM_INT);
            $sttm->bindValue(':idsach', $idsach, PDO::PARAM_INT);
            if ($sttm->execute()) {
                return true;
            }
        } catch(PDOException $e){
            error_log("Lỗi khi cập nhật sản phẩm: " . $e->getMessage());
            return false;
        }
    }
    return false;
}

function themSach($tenSach, $idTacGia, $idNXB, $idTheLoai, $idCTDanhMuc, $moTa, $anhbia) {
    $conn = connectdb();
    if ($conn) {
        try {
            $stmt = $conn->prepare("
                INSERT INTO sach (tensach, idtacgia, idnhaxuatban, idtheloai, idctdanhmuc, gia, sltonkho, mota, anhbia, trangthai) 
                VALUES (:tenSach, :idTacGia, :idNXB, :idTheLoai, :idCTDanhMuc, 0.00, 0, :moTa, :anhbia, 1)
            ");
            $stmt->bindParam(':tenSach', $tenSach, PDO::PARAM_STR);
            $stmt->bindParam(':idTacGia', $idTacGia, PDO::PARAM_INT);
            $stmt->bindParam(':idNXB', $idNXB, PDO::PARAM_INT);
            $stmt->bindParam(':idTheLoai', $idTheLoai, PDO::PARAM_INT);
            $stmt->bindParam(':idCTDanhMuc', $idCTDanhMuc, PDO::PARAM_INT);
            $stmt->bindParam(':moTa', $moTa, PDO::PARAM_STR);
            $stmt->bindParam(':anhbia', $anhbia, PDO::PARAM_STR);
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            error_log("Lỗi khi thêm sách: " . $e->getMessage());
        }
    }
    return false;
}

function checkTrungTenSach($tenSach) {
    $conn = connectdb();
    if ($conn) {
        try {
            $sql = "SELECT COUNT(*) FROM sach WHERE tensach = :tenSach AND trangthai = 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':tenSach', $tenSach, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetchColumn();
            return $count > 0;
        } catch (PDOException $e) {
            error_log("Lỗi khi kiểm tra trùng tên sách: " . $e->getMessage());
            return false;
        }
    }
    return false;
}

function kiemTraIdSachTrongChiTietHoaDon($idsach){
    $conn = connectdb();
    if($conn){
        $sql = "SELECT count(*) FROM chitiethoadon WHERE idsach = :idsach";
        $sttm = $conn->prepare($sql);
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

// Hàm mới: Lấy số lượng tồn kho của sản phẩm
function getSoLuongTonKho($idsach) {
    $conn = connectdb();
    if ($conn) {
        try {
            $sql = "SELECT sltonkho FROM sach WHERE idsach = :idsach AND trangthai = 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':idsach', $idsach, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['sltonkho'] : 0;
        } catch (PDOException $e) {
            error_log("Lỗi khi lấy số lượng tồn kho: " . $e->getMessage());
            return 0;
        }
    }
    return 0;
}

// Hàm mới: Cập nhật số lượng tồn kho
function updateSoLuongTonKho($idsach, $soLuong) {
    $conn = connectdb();
    if ($conn) {
        try {
            $sql = "UPDATE sach SET sltonkho = sltonkho - :soLuong WHERE idsach = :idsach AND trangthai = 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':soLuong', $soLuong, PDO::PARAM_INT);
            $stmt->bindValue(':idsach', $idsach, PDO::PARAM_INT);
            $success = $stmt->execute();
            if ($success && $stmt->rowCount() > 0) {
                error_log("Cập nhật tồn kho thành công: idsach=$idsach, soLuong=$soLuong");
                return true;
            } else {
                error_log("Cập nhật tồn kho thất bại: idsach=$idsach, soLuong=$soLuong, không có hàng được cập nhật");
                return false;
            }
        } catch (PDOException $e) {
            error_log("Lỗi khi cập nhật số lượng tồn kho: " . $e->getMessage());
            return false;
        }
    }
    return false;
}
?>