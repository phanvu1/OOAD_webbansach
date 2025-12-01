<?php
require_once "../../model/connectDB.php";
require_once "../../model/hoadon.php";
require_once "../../model/khachhang.php";
require_once "../../model/thongtinnhanhang.php";

header('Content-Type: application/json');

class HoaDonController {
    public function handleRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $idKhachHang = isset($_GET['idKhachHang']) ? $_GET['idKhachHang'] : '';

        switch ($action) {
            case 'getOrderInfo':
                $result = $this->getOrderInfo($id);
                echo json_encode($result);
                break;
            case 'getOrderByCustomer':
                $result = $this->getOrderByCustomer($idKhachHang);
                echo json_encode($result);
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'Action không hợp lệ']);
        }
    }

    private function getOrderInfo($idhoadon) {
        try {
            // Lấy thông tin hóa đơn
            $hoadon = getDataHoaDonByID($idhoadon);
            if (!$hoadon) {
                error_log("Không tìm thấy hóa đơn với idhoadon: $idhoadon");
                return ['status' => 'error', 'message' => 'Không tìm thấy hóa đơn'];
            }
    
            // Lấy thông tin khách hàng
            $khachhang = getDataKhachHangTheoId($hoadon['idkhachhang']);
            if (!$khachhang) {
                error_log("Không tìm thấy khách hàng với idkhachhang: {$hoadon['idkhachhang']}");
                return ['status' => 'error', 'message' => 'Không tìm thấy thông tin khách hàng'];
            }
    
            // Lấy thông tin nhận hàng
            $thongtinnhanhang = getDataThongTinNhanHangByID($hoadon['iddiachi']);
            if (!$thongtinnhanhang) {
                error_log("Không tìm thấy thông tin nhận hàng với iddiachi: {$hoadon['iddiachi']}");
                return ['status' => 'error', 'message' => 'Không tìm thấy thông tin nhận hàng'];
            }
    
            // Tạo kết quả trả về
            $result = [
                'idhoadon' => $hoadon['idhoadon'],
                'tennguoinhan' => $khachhang['ten'],
                'sdt' => $thongtinnhanhang['sdtNgNhan'],
                'diachi' => $thongtinnhanhang['diachi_chitiet'],
                'phuongthuctt' => $hoadon['phuongthuctt']
            ];
    
            error_log("Kết quả getOrderInfo: " . print_r($result, true)); // Log để debug
            return $result;
    
        } catch (Exception $e) {
            error_log("Lỗi khi lấy thông tin hóa đơn: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    private function getOrderByCustomer($idKhachHang) {
        try {
            $orders = getOrdersByCustomerId($idKhachHang);
            if ($orders !== false) {
                return ['status' => 'success', 'data' => $orders];
            } else {
                return ['status' => 'success', 'data' => []];
            }
        } catch (Exception $e) {
            error_log("Lỗi khi lấy danh sách hóa đơn: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Lỗi hệ thống'];
        }
    }
}

$controller = new HoaDonController();
$controller->handleRequest();
?>