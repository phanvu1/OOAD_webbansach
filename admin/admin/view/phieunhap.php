<?php
include_once '../../model/connectDB.php';
include_once '../../model/phieunhap.php';
?>


        <!-- Main Content -->
        <div class="right-content">
            <div class="tab-content active">
                <h2>Danh sách Phiếu nhập</h2>
                <div class="actions">
                    <input type="text" id="searchPN" name="search" placeholder="Nhập mã phiếu nhập để tìm kiếm...">
                    <button onclick="location.href='../controller/index.php?pg=themphieunhap';">Thêm</button>
                </div>
                <!-- Bộ lọc -->
                <div class="filter">
                    <div class="filter-item">
                        <label for="startDate">Từ ngày:</label>
                        <input type="date" id="start" name="start" placeholder="Từ ngày">
                    </div>
                    <div class="filter-item">
                        <label for="endDate">Đến ngày:</label>
                        <input type="date" id="end" name="end" placeholder="Đến ngày">
                    </div>
                    <button id="filterBtn" onclick="applyFiltersPN()">Lọc</button>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nhà cung cấp</th>
                            <th>Nhân viên</th>
                            <th>Ngày nhập</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Xem chi tiết</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody id="receipt-table-body">
                        
                    </tbody>
                </table>

                <!-- Phân trang -->
                <div class="phanTrang" id="pagination">
                </div>
            </div>
        </div>
    </div>
    <script src="../view/layout/js/phieunhap.js"></script>
</body>

</html>
