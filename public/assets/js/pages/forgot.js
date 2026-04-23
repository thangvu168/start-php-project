$(function () {
  var forgotRule = {
    email: {
      required: "Email là bắt buộc",
      pattern: [/^[^\s@]+@[^\s@]+\.[^\s@]+$/, "Định dạng email không hợp lệ"],
    },
  };

  $("#btnForgot").on("click", function (event) {
    event.preventDefault();

    var $form = $("#forgotForm");
    var errors = App.Component.Form.validate($form, forgotRule);
    if (Object.keys(errors).length > 0) {
      App.Component.Form.renderErrors($form, errors);
      return;
    }

    App.Component.Form.clearErrors($form);
    var email = $form.find('[name="email"]').val();

    var captcha = "";
    if (typeof grecaptcha !== "undefined") {
      captcha = grecaptcha.enterprise.getResponse();
    }

    App.Auth.forgotPassword({ email: email, captcha: captcha })
      .then(function (res) {
        App.Component.Modal.setContent(
          "#modalForgot",
          res.message || "Nếu email tồn tại, bạn sẽ nhận được hướng dẫn.",
        );
        App.Component.Modal.open("#modalForgot");
      })
      .catch(function (err) {
        var payload = err.responseJSON || {};
        if (payload.errors) {
          App.Component.Form.renderErrors($form, payload.errors);
        }
        App.Component.Modal.setContent(
          "#modalForgot",
          payload.message || "Đã xảy ra lỗi",
        );
        App.Component.Modal.open("#modalForgot");
      });
  });
});
