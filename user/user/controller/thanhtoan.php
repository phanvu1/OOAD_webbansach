<?php
require_once "../../model/connectDB.php";
require_once "../../model/hoadon.php";
require_once "../../model/nhanvien.php";
require_once "../../model/chitiethoadon.php";
require_once "../../model/giohang.php";
require_once "../../model/sanpham.php";

header('Content-Type: application/json');

ob_start();

class ThanhToanController {
    public function handleRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'placeOrder':
                $result = $this->placeOrder();
                break;
            default:
                $result = json_encode(['status' => 'error', 'message' => 'Action không hợp lệ']);
        }
        // $output = ob_get_clean();
        // if ($output) {
        //     error_log("Đầu ra không mong muốn: " . $output);
        //     echo json_encode(['status' => 'error', 'message' => 'Lỗi server: Có đầu ra không mong muốn']);
        // } else {
            echo $result;
        // }
    }

    private function placeOrder() {
        try {
            $data = $_POST;
            if (!isset($data['idkhachhang']) || !isset($data['iddiachi']) || !isset($data['phuongthuctt']) || !isset($data['ngayxuat']) || !isset($data['tongtien'])) {
                return json_encode(['status' => 'error', 'message' => 'Thiếu thông tin bắt buộc']);
            }

            $idkhachhang = $data['idkhachhang'];
            $iddiachi = $data['iddiachi'];
            $phuongthuctt = $data['phuongthuctt'];
            $ngayxuat = $data['ngayxuat'];
            $tongtien = $data['tongtien'];
            $trangthai = isset($data['trangthai']) ? $data['trangthai'] : 0;

            if (!is_numeric($idkhachhang) || !is_numeric($iddiachi) || !is_numeric($tongtien)) {
                return json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
            }

            $conn = connectdb();
            if (!$conn) {
                return json_encode(['status' => 'error', 'message' => 'Không thể kết nối cơ sở dữ liệu']);
            }

            $conn->beginTransaction();

            $idnhanvien = getAvailableNhanVien();
            if ($idnhanvien === null) {
                $conn->rollBack();
                return json_encode(['status' => 'error', 'message' => 'Không tìm thấy nhân viên khả dụng']);
            }

            $cartItems = getAllSanPhamTrongGioHangID($idkhachhang);
            if (empty($cartItems)) {
                $conn->rollBack();
                return json_encode(['status' => 'error', 'message' => 'Giỏ hàng trống']);
            }

            $idhoadon = addHoaDon($idkhachhang, $iddiachi, $idnhanvien, $phuongthuctt, $ngayxuat, $tongtien, $trangthai);
            if (!$idhoadon) {
                $conn->rollBack();
                return json_encode(['status' => 'error', 'message' => 'Tạo hóa đơn thất bại']);
            }

            foreach ($cartItems as $item) {
                if (!isset($item['idsach']) || !isset($item['soluong'])) {
                    $conn->rollBack();
                    return json_encode(['status' => 'error', 'message' => 'Dữ liệu giỏ hàng không hợp lệ']);
                }

                // Kiểm tra số lượng tồn kho
                $slTonKho = getSoLuongTonKho($item['idsach']);
                if ($slTonKho < $item['soluong']) {
                    $conn->rollBack();
                    return json_encode([
                        'status' => 'error',
                        'message' => "Sản phẩm ID {$item['idsach']} không đủ tồn kho (còn $slTonKho, yêu cầu {$item['soluong']})"
                    ]);
                }

                $sanpham = getDataSanPhamTheoId($item['idsach']);
                if (!$sanpham || !isset($sanpham['gia'])) {
                    $conn->rollBack();
                    return json_encode(['status' => 'error', 'message' => 'Không tìm thấy thông tin sản phẩm']);
                }
                $gia = $sanpham['gia'];

                $result = addChiTietHoaDon($idhoadon, $item['idsach'], $item['soluong'], $gia);
                if (!$result) {
                    $conn->rollBack();
                    return json_encode(['status' => 'error', 'message' => 'Lưu chi tiết hóa đơn thất bại']);
                }

                // Cập nhật số lượng tồn kho
                $updateTonKho = updateSoLuongTonKho($item['idsach'], $item['soluong']);
                if (!$updateTonKho) {
                    $conn->rollBack();
                    return json_encode([
                        'status' => 'error',
                        'message' => "Cập nhật tồn kho cho sản phẩm ID {$item['idsach']} thất bại"
                    ]);
                }
            }

            $checkxoagiohang = deleteAllSanPhamKhoiGioHangByIdKhachHang($idkhachhang);
            if (!$checkxoagiohang) {
                $conn->rollBack();
                return json_encode(['status' => 'error', 'message' => 'Lỗi xóa giỏ hàng của idkhachhang']);
            }

            $conn->commit();
            return json_encode([
                'status' => 'success',
                'message' => 'Tạo hóa đơn thành công',
                'idhoadon' => $idhoadon
            ]);
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Lỗi khi xử lý thanh toán: " . $e->getMessage());
            return json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        }
    }
}

$controller = new ThanhToanController();
$controller->handleRequest();
?>