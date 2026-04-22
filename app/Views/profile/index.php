<?php
$avatar = $user['avatar'] ?? '/assets/image/default_avatar.webp';
?>

<div class="container">
    <div class="card">
        <div class="profile-grid">
            <div class="avatar-column">
                <div class="avatar-wrapper">
                    <img src="<?= htmlspecialchars($avatar) ?>" alt="Avatar" class="avatar-image" />
                </div>
            </div>
            <div class="info-column">
                <div class="info-item">
                    <label>Họ và tên:</label>
                    <span><?= htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></span>
                </div>
                <div class="info-item">
                    <label>Email:</label>
                    <span><?= htmlspecialchars($user['email'] ?? '') ?></span>
                </div>
            </div>
            <div class="info-column">
                <div class="info-item">
                    <label>Tên đăng nhập:</label>
                    <span><?= htmlspecialchars($user['username'] ?? '') ?></span>
                </div>
                <div class="info-item">
                    <label>Số điện thoại:</label>
                    <span><?= htmlspecialchars($user['phone'] ?? 'Chưa cập nhật') ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="editProfileModal" class="modal hidden">
    <div class="modal__overlay" data-close="true"></div>
    <div class="modal__content">
        <div class="modal__header">
            <strong class="modal__title">Chỉnh sửa thông tin cá nhân</strong>
            <button type="button" class="modal__close" id="btnCloseModal" data-close="true">&times;</button>
        </div>
        <form id="editProfileForm" class="modal__body" action="/profile" method="POST" enctype="multipart/form-data">
            <div style="display: flex; justify-content: center; margin-bottom: 16px;">
                <div class="avatar-wrapper">
                    <img src="<?= htmlspecialchars($avatar) ?>" alt="Avatar" class="avatar-image" />
                    <button type="button" class="avatar-edit-btn" id="btnChangeAvatar">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                    </button>
                    <input type="file" id="avatarInput" name="avatar" style="display: none;">
                </div>
            </div>
            <p class="js-form-message" style="display:none;"></p>
            <div class="form-grid">
                <div class="field">
                    <div class="label">
                        <label for="modal_first_name">Họ:</label>
                    </div>
                    <div class="input">
                        <input type="text" id="modal_first_name" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>">
                    </div>
                    <small class="js-field-error error-message" data-field="first_name" style="color:red;"></small>
                </div>
                <div class="field">
                    <div class="label">
                        <label for="modal_last_name">Tên:</label>
                    </div>
                    <div class="input">
                        <input type="text" id="modal_last_name" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>">
                    </div>
                    <small class="js-field-error error-message" data-field="last_name" style="color:red;"></small>
                </div>
                <div class="field">
                    <div class="label">
                        <label for="modal_username">Tên đăng nhập:</label>
                    </div>
                    <div class="input">
                        <input type="text" id="modal_username" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" disabled>
                    </div>
                </div>
                <div class="field">
                    <div class="label">
                        <label for="modal_email">Email:</label>
                    </div>
                    <div class="input">
                        <input type="email" id="modal_email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled>
                    </div>
                </div>
                <div class="field full-width">
                    <div class="label">
                        <label for="modal_phone">Số điện thoại:</label>
                    </div>
                    <div class="input">
                        <input type="tel" id="modal_phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                    </div>
                    <small class="js-field-error error-message" data-field="phone" style="color:red;"></small>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn-secondary" id="btnCancel" data-close="true">Hủy</button>
                <button type="submit" class="btn btn-primary" id="btnSaveProfile">Lưu</button>
            </div>
        </form>
    </div>
</div>

<div id="modalProfileError" class="modal hidden">
    <div class="modal__overlay" data-close="true"></div>
    <div class="modal__content">
        <div class="modal__header">
            <strong class="modal__title">Thông báo lỗi</strong>
            <button type="button" class="modal__close" data-close="true">&times;</button>
        </div>
        <div class="modal__body"></div>
        <div class="modal__footer">
            <button type="button" class="btn btn-primary" data-close="true">Đóng</button>
        </div>
    </div>
</div>
