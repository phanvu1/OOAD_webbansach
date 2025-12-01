<article class="container block-content block-content-news">
            <div class="flex-item">
                <aside class="block-main-content">
                    <div class="box-block-main-content">
                        <h1 class="text-title title-don-hang">Đơn hàng</h1>
                        <div class="block-don-hang">
                            <div class="block-left-don-hang">
                                <h2 class="text-title-dat-hang">Thông tin nhận hàng</h2>
                                <div class="container-info-nhanhang">
                                    <p>Tên người nhận : <span id="receiver-name"></span></p>
                                    <p>SĐT : <span id="receiver-phone"></span></p>
                                    <p>Địa chỉ : <span id="receiver-address"></span></p>
                                    <div class="macdinh" id="default3" style="display: none;">Mặc định</div>
                                    <div class="address-diff" id="diff-addr">Chọn địa chỉ khác</div>
                                </div>

                                <!-- Phần danh sách địa chỉ -->
                                <div id="container-address">
                                    <div class="text-title-address">Địa chỉ nhận hàng <span class="close">X</span></div>
                                    <form action="" method="POST" id="address-form">
                                        <div id="address-list">
                                            <!-- Danh sách địa chỉ sẽ được thêm động bằng JavaScript -->
                                        </div>
                                        <div class="set-default-container">
                                            <input type="checkbox" id="set-default"> <label for="set-default">Đặt làm mặc định</label>
                                        </div>
                                        <div class="btn-add" id="address-new">+ Thêm địa chỉ mới</div>
                                    </form>
                                </div>
                            </div>

                            <div class="block-right-thanh-toan">
                                <h2 class="text-title-dat-hang">Phương thức thanh toán</h2>
                                <div class="payment-options">
                                    <label><input type="radio" name="payment" value="cod" id="pay-on-delivery"> Thanh
                                        toán khi nhận
                                        hàng</label>
                                    <label><input type="radio" name="payment" value="bank" id="bank-transfer"> Chuyển
                                        khoản ngân
                                        hàng</label>
                                </div>


                                <!-- Form sửa địa chỉ -->
                                <div class="them-address" id="edit-address">
                                    <div class="text-title-address">Sửa địa chỉ</div>
                                    <div class="form-sua">
                                        <form id="edit-address-form">
                                            <input type="hidden" id="edit-iddiachi" name="iddiachi">
                                            <div class="form-item">
                                                <label for="full-name1">Tên người nhận</label>
                                                <input type="text" id="full-name1" name="full_name" required>
                                            </div>
                                            <div class="form-item">
                                                <label for="phone1">Số điện thoại</label>
                                                <input type="tel" id="phone1" name="phone" required>
                                            </div>
                                            <div class="form-item">
                                                <label for="edit-address">Địa chỉ</label>
                                                <select id="city1" name="city" required>
                                                    <option value="">Chọn thành phố</option>
                                                    <!-- Dữ liệu thành phố sẽ được tải động -->
                                                </select>
                                                <select id="district1" name="district" required>
                                                    <option value="">Chọn quận/huyện</option>
                                                </select>
                                                <select id="ward1" name="ward" required>
                                                    <option value="">Chọn phường/xã</option>
                                                </select>
                                                <textarea id="text1" name="diachi_chitiet" rows="3" placeholder="Nhập địa chỉ nhà, thông tin ghi chú,...(*)" required></textarea>
                                            </div>
                                            <div class="form-actions">
                                                <button type="button" class="cancel-btn" id="btn-huy1">Hủy</button>
                                                <button type="submit" class="save-btn" id="btn-luu1">Lưu</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="them-address" id="add-address">
                                    <div class="text-title-address">Thêm địa chỉ</div>
                                    <div class="form-sua">
                                        <form id="add-address-form">
                                            <div class="form-item">
                                                <label for="full-name">Tên người nhận</label>
                                                <input type="text" id="full-name" name="full_name" required>
                                            </div>
                                            <div class="form-item">
                                                <label for="phone">Số điện thoại</label>
                                                <input type="tel" id="phone" name="phone" required>
                                            </div>
                                            <div class="form-item">
                                                <label for="edit-address">Địa chỉ</label>
                                                <select id="city" name="city" required>
                                                    <option value="">Chọn thành phố</option>
                                                </select>
                                                <select id="district" name="district" required>
                                                    <option value="">Chọn quận/huyện</option>
                                                </select>
                                                <select id="ward" name="ward" required>
                                                    <option value="">Chọn phường/xã</option>
                                                </select>
                                                <textarea id="text" name="diachi_chitiet" rows="3" placeholder="Nhập địa chỉ nhà, thông tin ghi chú,...(*)" required></textarea>
                                            </div>
                                            <div class="form-actions">
                                                <button type="button" class="cancel-btn" id="btn-huy">Hủy</button>
                                                <button type="submit" class="save-btn" id="btn-luu">Lưu</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="payment-container" id="infor-bank">

                                    <h1 class="h1-payment">Thông tin thanh toán
                                        <span class="closeform"></span>
                                    </h1>

                                    <div class="payment-info">
                                        <p> <span>Ngân hàng: </span></i><?php echo $thongtin['tenNH']; ?></p>
                                        <p><span>Số tài khoản: </span></i><?php echo $thongtin['stk']; ?></p>
                                        <p> <span>Chủ tài khoản: </span> </i><?php echo $thongtin['tenChuTK']; ?></p>
                                        <p> <span>Nội dung chuyển khoản :</span> UniBook</p>
                                    </div>
                                    <div class="border"></div>
                                    <h2 class="labelquetQr">Hoặc quét mã Qr : </h2>
                                    <div class="qrcode">
                                        <img id="qr-image" src="../../<?php echo $thongtin['anhQrCk']; ?>" alt="QR Code">
                                    </div>
                                </div>
                            </div>

                            <div class="block-right-don-hang">
                                <h2 class="text-title-dat-hang">Thông tin đơn hàng <span id="total-products">(0 sản phẩm)</span></h2>
                                <div class="block-cart-buy block-cart-buy-show">
                                    <div class="block-card-scroll" id="order-items-container">
                                        <!-- Dữ liệu sẽ được thêm động bằng JavaScript -->
                                    </div>

                                    <div class="total-price-cart" style="justify-content: flex-end;">
                                        <p><b>Tổng tiền:</b> <span id="total-order-price">0</span></p>
                                    </div>
                                </div>

                                <div class="box-item-dat-hang-full btn-dat-hang">
                                    <button class="dataModal" style="display:block" id="button-cod" data-id="modal-dat-hang-thanh-cong">Đặt hàng</button>
                                    <button style="display:none" id="button-bank">Thanh toán</button>
                                </div>
                            </div>
                            </div>

                        </div>
                    </div>
                </aside>
            </div>
            <div class="block-opacity"></div>

