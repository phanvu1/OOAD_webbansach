<?php

function getDataDanhMuc($page, $limit){
    $conn = connectdb();
    if($conn){
        //tính trang bắt đầu phân trang
        $offset = ($page -1)*$limit;
        // đếm số tất cả danh mục
        $sqlCount = "SELECT count(*) AS total FROM danhmuc ";
        $sttm = $conn -> prepare($sqlCount);
        $sttm->execute();

        $totalRecords = $sttm->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords/$limit);

        //load data
        $sql = "SELECT * FROM danhmuc LIMIT :limit OFFSET :offset";
        $sttm = $conn->prepare($sql);

        //lấy dữ liệu cho trang hiện tại
        $sttm->bindValue(":limit", $limit, PDO::PARAM_INT);
        $sttm->bindValue(":offset", $offset, PDO::PARAM_INT);

        $sttm->execute();
        $data = $sttm->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data'=>$data,
            'totalPages'=>$totalPages,
            'currentPages'=>$page
        ];

    }
    return ['data' => [], 'totalPages'=>[], 'currentPages' => 1];
}

function delDanhMucById($iddanhmuc){
    $conn = connectdb();
    if($conn){
        $sql = "UPDATE danhmuc SET trangthai=0 WHERE iddanhmuc = :iddanhmuc";
        $sttm = $conn->prepare($sql);
        $sttm->bindValue(':iddanhmuc', $iddanhmuc, PDO::PARAM_INT);
        $sttm->execute();
        if($sttm->rowCount() >0){
            return true;
        }
    }
    return false;
}

function themDanhMuc($tenDM) {
    $conn = connectdb(); // Kết nối cơ sở dữ liệu
    if ($conn) {
        try {
            // Chuẩn bị câu lệnh SQL để thêm dữ liệu
            $stmt = $conn->prepare("
                INSERT INTO danhmuc (tendanhmuc, trangthai) 
                VALUES (:tenDM, 1)
            ");

            // Gán giá trị cho các tham số
            $stmt->bindParam(':tenDM', $tenDM, PDO::PARAM_STR);

            // Thực thi truy vấn
            if ($stmt->execute()) {
                return true; // Thêm thành công
            }
        } catch (PDOException $e) {
            // Ghi log lỗi (nếu xảy ra)
            error_log("Lỗi khi thêm danh mục: " . $e->getMessage());
        }
    }
    return false; // Thêm thất bại
}

function getComboboxCTDanhMuc() {
    $conn = connectdb();
    if ($conn) {
        try {
            $sql = "SELECT ctdm.idchitietdanhmuc, ctdm.tenchitietdanhmuc, 
                        dm.iddanhmuc, dm.tendanhmuc 
                    FROM chitietdanhmuc ctdm
                    JOIN danhmuc dm ON ctdm.iddanhmuc = dm.iddanhmuc";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            error_log("Lỗi khi lấy danh sách chi tiết danh mục: " . $e->getMessage());
        }
    }
    return false;
}

function getDanhMucById($iddanhmuc) {
    $conn = connectdb();
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT iddanhmuc, tendanhmuc FROM danhmuc WHERE iddanhmuc = :iddanhmuc");
            $stmt->bindParam(':iddanhmuc', $iddanhmuc, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            error_log("Lỗi khi lấy danh mục: " . $e->getMessage());
        }
    }
    return null;
}

function getTenDanhMuc($iddanhmuc) {
    $conn = connectdb();
    if ($conn) {
        try {
            $sql = "SELECT tendanhmuc FROM danhmuc WHERE iddanhmuc = :iddanhmuc";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':iddanhmuc', $iddanhmuc, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['tendanhmuc'] ?? 'Không xác định';
        } catch (PDOException $e) {
            error_log("Lỗi khi lấy tên danh mục: " . $e->getMessage());
        }
    }
    return 'Không xác định';
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
function getDataDanhMucTheoId($iddanhmuc){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "SELECT * from danhmuc WHERE iddanhmuc = :iddanhmuc ";
            $sttm =$conn->prepare($sql);
            $sttm->bindValue(':iddanhmuc', $iddanhmuc, PDO::PARAM_INT);
            $sttm->execute()>0;
            $success = $sttm->rowCount()>0;
            if($success){
                return $sttm->fetch(PDO::FETCH_ASSOC);
            }else{
                return null;
            }

        }catch(PDOException $e){
            error_log("Không tìm thấy thông tin cụ thể của danh mục!", $e->getMessage());
        }
    }
    return null;
}


function updateDanhMucById($iddanhmuc, $trangthai){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "UPDATE danhmuc SET trangthai= :trangthai WHERE iddanhmuc=:iddanhmuc";
            $sttm =$conn->prepare($sql);
            $sttm->bindValue(':trangthai', $trangthai, PDO::PARAM_INT);
            $sttm->bindValue(':iddanhmuc', $iddanhmuc, PDO::PARAM_INT);
            if ($sttm->execute()) {
                return true; 
            }

        }catch(PDOException $e){
            error_log("Lỗi khi cập nhật danh mục: ".$e->getMessage());
            return false;
        }
    }
    return false;
}
?>