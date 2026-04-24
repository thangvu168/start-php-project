window.App = window.App || {};

App.Component.Modal.init();

// Init all menu instances marked with data-menu-init
$("[data-menu-init]").each(function () {
  App.Component.Menu.init("#" + $(this).attr("id"));
});

App.Security = {
  getCSRF: function () {
    console.log(
      "Getting CSRF token::",
      $('meta[name="_csrf_token"]').attr("content"),
    );
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
