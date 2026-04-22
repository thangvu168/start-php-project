$(function () {
    var profileRules = {
      first_name: { required: "Họ là bắt buộc" },
      last_name: { required: "Tên là bắt buộc" },
      phone: {
        pattern: [/^[\+]?[0-9\-\(\)\s]+$/, "Số điện thoại không hợp lệ"],
      },
    };

    // Modal
    $("#btnEditProfile").on("click", function () {
      App.Component.Modal.open("#editProfileModal");
    });

    // Avatar preview
    $("#btnChangeAvatar").on("click", function () {
      $("#avatarInput").click();
    });

    $("#avatarInput").on("change", function () {
      var file = this.files[0];
      if (!file) return;

      var reader = new FileReader();
      reader.onload = function (e) {
        $("#editProfileModal .avatar-image").attr("src", e.target.result);
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

      App.User.updateProfile(new FormData($form[0]))
        .then(function () {
          App.Component.Modal.close("#editProfileModal");
          window.location.reload();
        })
        .catch(function (xhr) {
          var payload = xhr.responseJSON || {};
          if (payload.errors) {
            App.Component.Form.renderErrors($form, payload.errors);
          }
          if (payload.message) {
            App.Component.Modal.setContent(
              "#modalProfileError",
              payload.message,
            );
            App.Component.Modal.open("#modalProfileError");
          }
        });
    });
});
