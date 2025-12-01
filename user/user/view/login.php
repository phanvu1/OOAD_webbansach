<aside class="block-main-content">
    <div class="box-block-main-content">
        <?php if (isset($error_message)): ?>
            <div style="background-color: #f2dede; color: #a94442; padding: 10px; border: 1px solid #ebccd1; border-radius: 4px; margin-bottom: 10px; text-align: center;">
                <?= htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <div class="login-register">
            <!-- Form Đăng Nhập -->
            <div class="login-container">

                <h2>Đăng Nhập</h2>
                <form action="index.php?pg=dangnhap" method="POST">
                    <label for="login-username">Tên đăng nhập:</label>
                    <input type="text" id="login-username" name="username"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                        placeholder="Tên đăng nhập" required>

                    <label for="login-password">Mật khẩu:</label>
                    <input type="password" id="login-password" name="password"
                        value="<?= htmlspecialchars($_POST['password'] ?? '') ?>"
                        placeholder="Mật khẩu" required>
                    <button type="submit" name="login" value="Đăng nhập">Đăng nhập</button>
                </form>
                <span>Bạn chưa có tài khoản? <a href="index.php?pg=dangki" class="register-link">Đăng ký
                        ngay</a></span>

            </div>
        </div>

    </div>
</aside>
</div>
