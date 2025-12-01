<?php
// //trả về danh sách json 
// require_once '../../model/connectDB.php';
// require_once '../../model/phanquyen.php';

// $page = isset($_GET['page']) ? (int)$_GET['page']: 1;
// if($page < 1) $page =1;
// $limit = 5;
// $result = getDataPhanQuyen($page, $limit);

// echo json_encode(value: [
//     'data' => $result['data'],
//     'currentPage' =>$result['currentPage'],
//     'totalPages'=> $result['totalPages']
// ]);
// exit;

    include_once '../../model/connectDB.php';
    include_once '../../model/phanquyen.php';

    $keyword = isset($_GET['search']) ? $_GET['search'] : '';
    $quyenData = getDataQuyen();
    $message = '';

    // Nếu có từ khóa tìm kiếm, thực hiện tìm kiếm
    if ($keyword) {
        $quyenData = searchPhanQuyen($keyword);
    } else {
        $quyenData = getDataQuyen();
    }

    // Sửa quyền
    if (isset($_POST['update_quyen'])) {
        $tenquyen = $_POST['quyen'];
        // Kiểm tra checkbox: nếu không tick thì không tồn tại => gán 0
        $quyen = [
            'QLCuaHang' => isset($_POST['QLCuaHang']) ? 1 : 0,
            'QLSanPham' => isset($_POST['QLSanPham']) ? 1 : 0,
            'QLDanhMuc' => isset($_POST['QLDanhMuc']) ? 1 : 0,
            'QLNhanVien' => isset($_POST['QLNhanVien']) ? 1 : 0,
            'QLKhachHang' => isset($_POST['QLKhachHang']) ? 1 : 0,
            'QLNhaCungCap' => isset($_POST['QLNhaCungCap']) ? 1 : 0,
            'QLDonHang' => isset($_POST['QLDonHang']) ? 1 : 0,
            'QLPhieuNhap' => isset($_POST['QLPhieuNhap']) ? 1 : 0,
            'QLThongke' => isset($_POST['QLThongke']) ? 1 : 0,
            'QLTaiKhoan' => isset($_POST['QLTaiKhoan']) ? 1 : 0,
            'QLPhanQuyen' => isset($_POST['QLPhanQuyen']) ? 1 : 0
        ];

        updateQuyen($tenquyen, $quyen);
        echo "<script>alert('Cập nhật quyền thành công!');</script>";
        $quyenData = getDataQuyen();
    }

    // Xóa quyền
    if (isset($_POST['delete_quyen'])) {
        $tenQuyen = $_POST['quyen'];

        // Kiểm tra xem quyền hiện tại có tất cả chức năng = 0 chưa
        $currentQuyen = getQuyenByName($tenQuyen); // viết hàm này để lấy dữ liệu theo tên

        $allZero = true;
        foreach ($currentQuyen as $key => $value) {
            if (strpos($key, 'QL') === 0 && $value == 1) {
                $allZero = false;
                break;
            }
        }

        if ($allZero) {
            // Nếu tất cả quyền đã = 0 => xóa quyền ra khỏi DB
            deleteQuyen($tenQuyen); // viết thêm hàm này trong model
            echo "<script>alert('Đã xóa nhóm quyền \"$tenQuyen\" khỏi hệ thống.');</script>";
        } else {
            // Nếu còn quyền nào = 1 thì reset tất cả về 0
            $Quyen = [
                'QLCuaHang' => 0,
                'QLSanPham' => 0,
                'QLDanhMuc' => 0,
                'QLNhanVien' => 0,
                'QLKhachHang' => 0,
                'QLNhaCungCap' => 0,
                'QLDonHang' => 0,
                'QLPhieuNhap' => 0,
                'QLThongke' => 0,
                'QLTaiKhoan' => 0,
                'QLPhanQuyen' => 0
            ];

            updateQuyen($tenQuyen, $Quyen);
            echo "<script>alert('Đã xóa tất cả chức năng (set về 0) cho nhóm quyền: " . htmlspecialchars($tenQuyen) . "');</script>";
        }

        $quyenData = getDataQuyen();
    }

    // Thêm mới tên quyền
    if (isset($_POST['add_quyen'])) {
        $Quyen = trim($_POST['ten_quyen']);
    
        // Kiểm tra tên quyền đã tồn tại chưa
        $exist = false;
        foreach ($quyenData as $row) {
            if (strtolower($row['Quyen']) == strtolower($Quyen)) {
                $exist = true;
                break;
            }
        }
    
        if ($exist) {
            echo "<script>alert('Tên nhóm quyền đã tồn tại!');</script>";
        } else {
            // Gán tất cả quyền QL = 0
            $q = [
                'QLCuaHang' => 0,
                'QLSanPham' => 0,
                'QLDanhMuc' => 0,
                'QLNhanVien' => 0,
                'QLKhachHang' => 0,
                'QLNhaCungCap' => 0,
                'QLDonHang' => 0,
                'QLPhieuNhap' => 0,
                'QLThongke' => 0,
                'QLTaiKhoan' => 0,
                'QLPhanQuyen' => 0,
            ];
    
            addQuyen($Quyen, $q);
            echo "<script>alert('Thêm nhóm quyền thành công!');</script>";
            $quyenData = getDataQuyen();
        }
    }    
?>
