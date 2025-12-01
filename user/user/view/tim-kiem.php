<aside class="block-main-content">
    <div class="result-search">
        <h1> Tìm kiếm được <span><?php echo $total_results; ?></span> kết quả <?php if ($keyword !== '') { echo 'cho từ khóa <span>"' . htmlspecialchars($keyword) . '"</span>'; } ?></h1>
    </div>
    <div class="box-block-main-content">
        <div class="block-item-products">
            <div class="block-list-item-khuyen-mai flex-item">
                <?php foreach ($books as $book): ?>
                    <div class="item-product">
                        <div class="box-item-product">
                            <div class="img-item-product">
                                <a href="../controller/index.php?pg=chitietsp&id=<?php echo htmlspecialchars($book['idsach']); ?>">
                                    <img src="../../<?php echo htmlspecialchars($book['anhbia']); ?>" alt="">
                                </a>
                            </div>
                            <div class="box-description-item">
                                <a href="../controller/index.php?pg=chitietsp&id=<?php echo htmlspecialchars($book['idsach']); ?>">
                                    <h1><?php echo htmlspecialchars($book['tensach']); ?></h1>
                                </a>
                                <div class="price">
                                    <span><b><?php echo number_format($book['gia'], 0, ',', '.'); ?></b></span>
                                    <span class="price-old">
                                        <del><?php echo number_format($book['gia'] * 1.2, 0, ',', '.'); ?></del>
                                        <label class="label-discount"><?php echo round((($book['gia'] * 1.2 - $book['gia']) / ($book['gia'] * 1.2)) * 100); ?>%</label>
                                    </span>
                                </div>
                                <div class="box-add-to-card">
                                    <i class="icon-cart dataModal" data-id="modal-them-vao-gio-hang" data-idsach="${book.idsach}"></i>
                                    <button class="green buy-now" data-idsach="${book.idsach}">Mua ngay</button></div>
                                </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($books)): ?>
                    <div class="item-product">
                        <p>Không tìm thấy sách nào phù hợp.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="block-pagination">
            <ul>
                <?php
                $items_per_page = 10; // Số sách mỗi trang
                $total_pages = ceil($total_results / $items_per_page);
                $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
                if ($current_page > $total_pages) $current_page = $total_pages;

                // Nút "Trước"
                ?>
                <li><a href="#" class="page-link" data-page="<?php echo $current_page - 1; ?>" <?php if ($current_page <= 1) echo 'style="pointer-events: none; opacity: 0.5;"'; ?>><i class="icon-btn-pre"></i></a></li>

                <?php
                // Hiển thị các số trang
                for ($i = 1; $i <= $total_pages; $i++):
                ?>
                    <li <?php if ($i == $current_page) echo 'class="active"'; ?>><a href="#" class="page-link" data-page="<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php endfor; ?>

                <!-- Nút "Sau" -->
                <li><a href="#" class="page-link" data-page="<?php echo $current_page + 1; ?>" <?php if ($current_page >= $total_pages) echo 'style="pointer-events: none; opacity: 0.5;"'; ?>><i class="icon-btn-next"></i></a></li>
            </ul>
        </div>
    </div>   
</aside>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var idKhachHang = <?php echo isset($_SESSION['idkhachhang']) ? json_encode($_SESSION['idkhachhang']) : 'null'; ?>;
    // Xử lý khi click vào biểu tượng giỏ hàng
    $(document).on('click', '.icon-cart.dataModal', function() {
        
        if (idKhachHang === null) {
            console.log('User not logged in, redirecting to login');
            alert('Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng!');
            window.location.href = '../controller/index.php?pg=dangnhap';
            return;
        }
        var idSach = $(this).data('idsach'); 
        
        $.ajax({
            url: '../controller/giohang.php',
            type: 'POST',
            data: {
                action: 'them',
                idKhachHang: idKhachHang,
                idSach: idSach,
                soLuong: 1 // Mặc định thêm 1 sản phẩm
            },
            dataType: 'json',
            success: function(response) {
                console.log('AJAX success:', response);
                if (response.status === 'success') {
                    console.log('Showing modal');
                    $('#modal-them-vao-gio-hang').addClass('block-modal-active');
                    setTimeout(function() {
                        console.log('Hiding modal after 3 seconds');
                        $('#modal-them-vao-gio-hang').removeClass('block-modal-active');
                    }, 3000);
                    loadCartItems();
                } else {
                    alert('Lỗi: ' + response.message);
                }
            },
            error: function() {
                alert('Đã có lỗi xảy ra khi thêm vào giỏ hàng.');
            }
        });
    });

    // Xử lý khi click vào nút "Mua ngay"
    $(document).on('click', '.buy-now', function() {
        
        if (idKhachHang === null) {
            console.log('User not logged in, redirecting to login');
            alert('Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng!');
            window.location.href = '../controller/index.php?pg=dangnhap';
            return;
        }
        var idSach = $(this).data('idsach'); // Lấy idsach từ data-idsach

        $.ajax({
            url: '../controller/giohang.php',
            type: 'POST',
            data: {
                action: 'them',
                idKhachHang: idKhachHang,
                idSach: idSach,
                soLuong: 1 // Mặc định thêm 1 sản phẩm
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Thêm thành công, chuyển hướng đến trang giỏ hàng
                    window.location.href = '../controller/index.php?pg=giohang';
                } else {
                    alert('Lỗi: ' + response.message);
                }
            },
            error: function() {
                alert('Đã có lỗi xảy ra khi thêm vào giỏ hàng.');
            }
        });
    });
});
</script>