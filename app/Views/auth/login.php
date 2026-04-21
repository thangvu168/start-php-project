<div class="panel__left">
  <div class="">LOGO</div>
  <div>
    <form id="loginForm" action="/login" method="post" novalidate>
      <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">

      <h1>Đăng nhập</h1>
      <p>Chào mừng trở lại. Đăng nhập để bắt đầu làm việc.</p>

      <p class="js-form-message" style="display:none;"></p>

      <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
      <?php endif; ?>

      <div class="form__inner">
        <div class="field">
          <div class="label">
            <label for="email">Email</label>
          </div>
          <div class="input">
            <input id="email" type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
          </div>
          <small class="js-field-error" data-field="email" style="color:red;"></small>
        </div>

        <div class="field">
          <div class="label">
            <label for="password">Mật khẩu</label>
            <span>Quên mật khẩu?</span>
          </div>
          <div class="input">
            <input id="password" type="password" name="password">
          </div>
          <small class="js-field-error" data-field="password" style="color:red;"></small>
        </div>

        <div>
          <input type="checkbox" name="saved">
          <span>&nbsp; Giữ tôi luôn đăng nhập</span>
        </div>

        <button type="submit">Đăng nhập</button>
        <button type="button">Đăng nhập bằng Base ID</button>
      </div>
    </form>
  </div>
</div>
