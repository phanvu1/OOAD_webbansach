function loadPage(page){
    fetch(`../controller/pagHoaDon.php?page=${page}`)
    .then(response=>{
        if(!response.ok){
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data =>{
        const tbody = document.getElementById('invoice-table-body');
        tbody.replaceChildren();
        //thêm dữ liệu từ data
        data.data.forEach(invoice =>{
            const row =
            `
            <tr>
                <td>${invoice.idhoadon}</td>
                <td>${invoice.idkhachhang}</td>
                <td>${invoice.thanhpho}</td>
                <td>${invoice.idnhanvien}</td>
                <td>${invoice.phuongthuctt}</td>
                <td>${invoice.ngayxuat}</td>
                <td>${Number(invoice.tongtien).toLocaleString('vi-VN')}đ</td>
                <td>
                     ${
                         (() => {
                         const statusInt = parseInt(invoice.trangthai); switch (statusInt) {
                             case 0: return "Chờ xác nhận";
                             case 1: return "Xác nhận";
                             case 2: return "Đã giao thành công";
                             case 3: return "Đã hủy";
                             default: return "";
                         }
                         })()
                     }
                 </td>
                <td>
                    <a href="../controller/index.php?pg=xetduyetHD&idhoadon=${invoice.idhoadon}&iddiachi=${invoice.iddiachi}">
                        <img src="../view/layout/logo-icon/update-icon.png" alt="Xét duyệt" style="width:20px; height:20px;">
                    </a>
                </td>
            </tr>
            `;
            tbody.innerHTML+= row;
        });
        //cập nhật phân trang
        const pagination = document.getElementById('pagination_invoice');
        pagination.replaceChildren();
        for(let i =1; i <= data.totalPages ; i++){
            pagination.innerHTML+= 
            `
                <button data-page=${i} ${i == data.currentPage ? 'class= "active"' : ''}>${i}</button>
            `
        }

        //xử lý phân trang
        document.querySelectorAll("#pagination_invoice button").forEach(button=>{
            button.addEventListener("click", function(){
                const page = parseInt(this.getAttribute("data-page"));
                loadPage(page);
            })
        });
    })
    .catch(error =>console.error('Error fetching data', error));


}

function searchHoaDon(keyword, page=1){
    fetch(`../controller/pagHoaDon.php?search=${encodeURIComponent(keyword)}&page=${page}`)
    .then(response=>{
        if(!response.ok){
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data =>{
        const tbody = document.getElementById('invoice-table-body');
        tbody.replaceChildren();

        if(data.data.length === 0){
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" style="text-align: center;">Không tìm thấy đơn hàng phù hợp!</td>
                </tr>
            `;

            document.getElementById("pagination_invoice").innerHTML = "";
            return;
        }

        //thêm dữ liệu từ data
        data.data.forEach(invoice =>{
            const row =
            `
            <tr>
                <td>${invoice.idhoadon}</td>
                <td>${invoice.idkhachhang}</td>
                <td>${invoice.thanhpho}</td>
                <td>${invoice.idnhanvien}</td>
                <td>${invoice.phuongthuctt}</td>
                <td>${invoice.ngayxuat}</td>
                <td>${Number(invoice.tongtien).toLocaleString('vi-VN')}đ</td>
                <td>
                     ${
                         (() => {
                         const statusInt = parseInt(invoice.trangthai); switch (statusInt) {
                             case 0: return "Chờ xác nhận";
                             case 1: return "Xác nhận";
                             case 2: return "Đã giao thành công";
                             case 3: return "Đã hủy";
                             default: return "";
                         }
                         })()
                     }
                 </td>
                <td>
                    <a href="../controller/index.php?pg=xetduyetHD&idhoadon=${invoice.idhoadon}&iddiachi=${invoice.iddiachi}">
                        <img src="../view/layout/logo-icon/update-icon.png" alt="Xét duyệt" style="width:20px; height:20px;">
                    </a>
                </td>
            </tr>
            `;
            tbody.innerHTML+= row;
        });
        //cập nhật phân trang
        const pagination = document.getElementById('pagination_invoice');
        pagination.replaceChildren();
        for(let i =1; i <= data.totalPages ; i++){
            pagination.innerHTML+= 
            `
                <button data-page=${i} ${i == data.currentPage ? 'class= "active"' : ''}>${i}</button>
            `
        }

        //xử lý phân trang
        document.querySelectorAll("#pagination_invoice button").forEach(button=>{
            button.addEventListener("click", function(){
                const page = parseInt(this.getAttribute("data-page"));
                searchHoaDon(keyword, page);
            })
        });
    })
    .catch(error =>console.error('Error fetching data', error));


}

function getStatusCode(statusText) {
    switch (statusText) {
        case "0": return 0;
        case "1": return 1;
        case "2": return 2;
        case "3": return 3;
        default: return "";
    }
}

function applyFiltersHD() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const city = document.getElementById('filterCity').value;
    const statusRaw = document.getElementById('filterStatus').value;
    const status = getStatusCode(statusRaw);    

    const hasDateRange = startDate && endDate;
    const hasCity = city.trim() !== "";
    const hasStatus = status !== "";

    if (hasDateRange) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        if (start > end) {
            alert("Ngày bắt đầu không được lớn hơn ngày kết thúc!");
            return;
        }
    }

    if (hasDateRange || hasCity || hasStatus) {
        filterData(startDate, endDate, city, status);
    } else {
        alert("Vui lòng chọn ít nhất một điều kiện lọc (khoảng thời gian, thành phố hoặc tình trạng đơn hàng)!");
    }
}

function filterData(startDate, endDate, city, status, page = 1) {
    const params = new URLSearchParams();

    if (startDate && endDate) {
        params.append("startDate", startDate);
        params.append("endDate", endDate);
    }

    if (city && city.trim() !== "") {
        params.append("city", city.trim());
    }

    if (status !== null && status !== undefined && status !== "") {
        params.append("status", status);
    }    
    
    params.append("page", page);

    fetch(`../controller/pagHoaDon.php?${params.toString()}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const tbody = document.getElementById('invoice-table-body');
            tbody.replaceChildren();

            if (data.data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" style="text-align: center;">Không có đơn hàng nào phù hợp!</td>
                    </tr>
                `;
                document.getElementById("pagination_invoice").innerHTML = "";
                return;
            }

            data.data.forEach(invoice => {
                const row = `
                    <tr>
                        <td>${invoice.idhoadon}</td>
                        <td>${invoice.idkhachhang}</td>
                        <td>${invoice.thanhpho}</td>
                        <td>${invoice.idnhanvien}</td>
                        <td>${invoice.phuongthuctt}</td>
                        <td>${invoice.ngayxuat}</td>
                        <td>${Number(invoice.tongtien).toLocaleString('vi-VN')}đ</td>
                        <td>
                            ${
                                (() => {
                                    const statusInt = parseInt(invoice.trangthai); switch (statusInt) {
                                        case 0: return "Chờ xác nhận";
                                        case 1: return "Xác nhận";
                                        case 2: return "Đã giao thành công";
                                        case 3: return "Đã hủy";
                                        default: return "";
                                    }
                                })()
                            }
                        </td>
                        <td>
                            <a href="../controller/index.php?pg=xetduyetHD&idhoadon=${invoice.idhoadon}&iddiachi=${invoice.iddiachi}">
                                <img src="../view/layout/logo-icon/update-icon.png" alt="Xét duyệt" style="width:20px; height:20px;">
                            </a>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });

            // cập nhật phân trang
            const pagination = document.getElementById('pagination_invoice');
            pagination.replaceChildren();
            for (let i = 1; i <= data.totalPages; i++) {
                pagination.innerHTML += `
                    <button data-page="${i}" ${i === data.currentPage ? 'class="active"' : ''}>${i}</button>
                `;
            }

            // xử lý phân trang
            document.querySelectorAll("#pagination_invoice button").forEach(button => {
                button.addEventListener("click", function () {
                    const selectedPage = parseInt(this.getAttribute("data-page"));
                    filterData(startDate, endDate, city, status, selectedPage);
                });
            });
        })
        .catch(error => console.error('Lỗi khi lọc dữ liệu: ', error));
}

function loadCityOptions() {
    fetch('../view/layout/data/tinh_tp.json') 
        .then(response => response.json())
        .then(data => {
            const cityList = document.getElementById("cityList");
            for (const code in data) {
                const option = document.createElement("option");
                option.value = data[code].name_with_type; 
                cityList.appendChild(option);
            }
        })
        .catch(error => console.error("Lỗi khi tải danh sách tỉnh/thành: ", error));
}

// Load khi trang được tải
document.addEventListener('DOMContentLoaded', function () {
    loadPage(1); 
    loadCityOptions();
});

document.getElementById("searchHD").addEventListener("input", function(){
    const keyword = this.value.trim();
    if(keyword !== ""){
        searchHoaDon(keyword);
    } else {
        loadPage(1);
    }
});

