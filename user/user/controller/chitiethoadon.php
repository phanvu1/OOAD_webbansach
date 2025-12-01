<?php
require_once "../../model/connectDB.php";
require_once "../../model/chitiethoadon.php";

header('Content-Type: application/json');

class ChiTietHoaDonController {
    public function handleRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        switch ($action) {
            case 'getOrderDetails':
                $result = $this->getOrderDetails($id);
                echo json_encode($result);
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'Action không hợp lệ']);
        }
    }

    private function getOrderDetails($idhoadon) {
        $conn = connectdb();
        if (!$conn) {
            return ['status' => 'error', 'message' => 'Không thể kết nối cơ sở dữ liệu'];
        }
        try {
            $sql = "SELECT s.tensach, cthd.soluong, cthd.gia 
                    FROM chitiethoadon cthd
                    JOIN sach s ON cthd.idsach = s.idsach
                    WHERE cthd.idhoadon = :idhoadon";
            $sttm = $conn->prepare($sql);
            $sttm->bindValue(':idhoadon', $idhoadon, PDO::PARAM_INT);
            $sttm->execute();
            $result = $sttm->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                return $result;
            } else {
                return ['status' => 'error', 'message' => 'Không tìm thấy chi tiết hóa đơn'];
            }
        } catch (PDOException $e) {
            error_log("Lỗi khi lấy chi tiết hóa đơn: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Lỗi hệ thống'];
        }
    }
}

$controller = new ChiTietHoaDonController();
$controller->handleRequest();