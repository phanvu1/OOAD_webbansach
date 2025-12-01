<?php
function getDataNhaCungCap($page, $limit){
    $conn = connectdb();
    if($conn){
        //trang bắt đầu phân trang
        $offset = ($page-1)* $limit;
        //đến nhà cung cấp
        $sqlCount = "SELECT count(*) as total FROM nhacungcap";
        $sttm = $conn->prepare($sqlCount);
        $sttm->execute();
        $totalRecords = $sttm->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords/$limit);

        //load data
        $sql = "SELECT * FROM nhacungcap LIMIT :limit OFFSET :offset";
        $sttm = $conn->prepare($sql);
        $sttm->bindValue(":limit", $limit, PDO::PARAM_INT);
        $sttm->bindValue(":offset", $offset, PDO::PARAM_INT);
        $sttm->execute();
        $data = $sttm -> fetchAll(PDO::FETCH_ASSOC);
        return [
            'data'=>$data,
            'currentPage'=>$page,
            'totalPages'=>$totalPages
        ];

    }
    return ['data'=> [], 'currentPage'=> 1, 'totalPages'=> []];
}

function delNhaCungCapById($idnhacungcap){
    $conn = connectdb();
    if($conn){
        $sql = "UPDATE nhacungcap SET trangthai = 0 WHERE idnhacungcap = :idnhacungcap ";
        $sttm = $conn -> prepare($sql);
        $sttm-> bindValue(':idnhacungcap', $idnhacungcap, PDO::PARAM_INT);
        $sttm->execute();
        $result = $sttm->rowCount();
        if($result >0){
            return true;
        }
    }
    return false;
}

function themNCC($tenncc, $email, $sodienthoai, $diachi) {
    $conn = connectdb(); // Kết nối cơ sở dữ liệu
    if ($conn) {
        try {
            $stmt = $conn->prepare("
                INSERT INTO nhacungcap (tenncc, email, sodienthoai, diachi, trangthai) 
                VALUES (:tenncc, :email, :sodienthoai, :diachi, 1)
            ");

            // Gán giá trị cho các tham số
            $stmt->bindParam(':tenncc', $tenncc, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':sodienthoai', $sodienthoai, PDO::PARAM_STR);
            $stmt->bindParam(':diachi', $diachi, PDO::PARAM_STR);

            // Thực thi truy vấn
            if ($stmt->execute()) {
                return true; // Thêm thành công
            }
        } catch (PDOException $e) {
            // Log lỗi (nếu cần)
            error_log("Lỗi khi thêm nhà cung cấp: " . $e->getMessage());
        }
    }
    return false; // Thêm thất bại
}

function checkTrungTenVaEmailVaSdtNCC($tenNCC, $emailNCC, $sdtNCC) {
    $conn = connectdb(); // Kết nối CSDL

    // Kiểm tra tên
    $sqlTen = "SELECT COUNT(*) FROM nhacungcap WHERE tenncc = :tenNCC AND trangthai = 1";
    $stmtTen = $conn->prepare($sqlTen);
    $stmtTen->bindParam(':tenNCC', $tenNCC, PDO::PARAM_STR);
    $stmtTen->execute();
    $existsTen = $stmtTen->fetchColumn() > 0; // true nếu tên nhà xuất bản đã tồn tại
    
    // Kiểm tra email
    $sqlEmail = "SELECT COUNT(*) FROM nhacungcap WHERE email = :emailNCC AND trangthai = 1";
    $stmtEmail = $conn->prepare($sqlEmail);
    $stmtEmail->bindParam(':emailNCC', $emailNCC, PDO::PARAM_STR);
    $stmtEmail->execute();
    $existsEmail = $stmtEmail->fetchColumn() > 0; // true nếu tên nhà xuất bản đã tồn tại
    
    // Kiểm tra số điện thoại
    $sqlSDT = "SELECT COUNT(*) FROM nhacungcap WHERE sodienthoai = :sdtNCC AND trangthai = 1";
    $stmtSDT = $conn->prepare($sqlSDT);
    $stmtSDT->bindParam(':sdtNCC', $sdtNCC, PDO::PARAM_STR);
    $stmtSDT->execute();
    $existsSDT = $stmtSDT->fetchColumn() > 0; // true nếu số điện thoại đã tồn tại

    // Trả về kết quả kiểm tra cho từng trường
    return [
        'tenNCC' => $existsTen,
        'emailNCC' => $existsEmail,
        'sdtNCC' => $existsSDT
    ];
}

function getDataNhaCungCapTheoId($idnhacungcap){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "SELECT * from nhacungcap WHERE idnhacungcap = :idnhacungcap ";
            $sttm =$conn->prepare($sql);
            $sttm->bindValue(':idnhacungcap', $idnhacungcap, PDO::PARAM_INT);
            $sttm->execute()>0;
            $success = $sttm->rowCount()>0;
            if($success){
                return $sttm->fetch(PDO::FETCH_ASSOC);
            }else{
                return null;
            }

        }catch(PDOException $e){
            error_log("Không tìm thấy thông tin cụ thể của nhà cung cấp!", $e->getMessage());
        }
    }
    return null;
}

function updateNhaCungCapById($idnhacungcap, $trangthai){
    $conn = connectdb();
    if($conn){
        try{
            $sql = "UPDATE nhacungcap SET trangthai= :trangthai WHERE idnhacungcap=:idnhacungcap";
            $sttm =$conn->prepare($sql);
            $sttm->bindValue(':trangthai', $trangthai, PDO::PARAM_INT);
            $sttm->bindValue(':idnhacungcap', $idnhacungcap, PDO::PARAM_INT);
            if ($sttm->execute()) {
                return true; 
            }

        }catch(PDOException $e){
            error_log("Lỗi khi cập nhật nhà cung cấp: ".$e->getMessage());
            return false;
        }
    }
    return false;
}
?>
