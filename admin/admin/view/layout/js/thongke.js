
// // Initialize the chart
// var ctx = document.getElementById('myChart').getContext('2d');
// var myChart = new Chart(ctx, {
//     type: 'bar', // type: 'line' biểu đồ đường, type: 'bar' nếu biểu đồ cột
//     data: {
//         labels: [],
//         datasets: [{
//             label: 'Tổng Mua',
//             data: [],
//             backgroundColor: [
//                 'rgba(75, 192, 192, 0.2)', 
//                 'rgba(54, 162, 235, 0.2)', 
//                 'rgba(255, 206, 86, 0.2)', 
//                 'rgba(255, 99, 132, 0.2)',
//                 'rgba(153, 102, 255, 0.2)'
//             ],
//             borderColor: [
//                 'rgba(75, 192, 192, 1)',
//                 'rgba(54, 162, 235, 1)',
//                 'rgba(255, 206, 86, 1)',
//                 'rgba(255, 99, 132, 1)', 
//                 'rgba(153, 102, 255, 1)'
//             ],
//             borderWidth: 1
//             // borderColor: 'rgba(54, 162, 235, 1)',
//             // borderWidth: 2,
//             // fill: false, 
//             // tension: 0.4 
//         }]
//     },
//     options: {
//         scales: {
//             y: {
//                 beginAtZero: true
//             }
//         }
//     }
// });

// // Placeholder function for fetching data
// function fetchData(startDate, endDate) {
//     return [
//         { customer: 'Khách Hàng 1', orders: ['Đơn Hàng 1', 'Đơn Hàng 2'], total: 500 },
//         { customer: 'Khách Hàng 2', orders: ['Đơn Hàng 3'], total: 300 },
//         { customer: 'Khách Hàng 3', orders: ['Đơn Hàng 4', 'Đơn Hàng 5', 'Đơn Hàng 6'], total: 800 },
//         { customer: 'Khách Hàng 4', orders: ['Đơn Hàng 7'], total: 200 },
//         { customer: 'Khách Hàng 5', orders: ['Đơn Hàng 8', 'Đơn Hàng 9'], total: 600 },
//         // Add more data as needed
//     ];
// }

// document.getElementById('thongke-form').addEventListener('submit', function(event) {
//     event.preventDefault();
    
//     var startDate = document.getElementById('start-date').value;
//     var endDate = document.getElementById('end-date').value;
//     var results = fetchData(startDate, endDate);
    
//     // Sort results by total purchase amount in descending order
//     results.sort((a, b) => b.total - a.total);
    
//     // Get top 5 customers
//     results = results.slice(0, 5);
    
//     // Update table
//     var resultTable = document.getElementById('result-table');
//     resultTable.innerHTML = '';
    
//     results.forEach(function(result) {
//         var orders = result.orders.map(order => `<a href="../controller/index.php?pg=chitiethoadon&order=${order}">${order}</a>`).join(', ');
//         var row = `
//             <tr>
//                 <td>${result.customer}</td>
//                 <td>${orders}</td>
//                 <td>${result.total}</td>
//             </tr>
//         `;
//         resultTable.innerHTML += row;
//     });

//     // Update chart
//     myChart.data.labels = results.map(result => result.customer);
//     myChart.data.datasets[0].data = results.map(result => result.total);
//     myChart.update();
// });