<!-- Modal Đặt hàng thành công -->
    <div class="block-modal block-modal-complete-cart" id="modal-dat-hang-thanh-cong" style="display:none;">
        <h1>Thông báo
            <span class="closepopup"></span>
        </h1>
        <div class="box-descript-modal">
            <h2>Cảm ơn bạn đã mua hàng tại Unibook!</h2>
            <h2>Chúng tôi đã tiếp nhận đơn hàng của bạn với:</h2>
            <ul>
                <li><b>Mã hóa đơn là: </b></li>
                <li><b>Tên người nhận:</b></li>
                <li><b>SĐT nhận hàng:</b></li>
                <li><b>Địa chỉ nhận hàng:</b></li>
                <li><b>Hình thức thanh toán: </b></li>
            </ul>
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Giá tiền</th>
                        <th>Tổng tiền</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="4">Tổng số tiền</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <h4><a href="../controller/index.php"><i class="icon-arrow-left"></i>Về trang chủ</a></h4>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var idKhachHang = <?php echo isset($_SESSION['idkhachhang']) ? $_SESSION['idkhachhang'] : 'null'; ?>;
    var defaultAddressId = null; 

    // Kiểm tra đăng nhập
    if (idKhachHang === null) {
        alert('Vui lòng đăng nhập để tiếp tục thanh toán.');
        window.location.href = '../controller/index.php?pg=dangnhap';
        return;
    }

    loadDefaultAddress();

    $('#diff-addr').click(function() {
        $('#container-address').show();
        loadAddressList();
    });

    $('#container-address .close').click(function() {
        $('#container-address').hide();
    });

    // Khi chọn địa chỉ
    $(document).on('change', 'input[name="shipping_address"]', function() {
        let idDiaChi = $(this).val();
        updateSelectedAddress(idDiaChi);
    });

    // Khi nhấn "Đặt làm mặc định"
    $('#set-default').change(function() {
        if ($(this).is(':checked')) {
            let idDiaChi = $('input[name="shipping_address"]:checked').val();
            if (idDiaChi) {
                setDefaultAddress(idDiaChi);
            }
        }
    });

    // Khi nhấn "Sửa" địa chỉ
    $(document).on('click', '.sua:not(.delete-address)', function() {
        let idDiaChi = $(this).closest('.item-diachi').find('input[name="shipping_address"]').val();
        loadEditAddressForm(idDiaChi);
        $('#edit-address').show();
    });

    // Khi nhấn "Xóa" địa chỉ
    $(document).on('click', '.delete-address', function() {
        let idDiaChi = $(this).data('id');
        if (confirm('Bạn có chắc muốn xóa địa chỉ này?')) {
            deleteAddress(idDiaChi);
        }
    });

    // Hủy sửa địa chỉ
    $('#btn-huy1').click(function() {
        $('#edit-address').hide();
    });

    // Lưu sửa địa chỉ
    $('#edit-address-form').submit(function(e) {
        e.preventDefault();
        let idDiaChi = $('#edit-iddiachi').val();
        let data = {
            iddiachi: idDiaChi,
            hotenNgNhan: $('#full-name1').val(),
            sdtNgNhan: $('#phone1').val(),
            thanhpho: $('#city1').val(),
            huyen: $('#district1').val(),
            xa: $('#ward1').val(),
            diachi_chitiet: $('#text1').val(),
            emailNgNhan: ''
        };
        updateAddress(data);
    });

    // Khi nhấn "Thêm địa chỉ mới"
    $('#address-new').click(function() {
        $('#add-address').show();
        loadCities('', '', ''); // Tải dropdown trống cho form thêm mới
        $('#full-name').val('');
        $('#phone').val('');
        $('#text').val('');
    });

    // Hủy thêm địa chỉ
    $('#btn-huy').click(function() {
        $('#add-address').hide();
    });

    // Lưu thêm địa chỉ
    $('#add-address-form').submit(function(e) {
        e.preventDefault();
        let data = {
            idkhachhang: idKhachHang,
            hotenNgNhan: $('#full-name').val(),
            sdtNgNhan: $('#phone').val(),
            thanhpho: $('#city').val(),
            huyen: $('#district').val(),
            xa: $('#ward').val(),
            diachi_chitiet: $('#text').val(),
            emailNgNhan: ''
        };
        addAddress(data);
    });

   // Nút đặt hàng
   $('#button-cod').click(function(e) {
        e.preventDefault();

        let idDiaChi = $('input[name="shipping_address"]:checked').val();
        if (!idDiaChi) {
            if (!defaultAddressId) {
                $('.block-opacity').hide();
                $('.block-opacity-modal').removeClass('block-opacity-modal-active').css('z-index', -1);
                alert('Vui lòng chọn địa chỉ giao hàng!');
                return;
            }
            idDiaChi = defaultAddressId;
        }

        let phuongThucTT = $('input[name="payment"]:checked').val();
        if (!phuongThucTT) {
            $('.block-opacity').hide();
            $('.block-opacity-modal').removeClass('block-opacity-modal-active').css('z-index', -1);
            alert('Vui lòng chọn phương thức thanh toán!');
            return;
        }
        phuongThucTT = phuongThucTT === 'cod' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản ngân hàng';

        let tongtienText = $('#total-order-price').text().replace(' VND', '');
        let tongtien = parseFloat(tongtienText);
        if (isNaN(tongtien) || tongtien <= 0) {
            $('.block-opacity').hide();
            $('.block-opacity-modal').removeClass('block-opacity-modal-active').css('z-index', -1);
            alert('Tổng tiền không hợp lệ! Vui lòng kiểm tra giỏ hàng.');
            return;
        }

        let data = {
            idkhachhang: idKhachHang,
            iddiachi: idDiaChi,
            phuongthuctt: phuongThucTT,
            ngayxuat: '<?php echo date('Y-m-d'); ?>',
            tongtien: tongtien,
            trangthai: 0
        };

        placeOrder(data);
    });

    function placeOrder(data) {
        $.ajax({
            url: '../controller/thanhtoan.php?action=placeOrder',
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                console.log('Phản hồi từ server:', response);
                if (response.status === 'success') {
                    // Lấy thông tin hóa đơn và chi tiết hóa đơn sau khi đặt hàng thành công
                    fetchOrderDetails(response.idhoadon);
                } else {
                    alert('Lỗi: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('Lỗi AJAX:', xhr, status, error);
                console.log('Phản hồi từ server:', xhr.responseText);
                if (!xhr.responseText) {
                    alert('Lỗi: Server không trả về dữ liệu. Vui lòng kiểm tra log lỗi trên server.');
                } else {
                    alert('Lỗi khi gửi yêu cầu đặt hàng: ' + error + '\nPhản hồi: ' + xhr.responseText);
                }
            }
        });
    }

    function fetchOrderDetails(idhoadon) {
        $.ajax({
            url: '../controller/hoadon.php?action=getOrderInfo&id=' + idhoadon,
            method: 'GET',
            dataType: 'json',
            success: function(orderInfo) {
                console.log('Order Info:', orderInfo); 
                $.ajax({
                    url: '../controller/chitiethoadon.php?action=getOrderDetails&id=' + idhoadon,
                    method: 'GET',
                    dataType: 'json',
                    success: function(orderDetails) {
                        console.log('Order Details:', orderDetails);
                        displaySuccessModal(orderInfo, orderDetails);
                    },
                    error: function(xhr, status, error) {
                        alert('Lỗi khi lấy chi tiết hóa đơn: ' + error);
                    }
                });
            },
            error: function(xhr, status, error) {
                alert('Lỗi khi lấy thông tin hóa đơn: ' + error);
            }
        });
    }
    function displaySuccessModal(orderInfo, orderDetails) {
        if (!orderInfo || typeof orderInfo !== 'object') {
            alert('Lỗi: Không lấy được thông tin hóa đơn');
            return;
        }

        // Kiểm tra từng thuộc tính và cung cấp giá trị mặc định
        const idhoadon = orderInfo.idhoadon || 'Không xác định';
        const tennguoinhan = orderInfo.tennguoinhan || 'Không xác định';
        const sdt = orderInfo.sdt || 'Không xác định';
        const diachi = orderInfo.diachi || 'Không xác định';
        const phuongthuctt = orderInfo.phuongthuctt || 'Không xác định';

        // Cập nhật thông tin hóa đơn
        $('#modal-dat-hang-thanh-cong ul li:nth-child(1)').html('<b>Mã hóa đơn là: </b>' + idhoadon);
        $('#modal-dat-hang-thanh-cong ul li:nth-child(2)').html('<b>Tên người nhận:</b> ' + tennguoinhan);
        $('#modal-dat-hang-thanh-cong ul li:nth-child(3)').html('<b>SĐT nhận hàng:</b> ' + sdt);
        $('#modal-dat-hang-thanh-cong ul li:nth-child(4)').html('<b>Địa chỉ nhận hàng:</b> ' + diachi);
        $('#modal-dat-hang-thanh-cong ul li:nth-child(5)').html('<b>Hình thức thanh toán: </b>' + phuongthuctt);

        // Xóa các dòng cũ trong tbody
        $('#modal-dat-hang-thanh-cong tbody').empty();

        // Thêm chi tiết sản phẩm
        let stt = 1;
        let tongtien = 0;
        if (orderDetails && Array.isArray(orderDetails)) {
            orderDetails.forEach(function(item) {
                let thanhtien = item.soluong * item.gia;
                $('#modal-dat-hang-thanh-cong tbody').append(`
                    <tr>
                        <td>${stt}</td>
                        <td>${item.tensach || 'Không xác định'}</td>
                        <td>${item.soluong || 0}</td>
                        <td>${(item.gia || 0).toLocaleString('vi-VN')} VND</td>
                        <td>${thanhtien.toLocaleString('vi-VN')} VND</td>
                    </tr>
                `);
                tongtien += thanhtien;
                stt++;
            });
        } else {
            $('#modal-dat-hang-thanh-cong tbody').append('<tr><td colspan="5">Không có chi tiết hóa đơn</td></tr>');
        }

        // Cập nhật tổng tiền
        $('#modal-dat-hang-thanh-cong .total-row td:last-child').text(tongtien.toLocaleString('vi-VN') + ' VND');

        // Hiển thị modal
        $('#modal-dat-hang-thanh-cong').show();
    }

    $('#modal-dat-hang-thanh-cong .closepopup').click(function() {
        $('#modal-dat-hang-thanh-cong').hide();
        window.location.href = '../controller/index.php';
    });
        // Hàm tải địa chỉ mặc định
    function loadDefaultAddress() {
        $.ajax({
            url: '../controller/thongtinnhanhang.php?action=hienThi&idKhachHang=' + idKhachHang,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    let defaultAddress = response.data.find(item => item.trangthai == 1);
                    if (defaultAddress) {
                        updateAddressInfo(defaultAddress);
                        defaultAddressId = defaultAddress.iddiachi; // Lưu iddiachi của địa chỉ mặc định
                    } else if (response.data.length > 0) {
                        updateAddressInfo(response.data[0]);
                        defaultAddressId = response.data[0].iddiachi; // Lưu iddiachi của địa chỉ đầu tiên
                    } else {
                        $('#receiver-name').text('Chưa có địa chỉ');
                        $('#receiver-phone').text('');
                        $('#receiver-address').text('');
                        $('#default3').hide();
                        defaultAddressId = null; // Không có địa chỉ
                    }
                } else {
                    $('#receiver-name').text('Chưa có địa chỉ');
                    $('#receiver-phone').text('');
                    $('#receiver-address').text('');
                    $('#default3').hide();
                    defaultAddressId = null;
                }
            },
            error: function() {
                $('#receiver-name').text('Lỗi tải dữ liệu');
                $('#receiver-phone').text('');
                $('#receiver-address').text('');
                $('#default3').hide();
                defaultAddressId = null;
            }
        });
    }

    // Hàm tải danh sách địa chỉ
    function loadAddressList() {
        $.ajax({
            url: '../controller/thongtinnhanhang.php?action=hienThi&idKhachHang=' + idKhachHang,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let addressList = $('#address-list');
                addressList.empty();
                
                if (response.status === 'success' && response.data.length > 0) {
                    response.data.forEach(function(item) {
                        let isChecked = item.trangthai == 1 ? 'checked' : '';
                        let defaultLabel = item.trangthai == 1 ? '<div class="macdinh">Mặc định</div>' : '';
                        let addressHtml = `
                            <div class="item-diachi">
                                <input type="radio" id="address_${item.iddiachi}" name="shipping_address" value="${item.iddiachi}" ${isChecked}>
                                <p>${item.hotenngnhan}</p>
                                <span>| ${item.sdtngnhan}</span>
                                <div class="sua">Sửa</div>
                                <div class="sua delete-address" data-id="${item.iddiachi}">Xóa</div>
                                <label for="address_${item.iddiachi}">${item.diachi_chitiet}, ${item.xa}, ${item.huyen}, ${item.thanhpho}</label>
                                ${defaultLabel}
                            </div>
                        `;
                        addressList.append(addressHtml);
                    });
                } else {
                    addressList.append('<p>Chưa có địa chỉ nào.</p>');
                }
            },
            error: function() {
                $('#address-list').html('<p>Lỗi tải dữ liệu.</p>');
            }
        });
    }

    // Hàm cập nhật thông tin địa chỉ được chọn
    function updateSelectedAddress(idDiaChi) {
        $.ajax({
            url: '../controller/thongtinnhanhang.php?action=hienThi&idKhachHang=' + idKhachHang,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    let selectedAddress = response.data.find(item => item.iddiachi == idDiaChi);
                    if (selectedAddress) {
                        updateAddressInfo(selectedAddress);
                    }
                }
            },
            error: function() {
                alert('Lỗi khi cập nhật thông tin địa chỉ.');
            }
        });
    }

    // Hàm cập nhật thông tin vào khối container-info-nhanhang
    function updateAddressInfo(address) {
        $('#receiver-name').text(address.hotenngnhan);
        $('#receiver-phone').text(address.sdtngnhan);
        $('#receiver-address').text(`${address.diachi_chitiet}, ${address.xa}, ${address.huyen}, ${address.thanhpho}`);
        $('#default3').css('display', address.trangthai == 1 ? 'block' : 'none');
    }

    // Hàm đặt địa chỉ mặc định
    function setDefaultAddress(idDiaChi) {
        $.ajax({
            url: '../controller/thongtinnhanhang.php?action=setDefault&idDiaChi=' + idDiaChi + '&idKhachHang=' + idKhachHang,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    loadDefaultAddress();
                    loadAddressList();
                    $('#set-default').prop('checked', false);
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Lỗi khi đặt địa chỉ mặc định.');
            }
        });
    }

    // Hàm tải form sửa địa chỉ
    function loadEditAddressForm(idDiaChi) {
        $.ajax({
            url: '../controller/thongtinnhanhang.php?action=hienThi&idKhachHang=' + idKhachHang,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    let address = response.data.find(item => item.iddiachi == idDiaChi);
                    if (address) {
                        $('#edit-iddiachi').val(address.iddiachi);
                        $('#full-name1').val(address.hotenngnhan);
                        $('#phone1').val(address.sdtngnhan);
                        $('#city1').val(address.thanhpho);
                        $('#district1').val(address.huyen);
                        $('#ward1').val(address.xa);
                        $('#text1').val(address.diachi_chitiet);
                        loadCities(address.thanhpho, address.huyen, address.xa);
                    }
                }
            },
            error: function() {
                alert('Lỗi khi tải thông tin địa chỉ.');
            }
        });
    }

    // Hàm cập nhật địa chỉ
    function updateAddress(data) {
        $.ajax({
            url: '../controller/thongtinnhanhang.php?action=update',
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    $('#edit-address').hide();
                    loadAddressList();
                    loadDefaultAddress();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Lỗi khi cập nhật địa chỉ.');
            }
        });
    }

    // Hàm xóa địa chỉ
    function deleteAddress(idDiaChi) {
        $.ajax({
            url: '../controller/thongtinnhanhang.php?action=delete&idDiaChi=' + idDiaChi,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    loadAddressList();
                    loadDefaultAddress();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Lỗi khi xóa địa chỉ.');
            }
        });
    }

    // Hàm thêm địa chỉ mới
    function addAddress(data) {
        $.ajax({
            url: '../controller/thongtinnhanhang.php?action=add',
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    $('#add-address').hide();
                    loadAddressList();
                    loadDefaultAddress();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Lỗi khi thêm địa chỉ.');
            }
        });
    }

    // Hàm tải danh sách thành phố, quận/huyện, phường/xã
    function loadCities(selectedCity, selectedDistrict, selectedWard) {
        let cities = ['Hồ Chí Minh', 'Hà Nội', 'Đà Nẵng'];
        let districts = {
            'Hồ Chí Minh': ['Quận 1', 'Quận Bình Tân', 'Quận 5'],
            'Hà Nội': ['Quận Hoàn Kiếm', 'Quận Ba Đình'],
            'Đà Nẵng': ['Quận Hải Châu', 'Quận Thanh Khê']
        };
        let wards = {
            'Quận 1': ['Phường Bến Nghé', 'Phường Nguyễn Thái Bình', 'Phường Tân Định'],
            'Quận Bình Tân': ['Phường Bình Trị Đông A', 'Phường An Lạc', 'Phường Bình Hưng Hòa'],
            'Quận 5': ['Phường 2', 'Phường 3', 'Phường 8'],
            'Quận Hoàn Kiếm': ['Phường Hàng Trống', 'Phường Lý Thái Tổ', 'Phường Tràng Tiền'],
            'Quận Ba Đình': ['Phường Ngọc Hà', 'Phường Đội Cấn', 'Phường Vĩnh Phúc'],
            'Quận Hải Châu': ['Phường Hòa Cường Bắc', 'Phường Thanh Bình', 'Phường Thuận Phước'],
            'Quận Thanh Khê': ['Phường An Khê', 'Phường Thanh Khê Đông', 'Phường Xuân Hà']
        };

        // Điền danh sách thành phố
        let cityTarget = $('#edit-address').is(':visible') ? '#city1' : '#city';
        let districtTarget = $('#edit-address').is(':visible') ? '#district1' : '#district';
        let wardTarget = $('#edit-address').is(':visible') ? '#ward1' : '#ward';

        $(cityTarget).empty().append('<option value="">Chọn thành phố</option>');
        cities.forEach(city => {
            let selected = city === selectedCity ? 'selected' : '';
            $(cityTarget).append(`<option value="${city}" ${selected}>${city}</option>`);
        });

        $(districtTarget).empty().append('<option value="">Chọn quận/huyện</option>');
        if (selectedCity && districts[selectedCity]) {
            districts[selectedCity].forEach(district => {
                let selected = district === selectedDistrict ? 'selected' : '';
                $(districtTarget).append(`<option value="${district}" ${selected}>${district}</option>`);
            });
        }

        $(wardTarget).empty().append('<option value="">Chọn phường/xã</option>');
        if (selectedDistrict && wards[selectedDistrict]) {
            wards[selectedDistrict].forEach(ward => {
                let selected = ward === selectedWard ? 'selected' : '';
                $(wardTarget).append(`<option value="${ward}" ${selected}>${ward}</option>`);
            });
        }

        // Thêm sự kiện để cập nhật quận/huyện khi thay đổi thành phố
        $(cityTarget).change(function() {
            let city = $(this).val();
            $(districtTarget).empty().append('<option value="">Chọn quận/huyện</option>');
            $(wardTarget).empty().append('<option value="">Chọn phường/xã</option>');
            if (city && districts[city]) {
                districts[city].forEach(district => {
                    $(districtTarget).append(`<option value="${district}">${district}</option>`);
                });
            }
        });

        // Thêm sự kiện để cập nhật phường/xã khi thay đổi quận/huyện
        $(districtTarget).change(function() {
            let district = $(this).val();
            $(wardTarget).empty().append('<option value="">Chọn phường/xã</option>');
            if (district && wards[district]) {
                wards[district].forEach(ward => {
                    $(wardTarget).append(`<option value="${ward}">${ward}</option>`);
                });
            }
        });
    }

    // Tải danh sách sản phẩm
    loadOrderItems();

    function loadOrderItems() {
        $.ajax({
            url: '../controller/giohang.php?action=hienThi&idKhachHang=' + idKhachHang,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    let orderItemsContainer = $('#order-items-container');
                    let totalPrice = 0;
                    let totalProducts = response.data.length;

                    orderItemsContainer.empty();

                    response.data.forEach(function(item) {
                        let thanhTien = parseFloat(item.gia) * parseInt(item.soluong);
                        totalPrice += thanhTien;

                        let orderItemHtml = `
                            <div class="box-item-buy">
                                <div class="box-img-buy">
                                    <img src="../../${item.anhbia}" alt="${item.tensach}">
                                    <span class="total-item">${item.soluong}</span>
                                </div>
                                <div class="box-description-buy">
                                    <h1>${item.tensach}</h1>
                                    <p class="price-cart">${item.gia}</p>
                                </div>
                            </div>
                        `;

                        orderItemsContainer.append(orderItemHtml);
                    });

                    $('#total-products').text('(' + totalProducts + ' sản phẩm)');
                    $('#total-order-price').text(totalPrice + ' VND');
                } else {
                    $('#order-items-container').html('<p>Không có sản phẩm trong đơn hàng.</p>');
                    $('#total-products').text('(0 sản phẩm)');
                    $('#total-order-price').text('0 VND');
                }
            },
            error: function() {
                $('#order-items-container').html('<p>Có lỗi khi tải dữ liệu.</p>');
                $('#total-products').text('(0 sản phẩm)');
                $('#total-order-price').text('0 VND');
            }
        });
    }
});
</script>