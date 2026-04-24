<?php
$avatar   = $user['avatar'] ?? '/assets/image/default_avatar.jpg';
$fullName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
$phone    = $user['phone'] ?? null;
$email    = $user['email'] ?? '';
?>

<div class="profile-layout">
    <div class="profile-main">
        <div class="container">
            <div class="profile-hero">
                <div class="profile-avatar-wrap">
                    <img src="<?= htmlspecialchars($avatar) ?>" alt="Avatar" class="profile-avatar" />
                </div>
                <div class="profile-hero-info">
                    <h2 class="profile-name"><?= htmlspecialchars($fullName) ?></h2>
                    <p class="profile-job-title">Chưa nhập chức danh</p>
                    <div class="profile-basic-info">
                        <div class="profile-basic-info-item">
                            <span class="info-label">Địa chỉ email</span>
                            <span class="info-value"><?= htmlspecialchars($email) ?></span>
                        </div>
                        <div class="profile-basic-info-item">
                            <span class="info-label">Số điện thoại</span>
                            <span class="info-value"><?= $phone ? htmlspecialchars($phone) : 'Chưa nhập số điện thoại' ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="profile-section">
                <div class="profile-section__header">
                    <span class="profile-section__title">Thông tin liên hệ</span>
                </div>
                <div class="profile-section__content">
                </div>
            </div>

            <div class="profile-section">
                <div class="profile-section__header">
                    <span class="profile-section__title">Nhóm</span>
                </div>
            </div>

            <div class="profile-section">
                <div class="profile-section__header">
                    <span class="profile-section__title">Nhân viên trực tiếp</span>
                </div>
            </div>

            <div class="profile-section">
                <div class="profile-section__header">
                    <span class="profile-section__title">Học vấn</span>
                </div>
            </div>
        </div>
    </div>

</div>

<div id="editProfileModal" class="modal hidden">
    <div class="modal__overlay" data-close="true"></div>
    <div class="modal__content modal__content--lg">
        <div class="modal__header">
            <strong class="modal__title">Chỉnh sửa hồ sơ cá nhân</strong>
            <button type="button" class="modal__close" data-close="true">&times;</button>
        </div>

        <form id="editProfileForm" action="/profile" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="remove_avatar" id="removeAvatarInput" value="0">
            <div class="modal__body">
                <div class="edit-field">
                    <div class="edit-field__label">Họ</div>
                    <div class="field field--sm">
                        <div class="input">
                            <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>">
                        </div>
                        <small class="js-field-error" data-field="first_name"></small>
                    </div>
                </div>

                <div class="edit-field">
                    <div class="edit-field__label">Tên</div>
                    <div class="field field--sm">
                        <div class="input">
                            <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>">
                        </div>
                        <small class="js-field-error" data-field="last_name"></small>
                    </div>
                </div>

                <div class="edit-field">
                    <div class="edit-field__label">Email</div>
                    <div class="field field--sm">
                        <div class="input">
                            <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled>
                        </div>
                    </div>
                </div>

                <div class="edit-field">
                    <div class="edit-field__label">Tên đăng nhập</div>
                    <div class="field field--sm">
                        <div class="input">
                            <input type="text" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" disabled>
                        </div>
                    </div>
                </div>

                <div class="edit-field">
                    <div class="edit-field__label">Ảnh đại diện</div>
                    <div class="edit-field__input">
                        <label class="avatar-file-label" for="avatarInput">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="17 8 12 3 7 8" />
                                <line x1="12" y1="3" x2="12" y2="15" />
                            </svg>
                            Chọn ảnh
                        </label>
                        <input type="file" id="avatarInput" name="avatar" style="display:none;" accept="image/jpeg,image/png,image/webp">
                        <?php $currentAvatar = $user['avatar'] ?? null; ?>
                        <div class="avatar-preview-wrap" id="avatarPreviewWrap" <?= $currentAvatar ? '' : 'style="display:none;"' ?>>
                            <img src="<?= htmlspecialchars($currentAvatar ?? '') ?>" alt="Preview" class="avatar-preview" id="avatarPreview">
                            <button type="button" class="avatar-preview-remove" id="btnRemoveAvatar">&times;</button>
                        </div>
                    </div>
                </div>

                <div class="edit-field">
                    <div class="edit-field__label">SĐT</div>
                    <div class="field field--sm">
                        <div class="input">
                            <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                        </div>
                        <small class="js-field-error" data-field="phone"></small>
                    </div>
                </div>
            </div>

            <div class="modal__footer">
                <button type="button" class="btn" data-close="true">Hủy</button>
                <button type="submit" class="btn btn-primary" id="btnSaveProfile">Cập nhật</button>
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
