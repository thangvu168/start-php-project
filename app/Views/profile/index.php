<?php
$avatar = $user['avatar'] ?? '/assets/image/default_avatar.webp';
$GLOBALS['show_page_header'] = true;
$GLOBALS['page_title'] = 'Hồ sơ';
$GLOBALS['back_url'] = '/';
$GLOBALS['buttons'] = [
    ['text' => 'Chỉnh sửa', 'class' => 'btn-primary', 'id' => 'edit-profile-btn']
];
?>

<div class="profile-container">
    <div class="profile-card">
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
    <div class="modal__overlay"></div>
    <div class="modal__content">
        <div class="modal__title">
            <h2>Chỉnh sửa thông tin cá nhân</h2>
            <button type="button" class="modal-close" id="close-modal-btn">&times;</button>
        </div>
        <form id="editProfileForm" class="modal__body" action="/profile" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($_SESSION['_csrf_token'] ?? '') ?>">
            <div class="avatar-wrapper" style="justify-content: center; display: flex; margin-bottom: 16px;">
                <img src="<?= htmlspecialchars($avatar) ?>" alt="Avatar" class="avatar-image" style="width: 96px; height: 96px; object-fit: cover; border-radius: 50%; border: 2px solid #eee;" />
                <button type="button" class="avatar-edit-btn" id="change-avatar-btn" style="margin-left: -36px; margin-top: 56px; background: #fff; border-radius: 50%; border: 1px solid #ccc; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                </button>
                <input type="file" id="avatarInput" name="avatar" style="display: none;">
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
                <button type="button" class="btn btn-secondary" id="cancel-btn btn">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu</button>
            </div>
        </form>
    </div>
</div>