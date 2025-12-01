<?php
if (!defined('APP_PATH')) {
    define('APP_PATH', __DIR__ . '/..');
}

if (!defined('BASE_URL')) {
    define('BASE_URL', '../../'); // Đường dẫn gốc của dự án
}

// Không cần khởi tạo BookModel và lấy dữ liệu vì đã được truyền từ BookController
// $book, $images, $relatedBooks đã được định nghĩa trong BookController.php
?>

<aside class="block-main-content">
    <div class="block-breadcrumbs">
        <ul>
            <li><a href="index.php"><i class="icon-home"></i></a></li>
            <li>
                <a href="index.php?pg=category&id=<?php echo htmlspecialchars($book->iddanhmuc); ?>">
                    <?php echo htmlspecialchars($book->tendanhmuc); ?><i class="icon-btn-next"></i>
                </a>
            </li>
            <li>
                <a href="index.php?pg=category&subcat=<?php echo htmlspecialchars($book->idchitietdanhmuc); ?>">
                    <?php echo htmlspecialchars($book->tenchitietdanhmuc); ?><i class="icon-btn-next"></i>
                </a>
            </li>
            <li><span><?php echo htmlspecialchars($book->tensach); ?></span></li>
        </ul>
    </div>

    <div class="box-block-main-content">
        <div class="block-detail-product">
            <div class="flex-item">
                <!-- <div class="block-left-detail-product">
                    <div class="block-img-product-detail">
                        <div class="img-detail-product">
                            <img src="<?php echo BASE_URL . htmlspecialchars($book->anhbia); ?>" alt="<?php echo htmlspecialchars($book->tensach); ?>">
                        </div>
                    </div>
                    <div class="block-thum-img-detail">
                        <?php if (!empty($images)): ?>
                            <?php foreach ($images as $image): ?>
                                <div class="img-thum-detail-product">
                                    <img src="<?php echo BASE_URL . htmlspecialchars($image->duongdananh); ?>" alt="<?php echo htmlspecialchars($book->tensach); ?>">
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Không có ảnh phụ cho sách này.</p>
                        <?php endif; ?>
                    </div>
                </div> -->
                <div class="block-left-detail-product">
                    <div class="block-img-product-detail" id="block-img-product-detail">
                        <div class="img-detail-product">
                            <img src="<?php echo BASE_URL . htmlspecialchars($book->anhbia); ?>" alt="<?php echo htmlspecialchars($book->tensach); ?>">
                        </div>
                        <?php if (!empty($images)): ?>
                            <?php foreach ($images as $image): ?>
                                <div class="img-detail-product">
                                    <img src="<?php echo BASE_URL . htmlspecialchars($image->duongdananh); ?>" alt="<?php echo htmlspecialchars($book->tensach); ?>">
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="block-thum-img-detail" id="block-thum-img-detail">
                        <div class="img-thum-detail-product">
                            <img src="<?php echo BASE_URL . htmlspecialchars($book->anhbia); ?>" alt="<?php echo htmlspecialchars($book->tensach); ?>">
                        </div>
                        <?php if (!empty($images)): ?>
                            <?php foreach ($images as $image): ?>
                                <div class="img-thum-detail-product">
                                    <img src="<?php echo BASE_URL . htmlspecialchars($image->duongdananh); ?>" alt="<?php echo htmlspecialchars($book->tensach); ?>">
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="block-right-detail-product">
                    <h1 class="txt-name-product"><?php echo htmlspecialchars($book->tensach); ?></h1>
                    <div class="code-product">
                        <p><b>Mã sách:</b> BK<?php echo sprintf("%04d", $book->idsach); ?></p>
                    </div>
                    <div class="tacgia">
                        <span><b>Tác giả:</b> <?php echo htmlspecialchars($book->tentacgia); ?></span>
                    </div>
                    <div class="price-product">
                        <p>
                            <b>Giá:</b> <?php echo number_format($book->gia, 0, ',', '.'); ?> VNĐ
                            <?php if ($book->gia < 150000): ?>
                                <del><?php echo number_format($book->gia * 1.2, 0, ',', '.'); ?> VNĐ</del>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="block-box-so-luong cart-detail__quanity-col">
                        <b>Số lượng:</b>
                        <div class="box-so-luong cart-detail__quantity-input-controls">
                            <button class="btn-tru decrease-btn icon-tru"></button>
                            <input class="-quantity-input" type="text" value="1" data-limit-quantity="<?php echo htmlspecialchars($book->sltonkho); ?>">
                            <button class="btn-plus increase-btn icon-plus"></button>
                        </div>
                    </div>
                    <div class="status">
                        Tình trạng:
                        <span id="con" style="<?php echo $book->sltonkho > 0 ? 'display:inline;' : 'display:none;'; ?>"> Còn hàng</span>
                        <span id="het" style="<?php echo $book->sltonkho <= 0 ? 'display:inline;' : 'display:none;'; ?>"> Hết hàng</span>
                    </div>
                    <div class="block-add-to-cart">
                        <form id="add-to-cart-form">
                            <input type="hidden" name="quantity" class="quantity-hidden" value="1">
                            <input type="hidden" name="idSach" value="<?php echo htmlspecialchars($book->idsach); ?>">
                            <button type="button" class="btn-add-to-cart" <?php echo $book->sltonkho <= 0 ? 'disabled' : ''; ?>>
                                <i class="icon-cart-bold"></i> Thêm vào giỏ
                            </button>
                            <button type="button" class="btn-buy-now" <?php echo $book->sltonkho <= 0 ? 'disabled' : ''; ?>>
                                <i class="icon-dola"></i> Mua ngay
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="block-description">
            <h1 class="txt-description"><span>Giới thiệu sách</span></h1>
            <h2 class="block-title-description">Thông tin chi tiết</h2>
            <div class="box-note-product">
                <p>Tác giả: <?php echo htmlspecialchars($book->tentacgia); ?></p>
                <p>Nhà xuất bản: <?php echo htmlspecialchars($book->tennxb); ?></p>
            </div>
            <h2 class="block-title-description">Mô tả</h2>
            <p><?php echo nl2br(htmlspecialchars($book->mota)); ?></p>
        </div>

        <div class="block-item-products block-item-products-like">
            <h1 class="text-title">Sách liên quan</h1>
            <div class="block-list-item-khuyen-mai flex-item">
                <?php if (!empty($relatedBooks)): ?>
                    <?php foreach ($relatedBooks as $related): ?>
                        <div class="item-product">
                            <div class="box-item-product">
                                <div class="img-item-product">
                                    <a href="index.php?pg=chitietsp&id=<?php echo htmlspecialchars($related->idsach); ?>">
                                        <img src="<?php echo BASE_URL . htmlspecialchars($related->anhbia); ?>" alt="<?php echo htmlspecialchars($related->tensach); ?>">
                                    </a>
                                </div>
                                <div class="box-description-item">
                                    <a href="index.php?pg=chitietsp&id=<?php echo htmlspecialchars($related->idsach); ?>">
                                        <h1><?php echo htmlspecialchars($related->tensach); ?></h1>
                                        <h2>Tác giả: <?php echo htmlspecialchars($related->tentacgia); ?></h2>
                                    </a>
                                    <div class="price">
                                        <span><b><?php echo number_format($related->gia, 0, ',', '.'); ?></b></span>
                                        <span class="price-old">
                                            <del><?php echo number_format($related->gia * 1.2, 0, ',', '.'); ?></del>
                                        </span>
                                    </div>
                                    <div class="box-add-to-card">
                                        <i class="icon-cart cartModal" data-id="modal-them-vao-gio-hang" data-idsach="<?php echo htmlspecialchars($related->idsach); ?>"></i>
                                        <button class="green buy-now-custom" data-idsach="<?php echo htmlspecialchars($related->idsach); ?>">Mua ngay</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Không có sách liên quan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</aside>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        const decreaseBtn = $('.decrease-btn');
        const increaseBtn = $('.increase-btn');
        const quantityInput = $('.-quantity-input');
        const maxQuantity = parseInt(quantityInput.data('limit-quantity'));

        // Sự kiện khi nhấn nút giảm
        decreaseBtn.off('click').on('click', function() {
            let currentValue = parseInt(quantityInput.val());
            if (currentValue > 1) {
                quantityInput.val(currentValue - 1);  // Giảm số lượng
                updateHiddenQuantity();
            }
        });

        // Sự kiện khi nhấn nút tăng
        increaseBtn.off('click').on('click', function() {
            let currentValue = parseInt(quantityInput.val());
            if (currentValue < maxQuantity) {
                quantityInput.val(currentValue + 1);  // Tăng số lượng
                updateHiddenQuantity();
            }
        });

        // Kiểm tra và xử lý số lượng khi thay đổi giá trị trong input
        quantityInput.on('input', function() {
            let value = parseInt(this.value);
            if (isNaN(value) || value < 1) {
                this.value = 1;  // Nếu nhập không hợp lệ, gán lại giá trị là 1
            } else if (value > maxQuantity) {
                this.value = maxQuantity;  // Nếu vượt quá số lượng tối đa, gán lại giá trị tối đa
            }
            updateHiddenQuantity();
        });

        // Hàm cập nhật giá trị vào ô ẩn
        function updateHiddenQuantity() {
            $('.quantity-hidden').val(quantityInput.val());
        }

        // Sự kiện khi nhấn nút "Thêm vào giỏ"
        $('.btn-add-to-cart').click(function(e) {
            e.preventDefault(); // Ngăn form submit mặc định

            var idSach = $('input[name="idSach"]').val();
            var soLuong = quantityInput.val(); // Lấy số lượng từ input

            // Gửi yêu cầu AJAX
            $.ajax({
                url: '../controller/giohang.php',
                method: 'POST',
                data: {
                    action: 'them',
                    idKhachHang: idKhachHang,
                    idSach: idSach,
                    soLuong: soLuong
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#modal-them-vao-gio-hang').addClass('block-modal-active');
                        setTimeout(function() {
                            $('#modal-them-vao-gio-hang').removeClass('block-modal-active');
                        }, 3000);
                        loadCartItems();  // Cập nhật giỏ hàng
                    } else {
                        alert('Lỗi: ' + response.message);
                    }
                },
                error: function() {
                    alert('Có lỗi khi thêm vào giỏ hàng.');
                }
            });
        });

        // Xử lý khi nhấn nút "Mua ngay"
        $('.btn-buy-now').click(function(e) {
            e.preventDefault();

            if (idKhachHang === null) {
                alert('Vui lòng đăng nhập để mua sản phẩm.');
                window.location.href = 'index.php?pg=dangnhap';
                return;
            }

            var idSach = $('input[name="idSach"]').val();
            var soLuong = $('input[name="quantity"]').val();

            $.ajax({
                url: '../controller/giohang.php',
                method: 'POST',
                data: {
                    action: 'them',
                    idKhachHang: idKhachHang,
                    idSach: idSach,
                    soLuong: soLuong
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        loadCartItems();

                        window.location.href = 'index.php?pg=giohang';
                    } else {
                        alert('Lỗi: ' + response.message);
                    }
                },
                error: function() {
                    alert('Có lỗi khi thêm vào giỏ hàng.');
                }
            });
        });

        // Đóng modal
        $('.closepopup').click(function() {
            $('#modal-them-vao-gio-hang').removeClass('block-modal-active');
        });
    });
</script>
