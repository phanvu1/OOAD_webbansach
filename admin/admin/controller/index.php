<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: ../view/dangnhap.php"); // Chuyển đến trang đăng nhập
        exit();
    }

    if (!isset($_GET['search']) || !isset($_GET['pg'])) {
        include '../view/leftmenu.php'; 
    }

    // Lấy tham số 'pg' từ URL để xác định trang cần hiển thị
    $page = isset($_GET['pg']) ? $_GET['pg'] : null;  // Không có trang mặc định

    // Điều hướng đến các trang con dựa trên giá trị của 'pg'
    switch ($page) {
        case 'cuahang':
            include '../view/cuahang.php';
            break;
        case 'suacuahang':
            include '../view/suacuahang.php';
            break;
        case 'suachuyenkhoan':
            include '../view/suachuyenkhoan.php';
            break;
        case 'suabanner':
            include '../view/suabanner.php';
            break;
        case 'xoabanner':
            include '../view/xoabanner.php';
            break;
        case 'sanpham':
            include '../view/sanpham.php';
            break;
        case 'themsach':
            include '../view/themsanpham.php';
            break;
        case 'suasach':
            include '../view/suasanpham.php';
            break;
        case 'xoasach':
            include '../view/xoasach.php';
            break;
        case 'themtheloai':
            include '../view/themtheloai.php';
            break; 
        case 'suatheloai':
            include '../view/suatheloai.php';
            break;
        case 'xoatheloai':
            include '../view/xoatheloai.php';
            break;
        case 'themtacgia':
            include '../view/themtacgia.php';
            break;
        case 'suatacgia':
            include '../view/suatacgia.php';
            break;
        case 'xoatacgia':
            include '../view/xoatacgia.php';
            break;
        case 'themnhaxuatban':
            include '../view/themnhaxuatban.php';
            break;
        case 'suanhaxuatban':
            include '../view/suanhaxuatban.php';
            break;
        case 'xoanhaxuatban':
            include '../view/xoanhaxuatban.php';
            break;
        case 'themhinhanhsach':
            include '../view/themhinhanhsach.php';
            break;  
        case 'danhmuc':
            include '../view/danhmuc.php';
            break;
        case 'chitietdanhmuc':
            include '../view/chitietdanhmuc.php';
            break;
        case 'themdanhmuc':
            include '../view/themdanhmuc.php';
            break;
        case 'themctdanhmuc':
            include '../view/themctdanhmuc.php';
            break;
        case 'suadanhmuc':
            include '../view/suadanhmuc.php';
            break;
        case 'xoadanhmuc':
            include '../view/xoadanhmuc.php';
            break;
        case 'nhanvien':
            include '../view/nhanvien.php';
            break;
        case 'themnhanvien':
            include '../view/themnhanvien.php';
            break;
        case 'suanhanvien':
            include '../view/suanhanvien.php';
            break;
        case 'xoanhanvien':
            include '../view/xoanhanvien.php';
            break;
        case 'khachhang':
            include '../view/khachhang.php';
            break;
        case 'themkhachhang':
            include '../view/themkhachhang.php';
            break;
        case 'suakhachhang':
            include '../view/suakhachhang.php';
            break;
        case 'xoakhachhang':
            include '../view/xoakhachhang.php';
            break;
        case 'khoakhachhang':
            include '../view/khoakhachhang.php';
            break;
        case 'nhacungcap':
            include '../view/nhacungcap.php';
            break;
        case 'themnhacungcap':
            include '../view/themnhacungcap.php';
            break;
        case 'suanhacungcap':
            include '../view/suanhacungcap.php';
            break;
        case 'xoanhacungcap':
            include '../view/xoanhacungcap.php';
            break;
        case 'hoadon':
            include '../view/hoadon.php';
            break;
        case 'xetduyetHD':
            include '../view/xetduyetHD.php';
            break;
        case 'phieunhap':
            include '../view/phieunhap.php';
            break;
        case 'themphieunhap':
            include '../view/themphieunhap.php';
            break;
        case 'chitietphieunhap':
            include '../view/chitietphieunhap.php';
            break;
        case 'xoaphieunhap':
            include '../view/xoaphieunhap.php';
            break;
        case 'thongke':
            include '../view/thongke.php';
            break;
        case 'taikhoan':
            include '../view/taikhoan.php';
            break;
        case 'themtaikhoan':
            include '../view/themtaikhoan.php';
            break;
        case 'suataikhoan':
            include '../view/suataikhoan.php';
            break;
        case 'xoataikhoan':
            include '../view/xoataikhoan.php';
            break;
        case 'phanquyen':
            include '../view/phanquyen.php';
            break;
        case 'dangxuat':
            // Xóa các biến session cụ thể
            unset($_SESSION['loggedin']);
            unset($_SESSION['username-NV']);
            header("Location: ../view/dangnhap.php");
            exit();
        default:
            header("Location: ../view/dangnhap.php");
            exit();
    }
?>
