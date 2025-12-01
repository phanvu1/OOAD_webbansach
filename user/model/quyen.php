<?php
    function getComboboxQuyen() {
        $conn = connectdb();
        if ($conn) {
            try {
                $stmt = $conn->prepare("SELECT idquyen, tenquyen FROM nhomquyen");
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về danh sách toàn bộ quyền
            } catch (PDOException $e) {
                error_log("Lỗi khi lấy danh sách quyền: " . $e->getMessage());
            }
        }
        return false;
    }
?>