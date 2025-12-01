<div class="toast-container" id="toast-container"></div> 
<aside class="block-main-content">
    <div class="box-block-main-content">
    <div class="login-register">
        <!-- Form đăng kí -->
        <div class="register-container">
            <h2>Đăng Ký</h2>
            <form id="register-form" method="POST" class="register-form">
                <div class="form-row">
                    <!-- Cột trái -->
                    <div class="form-column">
                        <div class="form-group">
                            <label for="register-name">Họ và tên :</label>
                            <input type="text" id="register-name" name="name" placeholder="Họ và tên" required>
                        </div>
                        <div class="form-group">
                            <label for="register-email">Email :</label>
                            <input type="email" id="register-email" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <label for="register-sdt">Số điện thoại :</label>
                            <input type="tel" id="register-sdt" name="sdt" placeholder="Số điện thoại" required>
                        </div>
                    </div>
                    <!-- Cột phải -->
                    <div class="form-column">
                        <div class="form-group">
                            <label for="register-username">Tên đăng nhập :</label>
                            <input type="text" id="register-username" name="username" placeholder="Tên đăng nhập" required>
                        </div>
                        <div class="form-group">
                            <label for="register-password">Mật khẩu :</label>
                            <input type="password" id="register-password" name="password" placeholder="Mật khẩu" required>
                        </div>
                        <div class="form-group">
                            <label for="repeat-password">Nhập lại mật khẩu :</label>
                            <input type="password" id="repeat-password" name="repeat-password" placeholder="Nhập lại mật khẩu" required>
                        </div>
                    </div>
                </div>
                <button type="submit">Đăng Ký</button>
            </form>
            <!-- Bỏ div#register-message vì sẽ dùng toast -->
        </div>
    </div>
    </div>
</aside>
<script>
window.onload = function() {
    document.getElementById('register-form').addEventListener('submit', function(e) {
        e.preventDefault();
        console.log("Form submitted");

        const password = document.getElementById('register-password').value;
        const repeatPassword = document.getElementById('repeat-password').value;

        if (password !== repeatPassword) {
            showToast("Mật khẩu và mật khẩu nhập lại không khớp!", "error");
            console.log("Password mismatch");
            return;
        }

        const formData = new FormData(this);

        fetch('../controller/register.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log("Fetch response:", response);
            return response.json();
        })
        .then(data => {
            console.log("Fetch data:", data);
            if (data.success) {
                showToast(data.message, "success");
                document.getElementById('register-form').reset();
                
                setTimeout(() => {
                    window.location.href = "../controller/index.php";
                }, 2000); 
               
            } else {
                showToast(data.message, "error");
            }
        })
        .catch(error => {
            console.log("Fetch error:", error);
            showToast("Có lỗi xảy ra, vui lòng thử lại!", "error");
        });
    });
};
</script>