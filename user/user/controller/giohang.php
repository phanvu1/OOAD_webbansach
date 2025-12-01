<?php
require_once "../../model/connectDB.php";
require_once "../../model/giohang.php";
require_once "../../model/sanpham.php";

header('Content-Type: application/json');

class GioHangController {
    public function handleRequest() {
        $action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : 'hienThi');
        $idKhachHang = isset($_POST['idKhachHang']) ? $_POST['idKhachHang'] : (isset($_GET['idKhachHang']) ? $_GET['idKhachHang'] : null);

        switch ($action) {
            case 'hienThi':
                echo $this->hienThiSanPhamKhachHangTrongGioHang($idKhachHang);
                break;
            case 'them':
                $idSach = isset($_POST['idSach']) ? $_POST['idSach'] : (isset($_GET['idSach']) ? $_GET['idSach'] : null);
                $soLuong = isset($_POST['soLuong']) ? $_POST['soLuong'] : (isset($_GET['soLuong']) ? $_GET['soLuong'] : 1);
                echo $this->themSanPhamVaoGioHang($idKhachHang, $idSach, $soLuong);
                break;
            case 'xoa':
                $idGioHang = isset($_POST['idGioHang']) ? $_POST['idGioHang'] : (isset($_GET['idGioHang']) ? $_GET['idGioHang'] : null);
                echo $this->xoaSanPhamKhoiGioHang($idGioHang);
                break;
            case 'capNhat':
                $idSach = isset($_POST['idSach']) ? $_POST['idSach'] : (isset($_GET['idSach']) ? $_GET['idSach'] : null);
                $idGioHang = isset($_POST['idGioHang']) ? $_POST['idGioHang'] : (isset($_GET['idGioHang']) ? $_GET['idGioHang'] : null);
                $soLuongMoi = isset($_POST['soLuong']) ? $_POST['soLuong'] : (isset($_GET['soLuong']) ? $_GET['soLuong'] : null);
                echo $this->capNhatSoLuongSanPham($idGioHang, $soLuongMoi, $idSach);
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'Action không hợp lệ']);
        }
    }

    private function hienThiSanPhamKhachHangTrongGioHang($idKhachHang) {
        if ($idKhachHang === null) {
            return json_encode([
                'status' => 'error',
                'message' => 'idKhachHang is required'
            ]);
        }

        $gioHangItems = getAllSanPhamTrongGioHangID($idKhachHang);

        $result = [
            'status' => 'success',
            'data' => []
        ];

        if ($gioHangItems && !empty($gioHangItems)) {
            foreach ($gioHangItems as $item) {
                $sanPham = getDataSanPhamTheoId($item['idsach']);
                
                if (!$sanPham) {
                    deleteSanPhamKhoiGioHang($item['idgiohang']);
                    continue;
                }

                // Kiểm tra số lượng tồn kho
                $idSach = $item['idsach'];
                $soLuong = $item['soluong'];
                $slTonKho = $sanPham['sltonkho'];

                if ($slTonKho < $soLuong) {
                    if ($slTonKho > 0) {
                        updateSoLuongSanPham($item['idgiohang'], $slTonKho);
                        $item['soluong'] = $slTonKho;
                    } else {
                        deleteSanPhamKhoiGioHang($item['idgiohang']);
                        continue;
                    }
                }

                $result['data'][] = [
                    'idgiohang' => $item['idgiohang'],
                    'idkhachhang' => $item['idkhachhang'],
                    'idsach' => $item['idsach'],
                    'soluong' => $item['soluong'],
                    'trangthai' => $item['trangthai'],
                    'tensach' => $sanPham['tensach'],
                    'anhbia' => $sanPham['anhbia'],
                    'gia' => $sanPham['gia']
                ];
            }
        } else {
            $result['status'] = 'error';
            $result['message'] = 'No data found';
        }

        return json_encode($result);
    }
    private function themSanPhamVaoGioHang($idKhachHang, $idSach, $soLuong) {
        if ($idKhachHang === null || $idSach === null) {
            return json_encode([
                'status' => 'error',
                'message' => 'Thiếu thông tin bắt buộc'
            ]);
        }

        // Kiểm tra số lượng tồn kho
        if (!checkSoLuongSanPhamTrongGioVoiTonKho($idSach, $soLuong)) {

            return json_encode([
                'status' => 'error',
                'message' => 'Số lượng yêu cầu vượt quá tồn kho'
            ]);
        }

        $result = addSanPhamVaoGioHang($idKhachHang, $idSach, $soLuong);
        return json_encode([
            'status' => $result ? 'success' : 'error',
            'message' => $result ? 'Thêm sản phẩm thành công' : 'Thêm sản phẩm thất bại'
        ]);
    }

    private function xoaSanPhamKhoiGioHang($idGioHang) {
        if ($idGioHang === null) {
            return json_encode([
                'status' => 'error',
                'message' => 'idGioHang is required'
            ]);
        }

        $result = deleteSanPhamKhoiGioHang($idGioHang);
        return json_encode([
            'status' => $result ? 'success' : 'error',
            'message' => $result ? 'Xóa sản phẩm thành công' : 'Xóa sản phẩm thất bại'
        ]);
    }

    private function capNhatSoLuongSanPham($idGioHang, $soLuongMoi, $idSach) {
        if ($idGioHang === null || $soLuongMoi === null) {
            return json_encode([
                'status' => 'error',
                'message' => 'Thiếu thông tin bắt buộc'
            ]);
        }
        if (!checkSoLuongSanPhamTrongGioVoiTonKho($idSach, $soLuongMoi)) {
            return json_encode([
                'status' => 'error',
                'message' => 'Số lượng yêu cầu vượt quá tồn kho'
            ]);
        }
        $result = updateSoLuongSanPham($idGioHang, $soLuongMoi);
        return json_encode([
            'status' => $result ? 'success' : 'error',
            'message' => $result ? 'Cập nhật số lượng thành công' : 'Cập nhật số lượng thất bại'
        ]);
    }
}

// Khởi tạo và xử lý
$controller = new GioHangController();
$controller->handleRequest();
?>