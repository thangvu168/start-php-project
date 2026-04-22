window.App = window.App || {};

App.Component.Modal.init();

App.Security = {
  getCSRF: function () {
    return $('meta[name="_csrf_token"]').attr("content");
  },
};

$.ajaxSetup({
  beforeSend: function (xhr, settings) {
    const token = App.Security.getCSRF();

    // Setup header HTTP_X_CSRF_TOKEN
    if (token) {
      xhr.setRequestHeader("X-CSRF-TOKEN", token);
    }
  },
});
