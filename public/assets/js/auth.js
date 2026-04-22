// Avoid conflict $
(function ($) {
  function clearErrors($form) {
    $form.find(".js-field-error").text("");
  }

  function setFieldError($form, field, message) {
    $form
      .find('.js-field-error[data-field="' + field + '"]')
      .text(message || "");
  }

  function renderErrors($form, errors) {
    clearErrors($form);

    if (!errors) return;

    $.each(errors, function (field, message) {
      setFieldError($form, field, message);
    });
  }

  function bindFieldClearOnInput($form) {
    $form.on("input", "input, select, textarea", function () {
      var name = $(this).attr("name");

      if (!name) return;

      $form.find('.js-field-error[data-field="' + name + '"]').text("");
    });
  }

  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function validateLogin($form) {
    var errors = {};

    var email = $.trim($form.find('[name="email"]').val());
    var password = $.trim($form.find('[name="password"]').val());

    if (!email) {
      errors.email = "Email là bắt buộc";
    } else if (!isValidEmail(email)) {
      errors.email = "Định dạng email không hợp lệ";
    }

    if (!password) {
      errors.password = "Mật khẩu là bắt buộc";
    }

    return errors;
  }

  function validateRegister($form) {
    var errors = {};

    var firstName = $.trim($form.find('[name="first_name"]').val());
    var lastName = $.trim($form.find('[name="last_name"]').val());
    var username = $.trim($form.find('[name="username"]').val());
    var email = $.trim($form.find('[name="email"]').val());
    var password = $.trim($form.find('[name="password"]').val());
    var confirmPassword = $.trim($form.find('[name="confirm_password"]').val());

    if (!firstName) {
      errors.first_name = "Họ là bắt buộc";
    }

    if (!lastName) {
      errors.last_name = "Tên là bắt buộc";
    }

    if (!username) {
      errors.username = "Tên đăng nhập là bắt buộc";
    } else if (username.length < 3) {
      errors.username = "Tên đăng nhập phải có ít nhất 3 ký tự";
    }

    if (!email) {
      errors.email = "Email là bắt buộc";
    } else if (!isValidEmail(email)) {
      errors.email = "Định dạng email không hợp lệ";
    }

    if (!password) {
      errors.password = "Mật khẩu là bắt buộc";
    } else if (password.length < 6) {
      errors.password = "Mật khẩu phải có ít nhất 6 ký tự";
    }

    if (!confirmPassword) {
      errors.confirm_password = "Xác nhận mật khẩu là bắt buộc";
    } else if (password !== confirmPassword) {
      errors.confirm_password = "Xác nhận mật khẩu không đúng";
    }

    return errors;
  }

  function showModal(message) {
    $("#errorModal .modal__body").text(message);
    $("#errorModal").removeClass("hidden");
  }

  function hideModal() {
    $("#errorModal").addClass("hidden");
  }

  function submitJsonForm($form, validateFn, onApiError) {
    $form.on("submit", function (event) {
      event.preventDefault();

      var errors = validateFn($form);

      if (Object.keys(errors).length > 0) {
        renderErrors($form, errors);
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
            renderErrors($form, response ? response.errors : {});

            if (typeof onApiError === "function") {
              onApiError(response);
            }

            return;
          }

          if (response.redirect) {
            window.location.href = response.redirect;
          }
        })
        .fail(function (xhr) {
          var payload = xhr.responseJSON || {};

          renderErrors($form, payload.errors || {});

          if (typeof onApiError === "function") {
            onApiError(payload);
          }
        });
    });
  }

  function showApiErrorModal(payload) {
    var message = payload?.message || "Đã xảy ra lỗi hệ thống";

    showModal(message);
  }

  $(function () {
    $("#closeModalBtn, .modal__overlay").on("click", function () {
      hideModal();
    });

    $(document).on("keydown", function (e) {
      if (e.key === "Escape") {
        hideModal();
      }
    });
  });

  $(function () {
    var $loginForm = $("#loginForm");
    var $registerForm = $("#registerForm");

    if ($loginForm.length) {
      bindFieldClearOnInput($loginForm);
      submitJsonForm($loginForm, validateLogin, showApiErrorModal);
    }

    if ($registerForm.length) {
      bindFieldClearOnInput($registerForm);
      submitJsonForm($registerForm, validateRegister, showApiErrorModal);
    }
  });
})(jQuery);
