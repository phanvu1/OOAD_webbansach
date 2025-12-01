<?php
// Kiểm tra và gán giá trị mặc định nếu $user không tồn tại hoặc không phải mảng
$user = isset($user) && is_array($user) ? $user : ['ten' => '', 'email' => '', 'sodienthoai' => '', 'tendangnhap' => '', 'tongchitieu' => 0];
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<article class="container block-content block-content-news">
    <div class="flex-item">
        <aside class="block-main-content">
            <div class="box-block-main-content">
                <h1 class="text-title">Thông tin</h1>
                <div class="error" id="message-box"></div>
                <div class="block-thongtin-TK">
                    <div class="block-left-thongtin">
                        <h2 class="text-title-thongtin">Thông tin cá nhân</h2>
                        <div class="info-container">
                            <div class="item-infor">
                                <p>Họ và tên: </p>
                                <span id="user-name"><?php echo htmlspecialchars($user['ten']); ?></span>
                            </div>
                            <div class="item-infor">
                                <p>Email: </p>
                                <span id="user-email"><?php echo htmlspecialchars($user['email']); ?></span>
                            </div>
                            <div class="item-infor">
                                <p>Số điện thoại: </p>
                                <span id="user-phone"><?php echo htmlspecialchars($user['sodienthoai']); ?></span>
                            </div>
                            <div class="sua" onclick="openModal('edit-info-modal')">
                                <img src="../view/resources/images/icon/icon-edit.png" alt=""><span>Sửa</span>
                            </div>
                        </div>
                    </div>
                    <div class="block-right-thongtin">
                        <h2 class="text-title-thongtin">Thông tin tài khoản</h2>
                        <div class="info-container">
                            <div class="item-infor">
                                <p>Tài khoản: </p>
                                <span><?php echo htmlspecialchars($user['tendangnhap']); ?></span>
                            </div>
                            <div class="item-infor">
                                <p>Tổng chi tiêu: </p>
                                <span><?php echo number_format($user['tongchitieu'], 0, ',', '.') . 'đ'; ?></span>
                            </div>
                            <div class="sua" onclick="openModal('change-password-modal')">
                                <span>Đổi mật khẩu</span>
                            </div>
                        </div>
                    </div>
                    <div class="toast-container" id="toast-container"></div> 
                    <!-- Modal sửa thông tin cá nhân -->
                    <div id="edit-info-modal" class="modal">
                        <div class="modal-content">
                            <span class="close-btn" onclick="closeModal('edit-info-modal')">×</span>
                            <h2 class="text-title-suathongtin">Sửa thông tin cá nhân</h2>
                            <div class="form-container">
                                <form id="edit-info-form">
                                    <input type="hidden" name="action" value="save_personal_info">
                                    <div class="form-item">
                                        <label for="full-name">Họ và tên</label>
                                        <input type="text" id="full-name" name="full_name" value="<?php echo htmlspecialchars($user['ten']); ?>" required>
                                    </div>
                                    <div class="form-item">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                    </div>
                                    <div class="form-item">
                                        <label for="phone">Số điện thoại</label>
                                        <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" value="<?php echo htmlspecialchars($user['sodienthoai']); ?>" required>
                                    </div>
                                    <div class="form-actions">
                                        <button type="button" class="cancel-btn" onclick="closeModal('edit-info-modal')">Hủy</button>
                                        <button type="submit" class="save-btn">Lưu</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal đổi mật khẩu -->
                    <div id="change-password-modal" class="modal">
                        <div class="modal-content">
                            <span class="close-btn" onclick="closeModal('change-password-modal')">×</span>
                            <h2 class="text-title-suathongtin">Đổi mật khẩu</h2>
                            <div class="form-container">
                                <form id="change-password-form">
                                    <input type="hidden" name="action" value="change_password">
                                    <div class="form-item">
                                        <label for="current-password">Mật khẩu hiện tại</label>
                                        <input type="password" id="current-password" name="current_password" required>
                                    </div>
                                    <div class="form-item">
                                        <label for="new-password">Mật khẩu mới</label>
                                        <input type="password" id="new-password" name="new_password" minlength="6" required>
                                    </div>
                                    <div class="form-item">
                                        <label for="confirm-password">Xác nhận mật khẩu mới</label>
                                        <input type="password" id="confirm-password" name="confirm_password" minlength="6" required>
                                    </div>
                                    <div class="form-actions">
                                        <button type="button" class="cancel-btn" onclick="closeModal('change-password-modal')">Hủy</button>
                                        <button type="submit" class="save-btn">Lưu</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        </aside>
    <!-- </div> -->
<script>
        $(document).ready(function() {
            // Xử lý form sửa thông tin cá nhân và đổi mật khẩu
            $('#edit-info-form, #change-password-form').submit(function(e) {
                e.preventDefault();
                console.log($(this).serialize()); // Debug dữ liệu gửi đi
                $.ajax({
                    url: '../controller/taikhoanUser.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        console.log(response); 
                        if (response.success) {
                            showToast(response.message, 'success');
                            if (response.user) {
                                $('#user-name').text(response.user.ten);
                                $('#user-email').text(response.user.email);
                                $('#user-phone').text(response.user.sodienthoai);
                            }
                            if ($(e.target).attr('id') === 'change-password-form') {
                                $('#current-password').val('');
                                $('#new-password').val('');
                                $('#confirm-password').val('');
                            }
                            closeModal($(e.target).closest('.modal').attr('id'));
                        } else {
                            showToast(response.message, 'error'); 
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX Error:', status, error); 
                        showToast('Lỗi kết nối server!', 'error');
                    }
                });
            });
        });
    </script>