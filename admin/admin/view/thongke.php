<?php
    include_once '../../model/connectDB.php';
    include_once '../../model/hoadon.php';
    $maxKhachHang = getSoLuongKhachMuaHang();
?>

    <link rel="stylesheet" href="../view/layout/css/thongke.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Nội dung chính -->
    <div class="container my-4 main-container">
        <h2 class="text-center">Thống Kê</h1>

            <form action="../controller/index.php?pg=thongke" method="POST" id="thongke-form">
                <div class="form-group">
                    <label for="start-date">Ngày Bắt Đầu</label>
                    <input type="date" class="form-control" id="start-date" name="start-date" required>
                    <label for="end-date">Ngày Kết Thúc</label>
                    <input type="date" class="form-control" id="end-date" name="end-date" required>
                    <label for="top-kh" class="mt-2">Số lượng khách hàng cần liệt kê</label>
                    <input type="number" class="form-control" id="top-kh" name="top-kh" min="1" 
                            max="<?= $maxKhachHang ?>" value="<?= $_POST['top-kh'] ?? $maxKhachHang ?>" required>
                    <label for="sort-order" class="mt-2">Sắp xếp theo tổng mua</label>
                    <select class="form-control" id="sort-order" name="sort-order" required>
                        <option value="DESC" <?= (isset($_POST['sort-order']) && $_POST['sort-order'] == 'DESC') ? 'selected' : '' ?>>Giảm dần</option>
                        <option value="ASC" <?= (isset($_POST['sort-order']) && $_POST['sort-order'] == 'ASC') ? 'selected' : '' ?>>Tăng dần</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Thống Kê</button>
                </div>
            </form>

            <!-- Chart Container -->
            <div class="chart-container">
                <canvas id="myChart"></canvas>
            </div>

            <!-- Kết quả thống kê -->
            <div class="table-responsive my-4 table-scroll">
                <table class="table table-bordered fixed-header">
                    <thead class="text-primary">
                        <tr>
                            <th>Khách Hàng</th>
                            <th>Đơn Hàng</th>
                            <th>Tổng Đơn Hàng</th>
                            <th>Tổng Mua</th>
                        </tr>
                    </thead>
                    <tbody id="result-table">
                        <!-- Kết quả sẽ được hiển thị ở đây -->
                    </tbody>
                </table>
            </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../view/layout/js/thongke.js"></script>
</body>

</html>
