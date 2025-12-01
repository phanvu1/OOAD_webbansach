<article class="container block-content block-content-news">
    <div class="flex-item">
        <aside class="block-main-content">
            <div class="box-block-main-content">
                <h1 class="text-title">Lịch sử mua hàng</h1>
                <h3 class="title-gio-hang">Những đơn hàng của bạn</h3>
                <!-- Bảng lịch sử mua hàng -->
                <table class="purchase-history">
                    <thead>
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody id="purchase-history-body">
                        <!-- Dữ liệu sẽ được thêm động bằng JavaScript -->
                    </tbody>
                </table>
                <!-- Kết thúc bảng -->
            </div>
        </aside>
    </div>

    <!-- Modal Chi tiết hóa đơn -->
    <div class="block-modal block-modal-complete-cart block-modal-history" id="modal-dat-hang-thanh-cong" style="display:none;">
        <h1>Thông báo
            <span class="closepopup"></span>
        </h1>
        <div class="box-descript-modal">
            <h2>Cảm ơn bạn đã mua hàng tại Unibook!</h2>
            <h2>Chúng tôi đã tiếp nhận đơn hàng của bạn với:</h2>
            <ul>
                <li><b>Mã hóa đơn là: </b><span id="modal-idhoadon"></span></li>
                <li><b>Tên người nhận:</b> <span id="modal-tennguoinhan"></span></li>
                <li><b>SĐT nhận hàng:</b> <span id="modal-sdt"></span></li>
                <li><b>Địa chỉ nhận hàng:</b> <span id="modal-diachi"></span></li>
                <li><b>Hình thức thanh toán: </b><span id="modal-phuongthuctt"></span></li>
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
                <tbody id="modal-order-details"></tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="4">Tổng số tiền</td>
                        <td id="modal-total-price"></td>
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

        if (idKhachHang === null) {
            alert('Vui lòng đăng nhập để xem lịch sử mua hàng.');
            window.location.href = '../controller/index.php?pg=dangnhap';
            return;
        }

        loadPurchaseHistory();

        $('#modal-dat-hang-thanh-cong .closepopup').on('click', function() {
            $('#modal-dat-hang-thanh-cong').hide().removeClass('block-modal-active');
        });

        $(document).on('click', '.dataModal', function(e) {
            e.preventDefault();
            var idhoadon = $(this).data('idhoadon');
            fetchOrderDetails(idhoadon);
        });

        // Hàm tải danh sách hóa đơn
        function loadPurchaseHistory() {
            $.ajax({
                url: '../controller/hoadon.php?action=getOrderByCustomer&idKhachHang=' + idKhachHang,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    var tbody = $('#purchase-history-body');
                    tbody.empty();

                    if (response.status === 'success' && response.data.length > 0) {
                        response.data.forEach(function(order) {
                            let statusText = '';
                            switch (parseInt(order.trangthai)) {
                                case 0:
                                    statusText = 'Chờ xác nhận';
                                    break;
                                case 1:
                                    statusText = 'Đã xác nhận';
                                    break;
                                case 2:
                                    statusText = 'Đã giao thành công';
                                    break;
                                case 3:
                                    statusText = 'Đã hủy';
                                    break;
                                default:
                                    statusText = 'Không xác định';
                            }

                            const rowHtml = `
                                <tr>
                                    <td>#${order.idhoadon}</td>
                                    <td>${formatDate(order.ngayxuat)}</td>
                                    <td>${parseFloat(order.tongtien).toLocaleString('vi-VN')} VND</td>
                                    <td>${statusText}</td>
                                    <td><a href="#" class="dataModal" data-idhoadon="${order.idhoadon}" data-id="modal-dat-hang-thanh-cong">Xem</a></td>
                                </tr>
                            `;
                            tbody.append(rowHtml);
                        });
                    } else {
                        tbody.append('<tr><td colspan="5">Bạn chưa có hóa đơn nào.</td></tr>');
                    }
                },
                error: function() {
                    $('#purchase-history-body').html('<tr><td colspan="5">Lỗi khi tải dữ liệu.</td></tr>');
                }
            });
        }

        function fetchOrderDetails(idhoadon) {
            $.ajax({
                url: '../controller/hoadon.php?action=getOrderInfo&id=' + idhoadon,
                method: 'GET',
                dataType: 'json',
                success: function(orderInfo) {
                    $.ajax({
                        url: '../controller/chitiethoadon.php?action=getOrderDetails&id=' + idhoadon,
                        method: 'GET',
                        dataType: 'json',
                        success: function(orderDetails) {
                            displayOrderModal(orderInfo, orderDetails);
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

        function displayOrderModal(orderInfo, orderDetails) {
            if (!orderInfo || typeof orderInfo !== 'object') {
                alert('Lỗi: Không lấy được thông tin hóa đơn');
                return;
            }

            $('#modal-idhoadon').text(orderInfo.idhoadon || 'Không xác định');
            $('#modal-tennguoinhan').text(orderInfo.tennguoinhan || 'Không xác định');
            $('#modal-sdt').text(orderInfo.sdt || 'Không xác định');
            $('#modal-diachi').text(orderInfo.diachi || 'Không xác định');
            $('#modal-phuongthuctt').text(orderInfo.phuongthuctt || 'Không xác định');

            $('#modal-order-details').empty();

            let stt = 1;
            let tongtien = 0;
            if (orderDetails && Array.isArray(orderDetails)) {
                orderDetails.forEach(function(item) {
                    let thanhtien = item.soluong * item.gia;
                    $('#modal-order-details').append(`
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
                $('#modal-order-details').append('<tr><td colspan="5">Không có chi tiết hóa đơn</td></tr>');
            }

            $('#modal-total-price').text(tongtien.toLocaleString('vi-VN') + ' VND');

            $('#modal-dat-hang-thanh-cong').show().addClass('block-modal-active');
        }

        function formatDate(dateString) {
            var date = new Date(dateString);
            var day = date.getDate().toString().padStart(2, '0');
            var month = (date.getMonth() + 1).toString().padStart(2, '0');
            var year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }
    });
    </script>
</article>
