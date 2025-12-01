<?php
include_once '../../model/connectDB.php';
include_once '../../model/phieunhap.php';

$idphieunhap = isset($_GET['idphieunhap']) ? $_GET['idphieunhap'] :null;
if($idphieunhap){
    $receipt = getDataPhieuNhapTheoID($idphieunhap);
    $itemreceipts = getDataChiTietPhieuNhapTheoID($idphieunhap);

}


?>

        <!-- Main Content -->
        <div class="right-content">
            <div id="tab6" class="tab-content active">
                <h2>Chi tiết Phiếu nhập</h2>
                <button type="button" class="close-button" onclick="location.href='../controller/index.php?pg=phieunhap'">X</button>
                <p><strong>ID phiếu nhập:</strong>
                    <input type="text" id="idphieunhap" readonly value="<?=$receipt['idphieunhap']?>">
                </p>
                <p><strong>Ngày nhập:</strong> 
                    <input type="text" id="idphieunhap" readonly value="<?=$receipt['ngaynhap']?>">
                </p>

                <p><strong>Nhân viên:</strong> 
                    <input type="text" id="idphieunhap" readonly value="<?=$receipt['idnhanvien']?>">
                </p>

                <p><strong>Nhà cung cấp:</strong>
                    <input type="text" id="idnhacungcap" readonly value="<?=$receipt['idnhacungcap']?>">
                </p>  
                <p><strong>Tổng tiền:</strong>
                    <input type="text" id="tongtien" readonly value="<?=$receipt['tongtien']?>">
                </p>
                <div class="ctpn-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Sách</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                                <th>Lợi nhuận (%)</th>
                            </tr>
                        </thead>
                        <tbody id="ctpnhap-body">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="../view/layout/css/ctphieunhap.css">
</body>
<script>
    
    const itemreceipts = <?= json_encode($itemreceipts)?>;
    console.log(itemreceipts);
    document.getElementById('ctpnhap-body').innerHTML = '';
    
    itemreceipts.forEach(item => {
        const row =
        `<tr>
            <td>${item.idsach}</td>
            <td>${item.soluong}</td>
            <td>${Number(item.gia).toLocaleString('vi-VN')}đ</td>
            <td>${item.loinhuan}</td>
        </tr>
        `;
        document.getElementById('ctpnhap-body').innerHTML += row;
    });

    window.onload = function() {
    const tongTien = document.getElementById('tongtien').value;
    
    // Chuyển đổi giá trị thành số và định dạng
    const formattedTongTien = formatCurrency(tongTien);
    
    document.getElementById('tongtien').value = formattedTongTien;
    };

    function formatCurrency(amount) {
        const number = parseFloat(amount);
        if (isNaN(number)) return amount;
        
        return number.toLocaleString('vi-VN') + 'đ';
    }
    
</script>

</html>
