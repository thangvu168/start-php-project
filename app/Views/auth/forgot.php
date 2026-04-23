<div class="panel__left">
    <div class="panel__left__inner">
        <div class="auth__logo">
            <a href="/">
                <img src="/assets/image/logo.png" alt="Logo">
            </a>
        </div>
        <div>
            <form id="forgotForm" action="/password/forgot" method="post" novalidate>
                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">

                <h1 class="auth__title">Quên mật khẩu</h1>
                <p class="auth__subtitle">Nhập email của bạn để nhận đường dẫn đặt lại mật khẩu.</p>

                <div class="form__inner">
                    <div class="field">
                        <div class="label">
                            <label for="email">Email</label>
                        </div>
                        <div class="input">
                            <input id="email" type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                        </div>
                        <small class="js-field-error error-message" data-field="email" style="color:red;"></small>
                    </div>

                    <div style="width: 100%; margin-top: 20px;">
                        <button id="btnForgot" type="submit" class="btn btn-primary">Gửi yêu cầu</button>
                    </div>
                </div>
            </form>
        </div>
        <a href="/login" class="auth__link">Quay lại đăng nhập</a>
    </div>
</div>

<div id="modalForgot" class="modal hidden">
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