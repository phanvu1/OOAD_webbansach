<?php
    function getDataCTDanhMuc($iddanhmuc, $page, $limit) {
        $offset = ($page - 1) * $limit;
        $conn = connectdb();
        if ($conn) {
            try {
                // Lấy danh sách chi tiết danh mục với phân trang
                $sql = "
                    SELECT ctdm.idchitietdanhmuc, ctdm.tenchitietdanhmuc, dm.iddanhmuc, dm.tendanhmuc
                    FROM chitietdanhmuc ctdm
                    JOIN danhmuc dm ON ctdm.iddanhmuc = dm.iddanhmuc
                    WHERE ctdm.iddanhmuc = :iddanhmuc
                    LIMIT :offset, :limit
                ";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':iddanhmuc', $iddanhmuc, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                // Đếm tổng số bản ghi
                $sqlTotal = "SELECT COUNT(*) FROM chitietdanhmuc WHERE iddanhmuc = :iddanhmuc";
                $stmtTotal = $conn->prepare($sqlTotal);
                $stmtTotal->bindParam(':iddanhmuc', $iddanhmuc, PDO::PARAM_INT);
                $stmtTotal->execute();
                $totalRecords = $stmtTotal->fetchColumn();
                $totalPages = ceil($totalRecords / $limit);
    
                return [
                    'currentPage' => $page,
                    'data' => $result,
                    'totalPages' => $totalPages
                ];
            } catch (PDOException $e) {
                error_log("Lỗi khi lấy danh sách chi tiết danh mục: " . $e->getMessage());
            }
        }
        return [];
    } 

    function checkTrungTenCTDM($idDanhMuc, $tenChiTiet) {
        $conn = connectdb();
        if ($conn) {
            try {
                $sql = "SELECT COUNT(*) FROM chitietdanhmuc WHERE iddanhmuc = :iddanhmuc AND tenchitietdanhmuc = :tenchitiet";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':iddanhmuc', $idDanhMuc, PDO::PARAM_INT);
                $stmt->bindParam(':tenchitiet', $tenChiTiet, PDO::PARAM_STR);
                $stmt->execute();
                $count = $stmt->fetchColumn();
                return $count > 0; // Trả về true nếu trùng tên
            } catch (PDOException $e) {
                error_log("Lỗi khi kiểm tra trùng tên chi tiết danh mục: " . $e->getMessage());
                return false;
            }
        }
        return false;
    }

    function themCTDM($iddanhmuc, $tenctdm) {
        $conn = connectdb(); // Kết nối cơ sở dữ liệu
        if ($conn) {
            try {
                // Chuẩn bị câu lệnh SQL để thêm dữ liệu
                $stmt = $conn->prepare("
                    INSERT INTO chitietdanhmuc (iddanhmuc, tenchitietdanhmuc) 
                    VALUES (:iddanhmuc, :tenctdm)
                ");

                // Gán giá trị cho các tham số
                $stmt->bindParam(':iddanhmuc', $iddanhmuc, PDO::PARAM_STR);
                $stmt->bindParam(':tenctdm', $tenctdm, PDO::PARAM_STR);

                // Thực thi truy vấn
                if ($stmt->execute()) {
                    return true; // Thêm thành công
                }
            } catch (PDOException $e) {
                // Ghi log lỗi (nếu xảy ra)
                error_log("Lỗi khi thêm chi tiết danh mục: " . $e->getMessage());
            }
        }
        return false; // Thêm thất bại
    }
?>