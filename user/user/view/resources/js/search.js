$(document).ready(function() {
    // Hàm xử lý tìm kiếm
    function performSearch(keyword, category, minPrice, maxPrice, page = 1) {
        // Validate từ khóa
        if (keyword === '') {
            $('.block-list-item-khuyen-mai').html('<div class="item-product"><p>Vui lòng nhập từ khóa tìm kiếm!</p></div>');
            $('.result-search h1').html('Tìm kiếm');
            $('.block-pagination ul').empty();
            return;
        }

        // Kiểm tra nếu đang ở trang tìm kiếm
        var currentPage = new URLSearchParams(window.location.search).get('pg');
        if (currentPage !== 'timkiem') {
            // Chuyển hướng tới trang tìm kiếm với tham số
            window.location.href = '../controller/index.php?pg=timkiem&keyword=' + encodeURIComponent(keyword) +
                '&category=' + encodeURIComponent(category) +
                '&min-price=' + encodeURIComponent(minPrice) +
                '&max-price=' + encodeURIComponent(maxPrice) +
                '&page=' + page;
            return;
        }

        // Cập nhật URL mà không tải lại trang
        var newUrl = '../controller/index.php?pg=timkiem&keyword=' + encodeURIComponent(keyword) +
            '&category=' + encodeURIComponent(category) +
            '&min-price=' + encodeURIComponent(minPrice) +
            '&max-price=' + encodeURIComponent(maxPrice) +
            '&page=' + page;
        window.history.pushState({}, '', newUrl);

        // Gửi yêu cầu AJAX
        $.ajax({
            url: '../controller/search.php',
            method: 'POST',
            data: {
                keyword: keyword,
                category: category,
                min_price: minPrice,
                max_price: maxPrice,
                page: page
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Cập nhật số lượng kết quả
                    $('.result-search h1').html(`Tìm kiếm được <span>${response.total_results}</span> kết quả ${keyword ? `cho từ khóa <span>"${keyword}"</span>` : ''}`);

                    // Cập nhật danh sách sản phẩm
                    var $productList = $('.block-list-item-khuyen-mai');
                    $productList.empty();

                    if (response.data.length > 0) {
                        response.data.forEach(function(book) {
                            var discountPrice = book.gia;
                            var originalPrice = (book.gia * 1.2).toFixed(0);
                            var discountPercent = Math.round(((originalPrice - discountPrice) / originalPrice) * 100);

                            var bookHtml = `
                                <div class="item-product">
                                    <div class="box-item-product">
                                        <div class="img-item-product">
                                            <a href="../controller/index.php?pg=chitietsp&id=${book.idsach}">
                                                <img src="../../${book.anhbia}" alt="${book.tensach}">
                                            </a>
                                        </div>
                                        <div class="box-description-item">
                                            <a href="../controller/index.php?pg=chitietsp&id=${book.idsach}">
                                                <h1>${book.tensach}</h1>
                                            </a>
                                            <div class="price">
                                                <span><b>${parseInt(book.gia).toLocaleString('vi-VN')}</b></span>
                                                <span class="price-old">
                                                    <del>${parseInt(originalPrice).toLocaleString('vi-VN')}</del>
                                                    <label class="label-discount">${discountPercent}%</label>
                                                </span>
                                            </div>
                                            <div class="box-add-to-card">
                                                <i class="icon-cart dataModal" data-id="modal-them-vao-gio-hang" data-idsach="${book.idsach}"></i>
                                                <button class="green buy-now" data-idsach="${book.idsach}">Mua ngay</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $productList.append(bookHtml);
                        });
                    } else {
                        $productList.html('<div class="item-product"><p>Không tìm thấy sách nào phù hợp.</p></div>');
                    }

                    // Cập nhật phân trang
                    var $pagination = $('.block-pagination ul');
                    $pagination.empty();

                    if (response.total_pages > 1) {
                        // Nút "Trước"
                        $pagination.append(`
                            <li><a href="#" class="page-link" data-page="${response.current_page - 1}" ${response.current_page <= 1 ? 'style="pointer-events: none; opacity: 0.5;"' : ''}><i class="icon-btn-pre"></i></a></li>
                        `);

                        // Các số trang
                        for (var i = 1; i <= response.total_pages; i++) {
                            $pagination.append(`
                                <li ${i === response.current_page ? 'class="active"' : ''}>
                                    <a href="#" class="page-link" data-page="${i}">${i}</a>
                                </li>
                            `);
                        }

                        // Nút "Sau"
                        $pagination.append(`
                            <li><a href="#" class="page-link" data-page="${response.current_page + 1}" ${response.current_page >= response.total_pages ? 'style="pointer-events: none; opacity: 0.5;"' : ''}><i class="icon-btn-next"></i></a></li>
                        `);
                    }

                    // Gắn sự kiện cho các liên kết phân trang
                    $('.page-link').off('click').on('click', function(e) {
                        e.preventDefault();
                        var newPage = $(this).data('page');
                        performSearch(keyword, category, minPrice, maxPrice, newPage);
                    });
                } else {
                    $('.block-list-item-khuyen-mai').html('<div class="item-product"><p>Đã xảy ra lỗi khi tìm kiếm.</p></div>');
                    $('.result-search h1').html('Tìm kiếm');
                    $('.block-pagination ul').empty();
                }
            },
            error: function() {
                $('.block-list-item-khuyen-mai').html('<div class="item-product"><p>Đã xảy ra lỗi khi tìm kiếm.</p></div>');
                $('.result-search h1').html('Tìm kiếm');
                $('.block-pagination ul').empty();
            }
        });
    }

    // Hàm debounce để giới hạn tần suất gọi hàm
    function debounce(func, wait) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                func.apply(context, args);
            }, wait);
        };
    }

    // Hàm lấy giá trị và gọi tìm kiếm
    function triggerSearch() {
        var keyword = $('input[name="keyword"]').val().trim();
        var category = $('#category').val() || 'all';
        var minPrice = $('input[name="min-price"]').val() || '';
        var maxPrice = $('input[name="max-price"]').val() || '';
        performSearch(keyword, category, minPrice, maxPrice);
    }

    // Hàm lấy tham số từ URL
    function getUrlParams() {
        var params = new URLSearchParams(window.location.search);
        return {
            keyword: params.get('keyword') || '',
            category: params.get('category') || 'all',
            minPrice: params.get('min-price') || '',
            maxPrice: params.get('max-price') || '',
            page: parseInt(params.get('page')) || 1
        };
    }

    // Khởi tạo tìm kiếm khi trang được tải
    var params = getUrlParams();
    if (new URLSearchParams(window.location.search).get('pg') === 'timkiem') {
        // Điền giá trị từ URL vào các ô input nếu có
        $('input[name="keyword"]').val(params.keyword);
        $('#category').val(params.category);
        $('input[name="min-price"]').val(params.minPrice);
        $('input[name="max-price"]').val(params.maxPrice);

        // Gọi hàm tìm kiếm với các tham số từ URL
        performSearch(params.keyword, params.category, params.minPrice, params.maxPrice, params.page);
    }

    // Xử lý submit form tìm kiếm
    $('form[action="../controller/index.php"]').on('submit', function(e) {
        e.preventDefault(); // Ngăn submit mặc định
        triggerSearch();
    });

    // Xử lý khi nhập vào ô keyword
    $('input[name="keyword"]').on('input', debounce(triggerSearch, 600));

    // Xử lý khi thay đổi danh mục
    $('#category').on('change', triggerSearch);

    // Xử lý khi nhập vào ô min-price
    $('input[name="min-price"]').on('input', debounce(triggerSearch, 600));

    // Xử lý khi nhập vào ô max-price
    $('input[name="max-price"]').on('input', debounce(triggerSearch, 600));
});