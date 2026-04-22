(function ($) {
  $(document).ready(function () {
    console.log("Profile JS loaded");

    // Modal handling
    $("#edit-profile-btn").on("click", function () {
      console.log("Edit button clicked");
      console.log("Modal element exists:", $("#editProfileModal").length);
      $("#editProfileModal").removeClass("hidden");
      console.log(
        "Has hidden class after remove:",
        $("#editProfileModal").hasClass("hidden"),
      );
    });

    $("#close-modal-btn, #cancel-btn").on("click", function () {
      console.log("Close button clicked");
      $("#editProfileModal").addClass("hidden");
    });

    // Close modal when clicking outside
    $(window).on("click", function (event) {
      if (event.target == $("#editProfileModal")[0]) {
        $("#editProfileModal").addClass("hidden");
      }
    });

    // Avatar change
    $("#change-avatar-btn").on("click", function () {
      $("#avatarInput").click();
    });

    $("#avatarInput").on("change", function () {
      var file = this.files[0];
      if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $(".avatar-image").attr("src", e.target.result);
        };
        reader.readAsDataURL(file);
      }
    });

    // Form validation and submission
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
      var phone = $.trim($form.find('[name="phone"]').val());

      if (!firstName) {
        errors.first_name = "Họ là bắt buộc";
      }

      if (!lastName) {
        errors.last_name = "Tên là bắt buộc";
      }

      if (phone && !/^[\+]?[0-9\-\(\)\s]+$/.test(phone)) {
        errors.phone = "Số điện thoại không hợp lệ";
      }

      return errors;
    }

    $("#editProfileForm").on("submit", function (e) {
      e.preventDefault();
      var $form = $(this);
      clearErrors($form);

      var errors = validateProfile($form);
      if (Object.keys(errors).length > 0) {
        for (var field in errors) {
          setFieldError($form, field, errors[field]);
        }
        return;
      }

      // Submit form
      $form[0].submit();
    });
  });
})(jQuery);
