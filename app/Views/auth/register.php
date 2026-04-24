<div class="panel__left">
  <div class="panel__left__inner">
    <div class="auth__logo">
      <a href="/">
        <img src="/assets/image/logo.png" alt="Logo">
      </a>
    </div>
    <div>
      <form id="registerForm" action="/register" method="post" novalidate>
        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">

        <h1 class="auth__title">Đăng kí</h1>
        <p class="auth__subtitle">Tạo tài khoản mới và bắt đầu sử dụng.</p>

        <div class="form__inner">
          <div class="field">
            <div class="label">
              <label for="first_name">Họ</label>
            </div>
            <div class="input">
              <input id="first_name" type="text" name="first_name" value="<?= htmlspecialchars($old['first_name'] ?? '') ?>">
            </div>
            <small class="js-field-error error-message" data-field="first_name" style="color:red;"></small>
          </div>

          <div class="field">
            <div class="label">
              <label for="last_name">Tên</label>
            </div>
            <div class="input">
              <input id="last_name" type="text" name="last_name" value="<?= htmlspecialchars($old['last_name'] ?? '') ?>">
            </div>
            <small class="js-field-error error-message" data-field="last_name" style="color:red;"></small>
          </div>

          <div class="field">
            <div class="label">
              <label for="username">Tên đăng nhập</label>
            </div>
            <div class="input">
              <input id="username" type="text" name="username" value="<?= htmlspecialchars($old['username'] ?? '') ?>">
            </div>
            <small class="js-field-error error-message" data-field="username" style="color:red;"></small>
          </div>

          <div class="field">
            <div class="label">
              <label for="email">Email</label>
            </div>
            <div class="input">
              <input id="email" type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
            </div>
            <small class="js-field-error error-message" data-field="email" style="color:red;"></small>
          </div>

          <div class="field">
            <div class="label">
              <label for="password">Mật khẩu</label>
            </div>
            <div class="input">
              <input id="password" type="password" name="password">
            </div>
            <small class="js-field-error error-message" data-field="password" style="color:red;"></small>
          </div>

          <div class="field">
            <div class="label">
              <label for="confirm_password">Xác nhận mật khẩu</label>
            </div>
            <div class="input">
              <input id="confirm_password" type="password" name="confirm_password">
            </div>
            <small class="js-field-error error-message" data-field="confirm_password" style="color:red;"></small>
          </div>

          <div style="width: 100%; margin-top: 20px;">
            <button id="btnRegister" type="submit" class="btn btn-primary">Đăng kí</button>
          </div>
        </div>
      </form>
    </div>
    <a href="/login" class="auth__link">Đã có tài khoản? Đăng nhập</a>
  </div>
</div>

<div id="modalRegisterError" class="modal hidden">
  <div class="modal__overlay" data-close=true></div>

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
