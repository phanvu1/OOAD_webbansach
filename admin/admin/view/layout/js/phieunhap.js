function loadPage(page){
    fetch(`../controller/pagPhieuNhap.php?page=${page}`)
    .then(response=>{
        if(!response.ok){
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data =>{
        const tbody = document.getElementById('receipt-table-body');
        tbody.replaceChildren();
        //thêm dữ liệu từ data
        data.data.forEach(receipt =>{
            const row =
            `
                <tr>
                    <td>${receipt.idphieunhap}</td>
                    <td>${receipt.idnhacungcap}</td>
                    <td>${receipt.idnhanvien}</td>
                    <td>${receipt.ngaynhap}</td>
                    <td>${Number(receipt.tongtien).toLocaleString('vi-VN')}đ</td>
                    <td>${receipt.trangthai ==1 ? "Hoạt động" : "Đã hủy" }</td>
                    <td>
                        <a href="../controller/index.php?pg=chitietphieunhap&idphieunhap=${receipt.idphieunhap}">
                            <img src="../view/layout/logo-icon/detail-icon.png" alt="Xem chi tiết" style="width:20px; height:20px;">
                        </a>
                    </td>
                    <td>
                        ${receipt.trangthai ==1 ? `
                        <a href="../controller/index.php?pg=xoaphieunhap&idphieunhap=${receipt.idphieunhap}" onclick="return confirm('Bạn có chắc muốn xóa phiếu nhập ${receipt.idphieunhap} ?')">
                            <img src="../view/layout/logo-icon/delete-icon.png" alt="Xóa" style="width:20px; height:20px;">
                        </a>
                        ` : '<img src="../view/layout/logo-icon/delete-icon.png" alt="Xóa" style="width:20px; height:20px;">'}
                    </td>
                </tr>
            `;
            tbody.innerHTML+= row;

        })
        //cập nhật phân trang
        const pagination = document.getElementById('pagination');
        pagination.replaceChildren();
        for(let i =1; i <= data.totalPages ; i++){
            pagination.innerHTML+= 
            `
                <button data-page=${i} ${i == data.currentPage ? 'class= "active"' : ''}>${i}</button>
            `
        }

        //xử lý phân trang
        document.querySelectorAll("#pagination button").forEach(button=>{
            button.addEventListener("click", function(){
                const page = parseInt(this.getAttribute("data-page"));
                loadPage(page);
            })
        })


    })
    .catch(error =>console.error('Error fetching data', error));
}

function searchPhieuNhap(keyword, page=1){
    fetch(`../controller/pagPhieuNhap.php?search=${encodeURIComponent(keyword)}&page=${page}`)
    .then(response=>{
        if(!response.ok){
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data =>{
        const tbody = document.getElementById('receipt-table-body');
        tbody.replaceChildren();

        if(data.data.length === 0){
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" style="text-align: center;">Không tìm thấy phiếu nhập phù hợp!</td>
                </tr>
            `;

            document.getElementById("pagination").innerHTML = "";
            return;
        }

        //thêm dữ liệu từ data
        data.data.forEach(receipt =>{
            const row =
            `
                <tr>
                    <td>${receipt.idphieunhap}</td>
                    <td>${receipt.idnhacungcap}</td>
                    <td>${receipt.idnhanvien}</td>
                    <td>${receipt.ngaynhap}</td>
                    <td>${Number(receipt.tongtien).toLocaleString('vi-VN')}đ</td>
                    <td>${receipt.trangthai ==1 ? "Hoạt động" : "Đã hủy" }</td>
                    <td>
                        <a href="../controller/index.php?pg=chitietphieunhap&idphieunhap=${receipt.idphieunhap}">
                            <img src="../view/layout/logo-icon/detail-icon.png" alt="Xem chi tiết" style="width:20px; height:20px;">
                        </a>
                    </td>
                    <td>
                        ${receipt.trangthai ==1 ? `
                        <a href="../controller/index.php?pg=xoaphieunhap&idphieunhap=${receipt.idphieunhap}" onclick="return confirm('Bạn có chắc muốn xóa phiếu nhập ${receipt.idphieunhap} ?')">
                            <img src="../view/layout/logo-icon/delete-icon.png" alt="Xóa" style="width:20px; height:20px;">
                        </a>
                        ` : '<img src="../view/layout/logo-icon/delete-icon.png" alt="Xóa" style="width:20px; height:20px;">'}
                    </td>
                </tr>
            `;
            tbody.innerHTML+= row;

        })
        //cập nhật phân trang
        const pagination = document.getElementById('pagination');
        pagination.replaceChildren();
        for(let i =1; i <= data.totalPages ; i++){
            pagination.innerHTML+= 
            `
                <button data-page=${i} ${i == data.currentPage ? 'class= "active"' : ''}>${i}</button>
            `
        }

        //xử lý phân trang
        document.querySelectorAll("#pagination button").forEach(button=>{
            button.addEventListener("click", function(){
                const page = parseInt(this.getAttribute("data-page"));
                searchPhieuNhap(keyword, page);
            })
        })


    })
    .catch(error =>console.error('Error fetching data', error));
}

function applyFiltersPN() {
    const startDate = document.getElementById("start").value;
    const endDate = document.getElementById("end").value;

    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);

        if (start > end) {
            alert("Ngày bắt đầu không được lớn hơn ngày kết thúc!");
            return; 
        }

        loadReceiptData(startDate, endDate);
    } else {
        alert("Vui lòng chọn khoảng thời gian!");
    }
}

function loadReceiptData(startDate, endDate, page = 1) {
    fetch(`../controller/pagPhieuNhap.php?page=${page}&start=${startDate}&end=${endDate}`)
        .then(response => response.json())
        .then(data =>{
            const tbody = document.getElementById('receipt-table-body');
            tbody.replaceChildren();

            if(data.data.length === 0){
                const row = 
                `
                    <tr>
                        <td colspan="10" style="text-align: center;">Không có phiếu nhập nào!</td>
                    </tr>
                `;
                tbody.innerHTML = row;
                return;
            }

            //thêm dữ liệu từ data
            data.data.forEach(receipt =>{
                const row =
                `
                    <tr>
                        <td>${receipt.idphieunhap}</td>
                        <td>${receipt.idnhacungcap}</td>
                        <td>${receipt.idnhanvien}</td>
                        <td>${receipt.ngaynhap}</td>
                        <td>${Number(receipt.tongtien).toLocaleString('vi-VN')}đ</td>
                        <td>${receipt.trangthai ==1 ? "Hoạt động" : "Đã hủy" }</td>
                        <td>
                            <a href="../controller/index.php?pg=chitietphieunhap&idphieunhap=${receipt.idphieunhap}">
                                <img src="../view/layout/logo-icon/detail-icon.png" alt="Xem chi tiết" style="width:20px; height:20px;">
                            </a>
                        </td>
                        <td>
                            ${receipt.trangthai ==1 ? `
                            <a href="../controller/index.php?pg=xoaphieunhap&idphieunhap=${receipt.idphieunhap}" onclick="return confirm('Bạn có chắc muốn xóa phiếu nhập ${receipt.idphieunhap} ?')">
                                <img src="../view/layout/logo-icon/delete-icon.png" alt="Xóa" style="width:20px; height:20px;">
                            </a>
                            ` : '<img src="../view/layout/logo-icon/delete-icon.png" alt="Xóa" style="width:20px; height:20px;">'}
                        </td>
                    </tr>
                `;
                tbody.innerHTML+= row;
    
            })
            //cập nhật phân trang
            const pagination = document.getElementById('pagination');
            pagination.replaceChildren();
            for(let i =1; i <= data.totalPages ; i++){
                pagination.innerHTML+= 
                `
                    <button data-page=${i} ${i == data.currentPage ? 'class= "active"' : ''}>${i}</button>
                `
            }
    
            //xử lý phân trang
            document.querySelectorAll("#pagination button").forEach(button=>{
                button.addEventListener("click", function(){
                    const page = parseInt(this.getAttribute("data-page"));
                    loadPage(page);
                })
            })
    
    
        })
        .catch(error =>console.error('Error fetching data', error));
}


//load khi được tải
document.addEventListener('DOMContentLoaded', function(){
    loadPage(1);
});

document.getElementById("searchPN").addEventListener("input", function(){
    const keyword = this.value.trim();
    if(keyword !== ""){
        searchPhieuNhap(keyword);
    } else {
        loadPage(1);
    }
});
