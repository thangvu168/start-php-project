<div class="panel__left">
    <div class="panel__left__inner">
        <div class="auth__logo">
            <a href="/">
                <img src="/assets/image/logo.png" alt="Logo">
            </a>
        </div>
        <div>
            <form id="resetForm" action="/password/reset" method="post" novalidate>
                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">

                <h1 class="auth__title">Đặt lại mật khẩu</h1>
                <p class="auth__subtitle">Nhập mật khẩu mới của bạn.</p>

                <div class="form__inner">
                    <div class="field">
                        <div class="label"><label for="password">Mật khẩu mới</label></div>
                        <div class="input"><input id="password" type="password" name="password"></div>
                        <small class="js-field-error error-message" data-field="password" style="color:red;"></small>
                    </div>

                    <div class="field">
                        <div class="label"><label for="confirm_password">Xác nhận mật khẩu</label></div>
                        <div class="input"><input id="confirm_password" type="password" name="confirm_password"></div>
                        <small class="js-field-error error-message" data-field="confirm_password" style="color:red;"></small>
                    </div>

                    <div style="width: 100%; margin-top: 20px;">
                        <button id="btnReset" type="submit" class="btn btn-primary">Đặt lại</button>
                    </div>
                </div>
            </form>
        </div>
        <a href="/login" class="auth__link">Quay lại đăng nhập</a>
    </div>
</div>

<div id="modalReset" class="modal hidden">
    <div class="modal__overlay" data-close=true></div>
    <div class="modal__content">
        <div class="modal__header">
            <strong class="modal__title">Thông báo</strong>
            <button type="button" class="modal__close" data-close="true">&times;</button>
        </div>
        <div class="modal__body"></div>
        <div class="modal__footer">
            <button type="button" class="btn btn-primary" data-close="true">Đóng</button>
        </div>
    </div>
</div>
