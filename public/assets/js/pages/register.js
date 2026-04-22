// Handle event listeners for register page
$(function () {
  var registerRules = {
    first_name: { required: "Họ là bắt buộc" },
    last_name: { required: "Tên là bắt buộc" },
    username: {
      required: "Tên đăng nhập là bắt buộc",
      minLength: [3, "Tên đăng nhập phải có ít nhất 3 ký tự"],
    },
    email: {
      required: "Email là bắt buộc",
      pattern: [/^[^\s@]+@[^\s@]+\.[^\s@]+$/, "Định dạng email không hợp lệ"],
    },
    password: {
      required: "Mật khẩu là bắt buộc",
      minLength: [6, "Mật khẩu phải có ít nhất 6 ký tự"],
    },
    confirm_password: {
      required: "Xác nhận mật khẩu là bắt buộc",
      match: ["password", "Xác nhận mật khẩu không đúng"],
    },
  };

  $("#btnRegister").on("click", function (event) {
    event.preventDefault();

    var $form = $("#registerForm");
    var errors = App.Component.Form.validate($form, registerRules);

    if (Object.keys(errors).length > 0) {
      App.Component.Form.renderErrors($form, errors);
      return;
    }

    App.Component.Form.clearErrors($form);

    App.Auth.register({
      firstName: $form.find('[name="first_name"]').val(),
      lastName: $form.find('[name="last_name"]').val(),
      username: $form.find('[name="username"]').val(),
      email: $form.find('[name="email"]').val(),
      password: $form.find('[name="password"]').val(),
      confirm_password: $form.find('[name="confirm_password"]').val(),
    })
      .then(function (response) {
        window.location.href = response.redirect || "/login";
      })
      .catch(function (error) {
        var payload = error.responseJSON || {};
        if (payload.errors) {
          App.Component.Form.renderErrors($form, payload.errors);
        }
        App.Component.Modal.setContent(
          "#modalRegisterError",
          payload.message || "Đã xảy ra lỗi, vui lòng thử lại.",
        );
        App.Component.Modal.open("#modalRegisterError");
      });
  });
});
