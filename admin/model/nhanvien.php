<?php
function getDataNhanVien($page, $limit) {
    $conn = connectdb();
    if ($conn) {
        // Tính trang bắt đầu để lấy nhân viên
        $offset = ($page - 1) * $limit;
        
        // Đếm số lượng nhân viên 
        $sqlCount = "SELECT count(*) AS total FROM nhanvien";
        $sttm = $conn->prepare($sqlCount);
        $sttm->execute();
        $totalRecords = $sttm->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords / $limit);

        // Load dữ liệu nhân viên
        $sql = "SELECT * FROM nhanvien LIMIT :limit OFFSET :offset";
        $sttm = $conn->prepare($sql);
        $sttm->bindValue(":limit", $limit, PDO::PARAM_INT);
        $sttm->bindValue(":offset", $offset, PDO::PARAM_INT);
        $sttm->execute();
        $data = $sttm->fetchAll(PDO::FETCH_ASSOC);

        // Kiểm tra xem mỗi nhân viên có tài khoản hay chưa trong cùng hàm
        foreach ($data as &$employee) {
            // Kiểm tra tài khoản nhân viên
            $sqlAccount = "SELECT * FROM taikhoan_nhanvien WHERE idnhanvien = :idNhanVien";
            $stmtAccount = $conn->prepare($sqlAccount);
            $stmtAccount->bindParam(':idNhanVien', $employee['idnhanvien']);
            $stmtAccount->execute();
            $employee['hasAccount'] = $stmtAccount->fetch() ? true : false;
        }

        return [
            'data' => $data,
            'totalPages' => $totalPages,
            'currentPages' => $page
        ];
    }
    return ['data' => [], 'totalPages' => 0, 'currentPages' => 1];
}

function delNhanVienById($idnhanvien){
    $conn = connectdb();
    if($conn){
        $sql = "UPDATE nhanvien SET trangthai = 0 WHERE idnhanvien = :idnhanvien";
        $sttm = $conn->prepare($sql);
        $sttm->bindValue('idnhanvien', $idnhanvien, PDO::PARAM_INT);
        $sttm->execute();
        $result = $sttm->rowCount()>0;
        if($result>0){
            return true;
        }
    }
    return false;
}

function themNhanVien($tennv, $emailnv, $sodienthoainv, $chucVu) {
    $conn = connectdb(); // Kết nối cơ sở dữ liệu
    if ($conn) {
        try {
            $stmt = $conn->prepare("
                INSERT INTO nhanvien (ten, email, sodienthoai, chucvu, trangthai) 
                VALUES (:ten, :email, :sodienthoai, :chucVu, 1)
            ");

            // Gán giá trị cho các tham số
            $stmt->bindParam(':ten', $tennv, PDO::PARAM_STR);
            $stmt->bindParam(':email', $emailnv, PDO::PARAM_STR);
            $stmt->bindParam(':sodienthoai', $sodienthoainv, PDO::PARAM_STR);
            $stmt->bindParam(':chucVu', $chucVu, PDO::PARAM_STR);

            // Thực thi truy vấn
            if ($stmt->execute()) {
                return true; // Thêm thành công
            }
        } catch (PDOException $e) {
            // Log lỗi (nếu cần)
            error_log("Lỗi khi thêm nhân viên: " . $e->getMessage());
        }
    }
    return false; // Thêm thất bại
}

function getComboboxNhanVien() {
    $conn = connectdb();
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT idnhanvien, ten FROM nhanvien");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về danh sách toàn bộ nhân viên
        } catch (PDOException $e) {
            error_log("Lỗi khi lấy danh sách nhân viên: " . $e->getMessage());
        }
    }
    return false;
}

function getDataNhanVienTheoId($idnhanvien){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "SELECT * from nhanvien WHERE idnhanvien = :idnhanvien ";
            $sttm =$conn->prepare($sql);
            $sttm->bindValue(':idnhanvien', $idnhanvien, PDO::PARAM_INT);
            $sttm->execute()>0;
            $success = $sttm->rowCount()>0;
            if($success){
                return $sttm->fetch(PDO::FETCH_ASSOC);
            }else{
                return null;
            }

        }catch(PDOException $e){
            error_log("Không tìm thấy thông tin cụ thể của nhân viên!", $e->getMessage());
        }
    }
    return null;
}

function updateNhanVienById($idnhanvien, $trangthai){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "UPDATE nhanvien SET trangthai= :trangthai WHERE idnhanvien=:idnhanvien";
            $sttm =$conn->prepare($sql);
            $sttm->bindValue(':trangthai', $trangthai, PDO::PARAM_INT);
            $sttm->bindValue(':idnhanvien', $idnhanvien, PDO::PARAM_INT);
            if ($sttm->execute()) {
                return true; 
            }

        }catch(PDOException $e){
            error_log("Lỗi khi cập nhật nhân viên: ".$e->getMessage());
            return false;
        }
    }
    return false;
}

