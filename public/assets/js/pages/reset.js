$(function () {
  var resetRule = {
    password: {
      required: "Mật khẩu là bắt buộc",
      minLength: [6, "Mật khẩu phải có ít nhất 6 ký tự"],
    },
    confirm_password: {
      required: "Xác nhận mật khẩu là bắt buộc",
      match: ["password", "Xác nhận mật khẩu không khớp"],
    },
  };

  $("#btnReset").on("click", function (event) {
    event.preventDefault();

    var $form = $("#resetForm");
    var password = $form.find('[name="password"]').val();
    var confirm = $form.find('[name="confirm_password"]').val();
    var token = $form.find('[name="token"]').val();

    var errors = App.Component.Form.validate($form, resetRule);
    if (Object.keys(errors).length > 0) {
      App.Component.Form.renderErrors($form, errors);
      return;
    }

    App.Component.Form.clearErrors($form);

    var $btn = $("#btnReset");
    $btn.prop("disabled", true);

    App.Auth.resetPassword({
      token: token,
      password: password,
      confirm_password: confirm,
    })
      .then(function (res) {
        App.Component.Modal.setContent(
          "#modalReset",
          res.message || "Mật khẩu đã được cập nhật.",
        );
        App.Component.Modal.open("#modalReset");
        setTimeout(function () {
          window.location.href = "/login";
        }, 1200);
      })
      .catch(function (err) {
        $btn.prop("disabled", false);
        var payload = err.responseJSON || {};
        if (payload.errors) {
          App.Component.Form.renderErrors($form, payload.errors);
        }
        App.Component.Modal.setContent(
          "#modalReset",
          payload.message || "Đã xảy ra lỗi",
        );
        App.Component.Modal.open("#modalReset");
      });
  });
});