document.addEventListener('DOMContentLoaded', function () {
    // Cập nhật bảng
    function updateTable(data, topKhachHang, sortOrder = 'DESC') {
        const table = document.getElementById('result-table');
        table.innerHTML = ""; 

        if (data.length === 0) {
            table.innerHTML = "<tr><td colspan='3' class='text-center'>Không có dữ liệu</td></tr>";
        } else {
            // Nhóm dữ liệu theo khách hàng
            let groupedData = {};

            data.forEach((item) => {
                const ma_kh = item.ten;  // Dùng tên khách hàng để nhóm
                const tongMua = parseFloat(item.tong_mua);
                const formattedTongMua = tongMua.toLocaleString('vi-VN', {
                    style: 'currency',
                    currency: 'VND',
                    minimumFractionDigits: 0
                });

                // Nếu khách hàng chưa có trong nhóm, khởi tạo
                if (!groupedData[ma_kh]) {
                    groupedData[ma_kh] = {
                        ten: item.ten,
                        tong_mua: 0,
                        ds_don_hang: []
                    };
                }

                // Cộng dồn tổng tiền
                groupedData[ma_kh].tong_mua += tongMua;
                // Thêm các đơn hàng của khách vào danh sách
                groupedData[ma_kh].ds_don_hang = groupedData[ma_kh].ds_don_hang.concat(item.ds_don_hang);
            });

            // Tạo nội dung bảng từ groupedData
            let tableContent = '';
            const sortedData = Object.values(groupedData).sort((a, b) => {
                return sortOrder === 'ASC' ? a.tong_mua - b.tong_mua : b.tong_mua - a.tong_mua;
            });            

            // Lọc ra top khách hàng nếu có
            const topData = sortedData.slice(0, topKhachHang);

            topData.forEach((item) => {
                const formattedTongMua = item.tong_mua.toLocaleString('vi-VN', {
                    style: 'currency',
                    currency: 'VND',
                    minimumFractionDigits: 0
                });

                // Tạo danh sách đơn hàng theo dạng "Đơn hàng 1 - ID, Đơn hàng 2 - ID"
                const dsDon = item.ds_don_hang.map((id, index) => {
                    return `<a href="../controller/index.php?pg=xetduyetHD&idhoadon=${id}&from=thongke" class="link-donhang">
                                ${index + 1} - ID: ${id}
                            </a>`;
                }).join(', ');

                tableContent += `
                    <tr>
                        <td>${item.ten}</td>
                        <td>${dsDon}</td>
                        <td>${item.ds_don_hang.length}</td>
                        <td>${formattedTongMua}</td>
                    </tr>
                `;
            });

            // Cập nhật nội dung bảng một lần duy nhất
            table.innerHTML = tableContent;
        }
    }    

    // Vẽ biểu đồ
    function updateChart(data, topKhachHang, sortOrder = 'DESC') {
        let groupedData = {};

        data.forEach((item) => {
            const ma_kh = item.ten;
            const tongMua = parseFloat(item.tong_mua);

            if (!groupedData[ma_kh]) {
                groupedData[ma_kh] = {
                    ten: item.ten,
                    tong_mua: 0
                };
            }

            groupedData[ma_kh].tong_mua += tongMua;
        });

        // Lấy mảng các khách hàng đã nhóm
        let labels = Object.values(groupedData).map(function(item) { return item.ten; });
        let values = Object.values(groupedData).map(function(item) { return item.tong_mua; });

        // Sắp xếp và lọc dữ liệu theo top khách hàng
        const sortedData = Object.values(groupedData).sort((a, b) => {
            return sortOrder === 'ASC' ? a.tong_mua - b.tong_mua : b.tong_mua - a.tong_mua;
        });        
        const topData = sortedData.slice(0, topKhachHang);
        labels = topData.map(function(item) { return item.ten; });
        values = topData.map(function(item) { return item.tong_mua; });

        // Mảng màu tùy chỉnh cho các thanh trong biểu đồ
        let colors = labels.map(function() {
            var colorsArray = [
                'rgba(255, 99, 132, 0.6)',  // Màu đỏ
                'rgba(54, 162, 235, 0.6)',  // Màu xanh dương
                'rgba(255, 206, 86, 0.6)',  // Màu vàng
                'rgba(75, 192, 192, 0.6)',  // Màu xanh lá
                'rgba(153, 102, 255, 0.6)', // Màu tím
                'rgba(255, 159, 64, 0.6)'   // Màu cam
            ];

            return colorsArray[Math.floor(Math.random() * colorsArray.length)];
        });

        // Lấy đối tượng canvas để vẽ biểu đồ
        var ctx = document.getElementById('myChart').getContext('2d');

        // Nếu biểu đồ đã tồn tại, hủy nó và tạo mới
        if (window.myChart instanceof Chart) {
            window.myChart.destroy();  // Hủy biểu đồ hiện tại nếu có
        }

        // Tạo mới biểu đồ
        window.myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels, // Tên khách hàng làm nhãn
                datasets: [{
                    label: 'Tổng Mua (VND)',
                    data: values, // Tổng tiền mua của từng khách hàng
                    backgroundColor: colors, // Màu sắc các thanh
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }    

    // Hàm chuyển đổi định dạng dd/mm/yyyy -> yyyy-mm-dd
    function convertDateFormat(dateString) {
        var parts = dateString.split('/');
        if (parts.length === 3) {
            return parts[2] + '-' + parts[1] + '-' + parts[0];
        }
        return '';
    }

    document.getElementById('thongke-form').addEventListener('submit', function(event) {
        event.preventDefault();  // Ngăn submit form mặc định

        var startDate = document.getElementById('start-date').value;
        var endDate = document.getElementById('end-date').value;
        var topKhachHang = parseInt(document.getElementById('top-kh').value);  // Lấy số lượng khách hàng cần liệt kê
        var sortOrder = document.getElementById('sort-order').value || 'DESC';

        console.log("Start Date: ", startDate);
        console.log("End Date: ", endDate);
        console.log("Top Khach Hang: ", topKhachHang);
        console.log("Sap xep tong mua: ", sortOrder);

        if (!startDate || !endDate) {
            alert("Vui lòng chọn khoảng thời gian!");
            return;
        }

        if (startDate > endDate) {
            alert("Ngày bắt đầu không được lớn hơn ngày kết thúc!");
            return;
        }

        if (startDate.includes('/')) startDate = convertDateFormat(startDate);
        if (endDate.includes('/')) endDate = convertDateFormat(endDate);

        // Gửi dữ liệu qua Ajax POST
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../controller/pagThongKe.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        var params = "start-date=" + encodeURIComponent(startDate) + "&end-date=" + encodeURIComponent(endDate);
        console.log("Sending Params: ", params);
        xhr.send(params);

        // Xử lý phản hồi
        xhr.onload = function() {
            if (xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                console.log("Response:", response);  // Kiểm tra nội dung phản hồi

                if (response.status == 'success') {
                    // Kiểm tra xem 'data' có phải là mảng không
                    if (Array.isArray(response.data)) {
                        updateTable(response.data, topKhachHang, sortOrder);  // Gọi hàm updateTable nếu data là mảng
                        updateChart(response.data, topKhachHang, sortOrder);

                        sessionStorage.setItem('dulieuThongKe', JSON.stringify(response.data));
                        sessionStorage.setItem('startDate', startDate);
                        sessionStorage.setItem('endDate', endDate);
                    } else {
                        console.error("Dữ liệu không phải là mảng: ", response.data);
                    }
                } else {
                    alert(response.message);
                }
            } else {
                alert("Lỗi khi gửi yêu cầu!");
            }
        };
    });

    // Khi DOM load, kiểm tra sessionStorage
    const duLieuDaLuu = sessionStorage.getItem('dulieuThongKe');
    if (duLieuDaLuu) {
        const data = JSON.parse(duLieuDaLuu);
        const topKhachHang = parseInt(document.getElementById('top-kh').value) || 5;
        updateTable(data, topKhachHang);
        updateChart(data, topKhachHang);
    }

    const savedStartDate = sessionStorage.getItem('startDate');
    const savedEndDate = sessionStorage.getItem('endDate');

    if (savedStartDate) document.getElementById('start-date').value = savedStartDate;
    if (savedEndDate) document.getElementById('end-date').value = savedEndDate;
});



