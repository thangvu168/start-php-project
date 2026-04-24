$(function () {
  var profileRules = {
    first_name: {
      required: "Họ là bắt buộc",
      minLength: [2, "Họ phải có ít nhất 2 ký tự"],
    },
    last_name: {
      required: "Tên là bắt buộc",
      minLength: [2, "Tên phải có ít nhất 2 ký tự"],
    },
    phone: {
      minLength: [8, "Số điện thoại không hợp lệ"],
      pattern: [App.Validation.phone, "Số điện thoại không hợp lệ"],
    },
  };

  // Open modal — reset file input, keep showing current avatar
  $("#btnEditProfile").on("click", function () {
    $("#avatarInput").val("");
    App.Component.Modal.open("#editProfileModal");
  });

  // Avatar: show preview khi chọn file mới
  $("#avatarInput").on("change", function () {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function (e) {
      $("#avatarPreview").attr("src", e.target.result).show();
    };
    reader.readAsDataURL(file);
  });

  // Submit
  $("#editProfileForm").on("submit", function (event) {
    event.preventDefault();

    var $form = $(this);
    var errors = App.Component.Form.validate($form, profileRules);

    if (Object.keys(errors).length > 0) {
      App.Component.Form.renderErrors($form, errors);
      return;
    }

    App.Component.Form.clearErrors($form);

    var $btn = $form.find('[type="submit"]');
    $btn.prop("disabled", true);

    App.User.updateProfile(new FormData($form[0]))
      .then(function () {
        App.Component.Modal.close("#editProfileModal");
        window.location.reload();
      })
      .catch(function (xhr) {
        $btn.prop("disabled", false);
        var payload = xhr.responseJSON || {};
        if (payload.errors) {
          App.Component.Form.renderErrors($form, payload.errors);
        }
        if (payload.message) {
          App.Component.Modal.setContent("#modalProfileError", payload.message);
          App.Component.Modal.open("#modalProfileError");
        }
      });
  });
});
