<?php
session_start();
ob_start();

include_once "../../model/connectDB.php";
include_once "../../model/dangnhap.php";
include_once "../../model/thongtin.php";
include_once "../../model/banner.php";
include_once "../../model/BookModel.php";
include_once "../../model/giohang.php";
include_once "../../model/hoadon.php";
include_once "../../model/register.php";
include_once "../../model/taikhoanUser.php";
include_once "../../model/SearchModel.php"; 
include_once "BookController.php";

include '../view/header.php';

$banners = getAllBanners();
$thongtin = getThongTin();
$page = isset($_GET['pg']) ? htmlspecialchars($_GET['pg']) : null;
$conn = connectdb();

switch ($page) {
    case 'sanpham':
        include '../view/menu.php';
        include '../view/san-pham.php';
        break;

    case 'chitietsp':
        include '../view/menu.php';
        $controller = new BookController();
        if (isset($_POST['add_to_cart']) && isset($_SESSION['idkhachhang'])) {
            $bookId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            $idkhachhang = $_SESSION['idkhachhang'];
            $stmt = $conn->prepare("SELECT sltonkho FROM sach WHERE idsach = ? AND trangthai = 1");
            $stmt->execute([$bookId]);
            $book = $stmt->fetch(PDO::FETCH_OBJ);
            if ($book && $book->sltonkho >= $quantity) {
                $stmt = $conn->prepare("
                    INSERT INTO giohang (idkhachhang, idsach, soluong, trangthai)
                    VALUES (?, ?, ?, 1)
                    ON DUPLICATE KEY UPDATE soluong = soluong + ?
                ");
                $stmt->execute([$idkhachhang, $bookId, $quantity, $quantity]);
                echo '<script>alert("Thêm vào giỏ hàng thành công!"); window.location.href = "index.php?pg=giohang";</script>';
            } else {
                echo '<script>alert("Số lượng vượt quá tồn kho hoặc sách không tồn tại!"); window.location.href = "index.php?pg=chitietsp&id=' . $bookId . '";</script>';
            }
        } elseif (isset($_POST['add_to_cart']) && !isset($_SESSION['idkhachhang'])) {
            echo '<script>alert("Vui lòng đăng nhập để thêm vào giỏ hàng!"); window.location.href = "index.php?pg=dangnhap";</script>';
        }
        $controller->detail();
        break;

    case 'dangnhap':
        if (isset($_POST['login']) && $_POST['login']) {
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);
            $user_info = checktaikhoanKH($username, $password);
            if ($user_info && isset($user_info['idkhachhang'])) {
                $_SESSION['username'] = $username;
                $_SESSION['idkhachhang'] = $user_info['idkhachhang'];
                echo '<script>alert("Đăng nhập thành công!"); window.location.href = "index.php";</script>';
                exit();
            } else {
                $error_message = $user_info;
            }
        }
        include '../view/menu.php';
        include_once "../view/login.php";
        break;

    case 'dangki':
        include '../view/menu.php';
        include '../view/register.php';
        break;

    case 'donhang':
        if (isset($_SESSION['idkhachhang'])) {
            $khachhang = $_SESSION['idkhachhang'];
            if (countOrdersByCustomerId($khachhang) > 0) {
                include '../view/lichsu-muahang.php';
            } else {
                include '../view/don-hang-rong.php';
            }
        } else {
            echo '<script>alert("Bạn cần đăng nhập để xem đơn hàng!"); window.location.href = "index.php?pg=dangnhap";</script>';
        }
        break;

    case 'thanhtoan':
        if (isset($_SESSION['idkhachhang'])) {
            $khachhang = $_SESSION['idkhachhang'];
            if (countSanPhamTrongGioHang($khachhang) > 0) {
                include '../view/thanh-toan.php';
            } else {
                echo '<script>alert("Giỏ hàng đang trống! Hãy mua thêm hàng nhé"); window.location.href = "index.php";</script>';
            }
        } else {
            echo '<script>alert("Bạn cần đăng nhập để thanh toán!"); window.location.href = "index.php?pg=dangnhap";</script>';
        }
        break;

    case 'giohang':
        if (isset($_SESSION['idkhachhang'])) {
            $khachhang = $_SESSION['idkhachhang'];
            if (countSanPhamTrongGioHang($khachhang) == 0) {
                include '../view/gio-hang-rong.php';
            } else {
                include '../view/giohang.php';
            }
        } else {
            echo '<script>alert("Bạn cần đăng nhập để xem giỏ hàng!"); window.location.href = "index.php?pg=dangnhap";</script>';
        }
        break;

    case 'thongtintaikhoan':
        if (isset($_SESSION['idkhachhang'])) {
            $user = get_user_by_id($_SESSION['idkhachhang']);
            include '../view/thongtinTK.php';
        } else {
            echo '<script>alert("Bạn cần đăng nhập để xem thông tin tài khoản!"); window.location.href = "index.php?pg=dangnhap";</script>';
        }
        break;

    case 'logout':
        if (isset($_GET['confirm']) && $_GET['confirm'] === 'true') {
            unset($_SESSION['login']);
            unset($_SESSION['username']);
            unset($_SESSION['idkhachhang']);
            echo '<script>window.location.href = "index.php";</script>';
            exit();
        } else {
            echo '<script>
                if (confirm("Bạn có chắc chắn muốn đăng xuất không?")) {
                    window.location.href = "index.php?pg=logout&confirm=true";
                } else {
                    window.location.href = "index.php";
                }
            </script>';
        }
        break;

    case 'timkiem':
        include '../view/menu.php';
        $searchModel = new SearchModel();
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $category = isset($_GET['category']) && $_GET['category'] !== 'all' ? (int)$_GET['category'] : null;
        $min_price = isset($_GET['min-price']) && is_numeric($_GET['min-price']) ? (float)$_GET['min-price'] : null;
        $max_price = isset($_GET['max-price']) && is_numeric($_GET['max-price']) ? (float)$_GET['max-price'] : null;
        $result = $searchModel->searchBooks($keyword, $category, $min_price, $max_price);
        $books = $result['data'];
        $total_results = $result['total_results'];
        include '../view/tim-kiem.php';
        break;

    default:
        include '../view/menu.php';
        include '../view/banner.php';
        include '../view/index.php';
        break;
}

include '../view/thong-bao.php';
include '../view/footer.php';
ob_end_flush();
?>