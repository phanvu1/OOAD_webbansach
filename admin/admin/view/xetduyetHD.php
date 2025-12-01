<?php
    include_once '../../model/connectDB.php';
    include_once '../../model/hoadon.php';
    include_once '../../model/chitiethoadon.php';
    include_once '../../model/thongtinnhanhang.php';
    include_once '../../model/sanpham.php';

    $idHD = isset($_GET['idhoadon']) ? $_GET['idhoadon']: null;
    $idnhanvien = (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['idnhanvien'])) ? $_SESSION['idnhanvien'] : null;    echo "<script>console.log('ID Nhân viên: " . $idnhanvien . "');</script>";
    if($idHD) {
        $hoadon = getDataHoaDonByID($idHD);
        $cthds = getCTHoaDonByIDHoaDon($idHD);
        $diachi = getDataThongTinNhanHangByID($hoadon['iddiachi']);

        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $trangthai = $_POST['trangthai'];
           
            $result = updateHoaDonById($idHD, $idnhanvien, $trangthai);
            if($result){
                if($trangthai == '3'){
                    foreach ($cthds as $cthd){
                        $idsach = $cthd['idsach'];
                        $slsachgoc = getDataSanPhamTheoId($idsach);
                        $slban = $cthd['soluong'];
                        $slconlai = $slsachgoc['sltonkho'] + $slban;
                        updateSlTonKhoTheoId($idsach, $slconlai);
                    }
                }
                echo "<script>
                alert('Cập nhật thông tin hóa đơn thành công!');
                window.location.href = '../controller/index.php?pg=hoadon';
                </script>";
            }else{
                echo "<script>alert('Lỗi cập nhật đơn hàng!')</script>";
            }
        }
    }

    $quayVe = (isset($_GET['from']) && $_GET['from'] === 'thongke') 
    ? '../controller/index.php?pg=thongke' 
    : '../controller/index.php?pg=hoadon';
?>


        <div class="container">
            <form action="../controller/index.php?pg=xetduyetHD&idhoadon=<?=$hoadon['idhoadon']?>" method="post">
                    <h2>Chi Tiết Đơn Hàng</h2>
                    <button type="button" class="close-button" onclick="location.href='<?=$quayVe?>'">X</button>
                    <div class="form-grid">
                        <div class="form-column">
                            <div class="form-group">
                                <label>Mã hóa đơn</label>
                                <input type="text" value="<?=$hoadon['idhoadon']?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Phương thức thanh toán</label>
                                <input type="text" value="<?=$hoadon['phuongthuctt']?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Số điện thoại</label>
                                <input type="text" value="<?=$diachi['sdtNgNhan']?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" value="<?=$diachi['emailNgNhan']?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="status"><b>Trạng thái</b></label>
                                <select name="trangthai" >
                                    <?php if ($hoadon['trangthai'] == 0) {  ?>
                                    <option value="0" <?=$hoadon['trangthai'] ==0 ? 'selected' : ''?>>Chờ xác nhận</option>
                                    <option value="1" <?=$hoadon['trangthai'] ==1 ? 'selected' : ''?>>Xác nhận</option>
                                    <option value="2" <?=$hoadon['trangthai'] ==2 ? 'selected' : ''?>>Đã giao thành công</option>
                                    <option value="3" <?=$hoadon['trangthai'] ==3 ? 'selected' : ''?>>Đã hủy</option>
                                    <?php }else if($hoadon['trangthai'] == 1){?>
                                    <option value="1" <?=$hoadon['trangthai'] ==1 ? 'selected' : ''?>>Xác nhận</option>
                                    <option value="2" <?=$hoadon['trangthai'] ==2 ? 'selected' : ''?>>Đã giao thành công</option>
                                    <option value="3" <?=$hoadon['trangthai'] ==3 ? 'selected' : ''?>>Đã hủy</option>
                                    <?php }else if($hoadon['trangthai'] == 2) { ?>
                                        <option value="2" <?=$hoadon['trangthai'] ==2 ? 'selected' : ''?>>Đã giao thành công</option>
                                    <?php }else if($hoadon['trangthai'] == 3) { ?>
                                        <option value="3" <?=$hoadon['trangthai'] ==3 ? 'selected' : ''?>>Đã hủy</option>
                                    <?php } ?>


                                </select>
                            </div>
                        </div>

                        <div class="form-column">
                            <div class="form-group">
                                <label>Mã khách hàng</label>
                                <input type="text" value="<?=$hoadon['idkhachhang']?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Người nhận</label>
                                <input type="text" value="<?=$diachi['hotenNgNhan']?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Ngày xuất</label>
                                <input type="text" value="<?=$hoadon['ngayxuat']?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Tổng tiền</label>
                                <input type="text" value="<?= number_format($hoadon['tongtien'], 0, '.', '.') . ' đ' ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Địa chỉ nhận hàng</label>
                                <input type="text" value="<?=$diachi['diachi_chitiet']?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="scroll-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên sách</th>
                                    <th>Tác giả</th>
                                    <th>Nhà xuất bản</th>
                                    <th>Danh mục</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($cthds as $cthd): ?>
                                    <tr>
                                        <td><?=$cthd['idsach']?></td>
                                        <td><?=$cthd['tensach']?></td>
                                        <td><?=$cthd['idtacgia']?></td>
                                        <td><?=$cthd['idnhaxuatban']?></td>
                                        <td><?=$cthd['idctdanhmuc']?></td>
                                        <td><?=$cthd['soluong']?></td>
                                        <td><?= number_format($cthd['gia'], 0, '.', '.') ?> đ</td>
                                        <td><?= number_format($cthd['thanhtien'], 0, '.', '.') ?> đ</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn">Xét duyệt</button>
                    </div>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="../view/layout/css/form.css">
    <link rel="stylesheet" href="../view/layout/css/xetduyethoadon.css">
</body>
</html>
