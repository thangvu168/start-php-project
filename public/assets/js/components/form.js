window.App = window.App || {};
App.Component = App.Component || {};

App.Component.Form = (function () {
  function clearErrors($form) {
    $form.find(".js-field-error").text("");
  }

  function renderErrors($form, errors) {
    clearErrors($form);
    $.each(errors, function (field, message) {
      $form
        .find('.js-field-error[data-field="' + field + '"]')
        .text(message || "");
    });
  }

  /**
   * Validate form fields based on rules.
   *
   * Supported rules per field:
   *   required: "message"
   *   email:    "message"
   *   minLength: [min, "message"]
   *   match:    ["otherFieldName", "message"]
   *   pattern:  [/regex/, "message"]
   */
  function validate($form, rules) {
    var errors = {};

    $.each(rules, function (field, fieldRules) {
      var value = $.trim($form.find('[name="' + field + '"]').val());

      if (fieldRules.required) {
        if (!value) {
          errors[field] = fieldRules.required;
          return; // continue to next field
        }
      }

      if (!value) return;

      if (fieldRules.minLength) {
        if (value.length < fieldRules.minLength[0]) {
          errors[field] = fieldRules.minLength[1];
          return;
        }
      }

      if (fieldRules.match) {
        var matchValue = $.trim(
          $form.find('[name="' + fieldRules.match[0] + '"]').val()
        );
        if (value !== matchValue) {
          errors[field] = fieldRules.match[1];
          return;
        }
      }

      if (fieldRules.pattern) {
        if (!fieldRules.pattern[0].test(value)) {
          errors[field] = fieldRules.pattern[1];
          return;
        }
      }
    });

    return errors;
  }

  return {
    clearErrors,
    renderErrors,
    validate,
  };
})();
