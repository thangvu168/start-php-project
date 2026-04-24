// Handle event listeners for login page
$(function () {
  var loginRules = {
    email: {
      required: "Email là bắt buộc",
      pattern: [App.Validation.email, "Định dạng email không hợp lệ"],
    },
    password: { required: "Mật khẩu là bắt buộc" },
  };

  window.onCaptchaSuccess = function (token) {
    $("#btnLogin").prop("disabled", false);
  };

  window.onCaptchaExpired = function () {
    $("#btnLogin").prop("disabled", true);
    grecaptcha.reset();
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

    var $btn = $("#btnLogin");
    $btn.prop("disabled", true);

    App.Auth.login({
      email: $form.find('[name="email"]').val(),
      password: $form.find('[name="password"]').val(),
      remember_me: $form.find('[name="remember_me"]').is(":checked"),
      captcha: $form.find('[name="g-recaptcha-response"]').val(),
    })
      .then(function (response) {
        window.location.href = response.redirect || "/";
      })
      .catch(function (error) {
        $btn.prop("disabled", false);

        var payload = error.responseJSON || {};

        // If reload is needed (reached 3 attempts), reload the page
        if (payload.reload) {
          setTimeout(function () {
            window.location.reload();
          }, 1500);
        }

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
