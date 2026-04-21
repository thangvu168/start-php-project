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

  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function validateLogin($form) {
    var errors = {};
    var email = $.trim($form.find('[name="email"]').val());
    var password = $.trim($form.find('[name="password"]').val());

    if (!email) {
      errors.email = "Email is required";
    } else if (!isValidEmail(email)) {
      errors.email = "Email format is invalid";
    }

    if (!password) {
      errors.password = "Password is required";
    }

    return errors;
  }

  function validateRegister($form) {
    var errors = {};
    var username = $.trim($form.find('[name="username"]').val());
    var email = $.trim($form.find('[name="email"]').val());
    var password = $.trim($form.find('[name="password"]').val());
    var confirmPassword = $.trim($form.find('[name="confirm_password"]').val());

    if (!username) {
      errors.username = "Username is required";
    } else if (username.length < 3) {
      errors.username = "Username must be at least 3 characters";
    }

    if (!email) {
      errors.email = "Email is required";
    } else if (!isValidEmail(email)) {
      errors.email = "Email format is invalid";
    }

    if (!password) {
      errors.password = "Password is required";
    } else if (password.length < 6) {
      errors.password = "Password must be at least 6 characters";
    }

    if (!confirmPassword) {
      errors.confirm_password = "Confirm password is required";
    } else if (password !== confirmPassword) {
      errors.confirm_password = "Confirm password is incorrect";
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

  function submitJsonForm($form, validateFn) {
    $form.on("submit", function (event) {
      event.preventDefault();

      var errors = validateFn($form);
      if (Object.keys(errors).length > 0) {
        renderErrors($form, errors);
        showMessage($form, "Please check your input", false);
        return;
      }

      clearErrors($form);

      $.ajax({
        url: $form.attr("action"),
        method: $form.attr("method") || "POST",
        data: $form.serialize(),
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

            if (response && response.redirect) {
              window.location.href = response.redirect;
            }
            return;
          }

          showMessage($form, response.message || "Success", true);

          if (response.redirect) {
            window.location.href = response.redirect;
          }
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
    var $loginForm = $("#loginForm");
    var $registerForm = $("#registerForm");

    if ($loginForm.length) {
      submitJsonForm($loginForm, validateLogin);
    }

    if ($registerForm.length) {
      submitJsonForm($registerForm, validateRegister);
    }
  });
})(jQuery);
