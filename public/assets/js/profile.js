(function ($) {
  function clearErrors($form) {
    $form.find(".js-field-error").text("");
    hideMessage($form);
  }

  function setFieldError($form, field, message) {
    $form
      .find('.js-field-error[data-field="' + field + '"]')
      .text(message || "");
  }

  function showMessage($form, message, isSuccess) {
    var $message = $form.find(".js-form-message");
    $message
      .text(message || "")
      .css("color", isSuccess ? "green" : "red")
      .show();
  }

  function hideMessage($form) {
    $form.find(".js-form-message").text("").hide();
  }

  function validateProfile($form) {
    var errors = {};
    var firstName = $.trim($form.find('[name="first_name"]').val());
    var lastName = $.trim($form.find('[name="last_name"]').val());
    var avatar = $form.find('[name="avatar"]')[0].files[0];
    var allowedTypes = ["image/jpeg", "image/png", "image/webp"];
    var maxSize = 2 * 1024 * 1024;

    if (!firstName) {
      errors.first_name = "First name is required";
    }

    if (!lastName) {
      errors.last_name = "Last name is required";
    }

    if (avatar) {
      if ($.inArray(avatar.type, allowedTypes) === -1) {
        errors.avatar = "Avatar must be jpeg, png or webp";
      } else if (avatar.size > maxSize) {
        errors.avatar = "Avatar size must be less than 2MB";
      }
    }

    return errors;
  }

  function renderErrors($form, errors) {
    clearErrors($form);

    if (!errors) {
      return;
    }

    $.each(errors, function (field, message) {
      setFieldError($form, field, message);
    });
  }

  function bindAvatarPreview() {
    $("#avatarInput").on("change", function (event) {
      var file = event.target.files[0];
      if (!file) {
        return;
      }

      if (!file.type.startsWith("image/")) {
        return;
      }

      var imageUrl = URL.createObjectURL(file);
      $("#avatarPreview").attr("src", imageUrl);
    });
  }

  function submitProfileForm($form) {
    $form.on("submit", function (event) {
      event.preventDefault();

      var errors = validateProfile($form);
      if (Object.keys(errors).length > 0) {
        renderErrors($form, errors);
        showMessage($form, "Please check your input", false);
        return;
      }

      clearErrors($form);

      var formData = new FormData($form[0]);

      $.ajax({
        url: $form.attr("action"),
        method: $form.attr("method") || "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        headers: {
          Accept: "application/json",
        },
      })
        .done(function (response) {
          if (!response || response.success !== true) {
            showMessage(
              $form,
              (response && response.message) || "Request failed",
              false,
            );
            renderErrors($form, response ? response.errors : {});
            return;
          }

          showMessage(
            $form,
            response.message || "Update profile success",
            true,
          );
        })
        .fail(function (xhr) {
          var payload = xhr.responseJSON || {};

          if (payload.redirect) {
            window.location.href = payload.redirect;
            return;
          }

          showMessage(
            $form,
            payload.message || "Server error. Please try again.",
            false,
          );
          renderErrors($form, payload.errors || {});
        });
    });
  }

  $(function () {
    var $profileForm = $("#profileForm");
    if (!$profileForm.length) {
      return;
    }

    bindAvatarPreview();
    submitProfileForm($profileForm);
  });
})(jQuery);