function updateNhanVienFull($idnhanvien, $ten, $email, $sodienthoai, $chucvu, $trangthai){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "UPDATE nhanvien 
                    SET ten = :ten, email = :email, sodienthoai = :sodienthoai, chucvu = :chucvu, trangthai = :trangthai 
                    WHERE idnhanvien = :idnhanvien";
            $sttm = $conn->prepare($sql);
            $sttm->bindValue(':ten', $ten, PDO::PARAM_STR);
            $sttm->bindValue(':email', $email, PDO::PARAM_STR);
            $sttm->bindValue(':sodienthoai', $sodienthoai, PDO::PARAM_STR);
            $sttm->bindValue(':chucvu', $chucvu, PDO::PARAM_STR);
            $sttm->bindValue(':trangthai', $trangthai, PDO::PARAM_INT);
            $sttm->bindValue(':idnhanvien', $idnhanvien, PDO::PARAM_INT);
            if ($sttm->execute()) {
                return true;
            }
        }catch(PDOException $e){
            error_log("Lỗi khi cập nhật đầy đủ nhân viên: ".$e->getMessage());
            return false;
        }
    }
    return false;
}

function checkTrungEmailVaSDT($emailNV, $sdtNV) {
    $conn = connectdb(); // Kết nối CSDL
    
    // Kiểm tra email nhân viên
    $sqlEmail = "SELECT COUNT(*) FROM nhanvien WHERE email = :emailNV";
    $stmtEmail = $conn->prepare($sqlEmail);
    $stmtEmail->bindParam(':emailNV', $emailNV, PDO::PARAM_STR);
    $stmtEmail->execute();
    $existsEmail = $stmtEmail->fetchColumn() > 0; // true nếu tên nhà xuất bản đã tồn tại
    
    // Kiểm tra số điện thoại
    $sqlSDT = "SELECT COUNT(*) FROM nhanvien WHERE sodienthoai = :sdtNV";
    $stmtSDT = $conn->prepare($sqlSDT);
    $stmtSDT->bindParam(':sdtNV', $sdtNV, PDO::PARAM_STR);
    $stmtSDT->execute();
    $existsSDT = $stmtSDT->fetchColumn() > 0; // true nếu số điện thoại đã tồn tại

    // Trả về kết quả kiểm tra cho từng trường
    return [
        'emailNV' => $existsEmail,
        'sdtNV' => $existsSDT
    ];
}

function getNhanVienVaChucVuByIDNV($idNhanVien){
    $conn = connectdb();
    if ($conn) {
        try {
            $sql = "SELECT idnhanvien, ten, chucvu FROM nhanvien WHERE idnhanvien = :idnhanvien";
            $sttm = $conn->prepare($sql);
            $sttm->bindValue(':idnhanvien', $idNhanVien, PDO::PARAM_INT);
            $sttm->execute();

            if ($sttm->rowCount() > 0) {
                return $sttm->fetch(PDO::FETCH_ASSOC);
            } else {
                return null;
            }

        } catch (PDOException $e) {
            error_log("Lỗi khi lấy thông tin nhân viên: " . $e->getMessage());
        }
    }
    return null;
}


function searchNhanVien($keyword, $page, $limit) {
    $offset = ($page - 1) * $limit;
    $conn = connectdb();
    $keyword = "%" . $keyword . "%";

    // Đếm tổng kết quả khớp
    $countSql = "SELECT COUNT(*) FROM nhanvien WHERE ten LIKE :keyword OR idnhanvien LIKE :keyword
        OR email LIKE :keyword OR sodienthoai LIKE :keyword OR chucvu LIKE :keyword";
    $stmt = $conn->prepare($countSql);
    $stmt->bindValue(':keyword', $keyword);
    $stmt->execute();
    $total = $stmt->fetchColumn();

    $totalPages = ceil($total / $limit);

    // Truy vấn dữ liệu khớp từ khóa
    $sql = "SELECT * FROM nhanvien WHERE ten LIKE :keyword OR idnhanvien LIKE :keyword
                OR email LIKE :keyword OR sodienthoai LIKE :keyword OR chucvu LIKE :keyword LIMIT :limit OFFSET :offset";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':keyword', $keyword);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($data as &$employee) {
        $sqlAccount = "SELECT * FROM taikhoan_nhanvien WHERE idnhanvien = :idNhanVien";
        $stmtAccount = $conn->prepare($sqlAccount);
        $stmtAccount->bindParam(':idNhanVien', $employee['idnhanvien']);
        $stmtAccount->execute();
        $employee['hasAccount'] = $stmtAccount->fetch() ? true : false;
    }

    return [
        'data' => $data,
        'totalPages' => $totalPages,
        'currentPages' => $page 
    ];
}
?>
