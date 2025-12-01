<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="../view/resources/style/style.css" />
    <link rel="stylesheet" type="text/css" href="../view/resources/slick/slick-theme.css" />
    <link rel="stylesheet" type="text/css" href="../view/resources/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="../view/resources/style/moblie.css" />
    <link rel="stylesheet" href="../view/resources/style/perfect-scrollbar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <title>Document</title>
</head>
<body class="mrtung">
    <div class="wrapper">
        <header>
            <div class="mobile-toggle" id="mobile-toggle">
                <div class="button_toggle">
                    <span class="line" id="btn_line1"></span>
                    <span class="line line2" id="btn_line2"></span>
                    <span class="line" id="btn_line3"></span>
                </div>
            </div>
            <div class="container block-top-menu">
                <div class="flex-item box-block-top-menu">
                    <div class="block-logo">
                        <a href="../controller/index.php">
                            <img src="../view/resources/images/logonew.png" alt="">
                        </a>
                    </div>
                    <?php
                    require_once '../../model/connectDB.php';
                    $conn = connectdb();
                    define('BASE_URL', '../../');
                    ?>
                  
                    <div class="block-search">
                        <div class="box-block-search">
                            <form action="../controller/index.php" method="get">
                                <input type="hidden" name="pg" value="timkiem">
                                <div class="search-container" style="position: relative;">
                                    <input type="text" name="keyword" class="search-input" placeholder="Nhập từ khoá tìm kiếm" value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                                    <button type="submit" class="icon-search">
                                        <span>Tìm</span>
                                    </button>
                                    <button type="button" class="filter-button" id="filter-icon" title="Lọc tìm kiếm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                        </svg>
                                    </button>
                                </div>
                                <div class="filter-menu" id="filter-menu">
                                    <label for="category">Danh mục:</label>
                                    <select id="category" name="category">
                                        <option value="all">Tất cả</option>
                                        <?php
                                        $stmt = $conn->prepare("SELECT iddanhmuc, tendanhmuc FROM danhmuc WHERE trangthai = 1");
                                        $stmt->execute();
                                        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($categories as $category) {
                                            $selected = (isset($_GET['category']) && $_GET['category'] == $category['iddanhmuc']) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($category['iddanhmuc']) . '" ' . $selected . '>' . htmlspecialchars($category['tendanhmuc']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <label for="price-range">Khoảng giá:</label>
                                    <input type="number" id="min-price" name="min-price" placeholder="Giá thấp nhất" value="<?php echo isset($_GET['min-price']) ? htmlspecialchars($_GET['min-price']) : ''; ?>">
                                    <input type="number" id="max-price" name="max-price" placeholder="Giá cao nhất" value="<?php echo isset($_GET['max-price']) ? htmlspecialchars($_GET['max-price']) : ''; ?>">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="block-contact">
                        <i class=""><img src="../view/resources/images/icon/user.png" alt="" /></i>
                        <?php
                        if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
                            echo ' <span><a href="../controller/index.php?pg=thongtintaikhoan">' . $_SESSION['username'] . '</a></span>';
                        } else {
                            echo ' <span>Tài khoản</span>
                                  <div class="block-open-user">
                                      <ul>
                                          <li class="auth-item"><a href="../controller/index.php?pg=dangnhap"> Đăng nhập</a></li>
                                          <li class="auth-item"> <a href="../controller/index.php?pg=dangki">Đăng ký</a> </li>
                                      </ul>
                                  </div>';
                        }
                        ?>
                    </div>
                    <div class="block-card" id="block-card">
                        <i class="icon-cart-bold"><span id="cart-count">0</span></i>
                        <span>Giỏ hàng</span>
                        <div class="block-cart-buy">
                            <div class="block-card-scroll" id="cart-items-header"></div>
                            <div class="total-price-cart">
                                <p class="total-so-luong"><b>Số lượng:</b> <span id="total-quantity">0</span></p>
                                <p class="total-tien"><b>Tổng tiền:</b> <span id="total-cart-price">0</span></p>
                            </div>
                            <div class="block-action-buy-cart">
                                <button>
                                    <a href="../controller/index.php?pg=giohang">
                                        <i class="icon-cart"></i>
                                        Xem giỏ hàng
                                    </a>
                                </button>
                                <button>
                                    <a href="../controller/index.php?pg=thanhtoan"><i class="icon-dola"></i>
                                        Thanh toán</a>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="block-info">
                        <i class=""><img src="../view/resources/images/icon/icon_info.png" alt="" /></i>
                        <a href="../controller/index.php?pg=donhang"><span>Đơn hàng</span></a>
                    </div>
                    <?php
                    echo '<div class="block-logout">';
                    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
                        echo '<i class=""><img src="../view/resources/images/icon/icon-logout.png" alt="" /></i>';
                        echo '<a href="index.php?pg=logout"><span>Đăng xuất</span></a>';
                    }
                    echo '</div>';
                    ?>
                </div>
            </div>
        </header>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="../view/resources/js/suggestions.js"></script>
        <script src="../view/resources/js/search.js"></script>

        <script>
        var idKhachHang = <?php echo isset($_SESSION['idkhachhang']) ? $_SESSION['idkhachhang'] : 'null'; ?>;

        function loadCartItems() {
            $.ajax({
                url: '../controller/giohang.php?action=hienThi&idKhachHang=' + idKhachHang,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let cartItemsContainer = $('#cart-items-header');
                        let totalPrice = 0;
                        let totalDistinctProducts = response.data.length;
                        let totalQuantity = 0;

                        cartItemsContainer.empty();

                        response.data.forEach(function(item) {
                            let thanhTien = parseFloat(item.gia) * parseInt(item.soluong);
                            totalPrice += thanhTien;
                            totalQuantity += parseInt(item.soluong);

                            let cartItemHtml = `
                                <div class="box-item-buy">
                                    <div class="box-img-buy">
                                        <img src="../../${item.anhbia}" alt="${item.tensach}">
                                        <span class="total-item">${item.soluong}</span>
                                    </div>
                                    <div class="box-description-buy">
                                        <h1><a href="../controller/index.php?pg=chitietsp&id=${item.idsach}" class="book-link">${item.tensach}</a></h1>
                                        <p class="price-cart">${item.gia}</p>
                                    </div>
                                </div>
                            `;
                            cartItemsContainer.append(cartItemHtml);
                        });

                        $('#cart-count').text(totalDistinctProducts);
                        $('#total-quantity').text(totalQuantity);
                        $('#total-cart-price').text(totalPrice + ' VND');
                    } else {
                        $('#cart-items-header').html('<p>Không có sản phẩm trong giỏ hàng.</p>');
                        $('#cart-count').text('0');
                        $('#total-quantity').text('0');
                        $('#total-cart-price').text('0 VND');
                    }
                },
                error: function() {
                    $('#cart-items-header').html('<p>Có lỗi khi tải dữ liệu.</p>');
                    $('#cart-count').text('0');
                    $('#total-quantity').text('0');
                    $('#total-cart-price').text('0 VND');
                }
            });
        }

        $(document).ready(function() {
            loadCartItems();
        });
        
        </script>