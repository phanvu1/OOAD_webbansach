let lastScrollTop = 0;
const topHeader = document.querySelector(".top-header");
const header = document.querySelector("header");

window.addEventListener("scroll", function () {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (scrollTop > lastScrollTop) {
        // Cuộn xuống
        topHeader.style.top = "-29px"; // Ẩn top-header
        header.style.top = "0"; // Header dính lên top
    } else {
        // Cuộn lên
        topHeader.style.top = "0"; // Hiển thị lại top-header
        header.style.top = "30px"; // Header nằm dưới top-header
    }

    lastScrollTop = scrollTop; // Cập nhật vị trí cuộn cuối cùng
});
