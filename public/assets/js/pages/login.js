// Handle event listeners for login page
$(function () {
  var loginRules = {
    email: {
      required: "Email là bắt buộc",
      pattern: [/^[^\s@]+@[^\s@]+\.[^\s@]+$/, "Định dạng email không hợp lệ"],
    },
    password: { required: "Mật khẩu là bắt buộc" },
  };

  $("#btnLogin").on("click", function (event) {
    event.preventDefault();

    var $form = $("#loginForm");
    var errors = App.Component.Form.validate($form, loginRules);

    if (Object.keys(errors).length > 0) {
      App.Component.Form.renderErrors($form, errors);
      return;
    }

    App.Component.Form.clearErrors($form);

    App.Auth.login({
      email: $form.find('[name="email"]').val(),
      password: $form.find('[name="password"]').val(),
      remember_me: $form.find('[name="remember_me"]').is(":checked"),
    })
      .then(function (response) {
        window.location.href = response.redirect || "/";
      })
      .catch(function (error) {
        var payload = error.responseJSON || {};
        if (payload.errors) {
          App.Component.Form.renderErrors($form, payload.errors);
        }
        App.Component.Modal.setContent(
          "#modalLoginError",
          payload.message || "Đã xảy ra lỗi, vui lòng thử lại.",
        );
        App.Component.Modal.open("#modalLoginError");
      });
  });
});
