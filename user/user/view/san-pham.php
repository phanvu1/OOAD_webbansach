<?php
// user/view/san-pham.php
if (!defined('APP_PATH')) {
    define('APP_PATH', __DIR__ . '/../../'); // Đi lên 2 cấp để đến thư mục gốc (project/)
}

if (!defined('BASE_URL')) {
    define('BASE_URL', '/'); // Điều chỉnh nếu cần
}

// Lấy tham số từ URL
$categoryId = isset($_GET['iddanhmuc']) ? (int)$_GET['iddanhmuc'] : 7;
$subCategoryId = isset($_GET['idchitietdanhmuc']) ? (int)$_GET['idchitietdanhmuc'] : 0;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
?>

<aside class="block-main-content">
    <div class="block-breadcrumbs">
        <ul>
            <li><a href="../controller/index.php"><i class="icon-home"></i></a></li>
            <li><a href="../controller/index.php?pg=sanpham&iddanhmuc=<?php echo $categoryId; ?>" class="category-name">Loading...</a></li>
            <li><a href="#" class="subcategory-name">Loading...</a></li>
        </ul>
    </div>
    <div class="box-block-main-content">
        <div class="block-type-product">
            <ul class="box-type-product" id="box-type-product">
                <!-- Danh mục con sẽ được tải bằng AJAX -->
            </ul>
        </div>
        <div style="clear: both;"></div>
        <div class="block-item-products">
            <h1 class="text-title category-name">Loading...</h1>
            <div class="block-list-item-khuyen-mai flex-item" id="product-list">
                <!-- Sản phẩm sẽ được tải bằng AJAX -->
            </div>
            <div class="block-pagination">
                <ul id="pagination">
                    <!-- Phân trang sẽ được tải bằng AJAX -->
                </ul>
            </div>
        </div>
    </div>
</aside>


<script>
$(document).ready(function() {
    // Hàm tải sản phẩm qua AJAX
    function loadProducts(categoryId, subCategoryId, page) {
        $.ajax({
            url: '../controller/load_products.php', // Đường dẫn vẫn đúng vì cùng thư mục user/
            type: 'POST',
            data: {
                iddanhmuc: categoryId,
                idchitietdanhmuc: subCategoryId,
                page: page
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Cập nhật tên danh mục
                    $('.category-name').text(response.categoryName);

                    // Cập nhật tên danh mục con
                    $('.subcategory-name').text(response.subCategoryName);

                    // Cập nhật danh mục con
                    let subCategoryHtml = `<li class="${response.subCategoryId == 0 ? 'active' : ''}">
                        <a href="#" data-subcategory="0" data-category="${categoryId}">Tất cả</a>
                    </li>`;
                    response.subCategories.forEach(function(subCategory) {
                        subCategoryHtml += `<li class="${response.subCategoryId == subCategory.idchitietdanhmuc ? 'active' : ''}">
                            <a href="#" data-subcategory="${subCategory.idchitietdanhmuc}" data-category="${categoryId}">
                                ${subCategory.tenchitietdanhmuc}
                            </a>
                        </li>`;
                    });
                    $('#box-type-product').html(subCategoryHtml);

                    // Cập nhật danh sách sản phẩm
                    let productHtml = '';
                    if (response.books.length > 0) {
                        response.books.forEach(function(book) {
                            productHtml += `
                                <div class="item-product">
                                    <div class="box-item-product">
                                        <div class="img-item-product">
                                            <a href="../controller/index.php?pg=chitietsp&id=${book.idsach}">
                                                <img src="<?php echo BASE_URL; ?>${book.anhbia}" alt="${book.tensach}">
                                            </a>
                                        </div>
                                        <div class="box-description-item">
                                            <a href="../controller/index.php?pg=chitietsp&id=${book.idsach}">
                                                <h1>${book.tensach}</h1>
                                                <h2>Tác giả: ${book.tentacgia}</h2>
                                            </a>
                                            <div class="price">
                                                <span><b>${new Intl.NumberFormat('vi-VN').format(book.gia)}</b></span>
                                                <span class="price-old">
                                                    <del>${new Intl.NumberFormat('vi-VN').format(book.gia * 1.2)}</del>
                                                    <label class="label-discount">${Math.round(((book.gia * 1.2 - book.gia) / (book.gia * 1.2)) * 100)}%</label>
                                                </span>
                                            </div>
                                            <div class="box-add-to-card">
                                                <i class="icon-cart dataModal" data-id="modal-them-vao-gio-hang" data-idsach="${book.idsach}"></i>
                                                <button class="green buy-now" data-idsach="${book.idsach}">Mua ngay</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                        });
                    } else {
                        productHtml = '<p>Không có sách nào trong danh mục này.</p>';
                    }
                    $('#product-list').html(productHtml);

// Cập nhật phân trang
let paginationHtml = '';
if (response.totalPages > 1) { // Chỉ hiển thị phân trang nếu totalPages > 1
    if (response.currentPage > 1) {
        paginationHtml += `<li><a href="#" data-page="${response.currentPage - 1}" data-category="${categoryId}" data-subcategory="${response.subCategoryId}"><i class="icon-btn-pre"></i></a></li>`;
    }
    for (let i = 1; i <= response.totalPages; i++) {
        paginationHtml += `<li class="${i == response.currentPage ? 'active' : ''}">
            <a href="#" data-page="${i}" data-category="${categoryId}" data-subcategory="${response.subCategoryId}">${i}</a>
        </li>`;
    }
    if (response.currentPage < response.totalPages) {
        paginationHtml += `<li><a href="#" data-page="${response.currentPage + 1}" data-category="${categoryId}" data-subcategory="${response.subCategoryId}"><i class="icon-btn-next"></i></a></li>`;
    }
}
$('#pagination').html(paginationHtml);
                } else {
                    alert('Lỗi từ server: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('Lỗi AJAX:', xhr.responseText);
                alert('Lỗi khi tải sản phẩm: ' + error + '\nChi tiết: ' + xhr.responseText);
            }
        });
    }

    // Tải sản phẩm ban đầu
    loadProducts(<?php echo $categoryId; ?>, <?php echo $subCategoryId; ?>, <?php echo $page; ?>);

    // Xử lý sự kiện khi nhấn vào danh mục con
    $(document).on('click', '#box-type-product a', function(e) {
        e.preventDefault();
        let categoryId = $(this).data('category');
        let subCategoryId = $(this).data('subcategory');
        loadProducts(categoryId, subCategoryId, 1);
    });

    // Xử lý sự kiện khi nhấn vào phân trang
    $(document).on('click', '#pagination a', function(e) {
        e.preventDefault();
        let categoryId = $(this).data('category');
        let subCategoryId = $(this).data('subcategory');
        let page = $(this).data('page');
        loadProducts(categoryId, subCategoryId, page);
    });
});
</script>

<!-- Modal xác nhận thêm vào giỏ hàng -->
<!-- <div class="block-modal block-modal-complete-cart" id="modal-them-vao-gio-hang" style="display:none;">
    <h1>Thông báo
        <span class="closepopup"></span>
    </h1>
    <div class="box-descript-modal">
        <h2>Sản phẩm đã được thêm vào giỏ hàng!</h2>
        <p><a href="../controller/index.php?pg=giohang">Xem giỏ hàng</a></p>
    </div>
</div> -->

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