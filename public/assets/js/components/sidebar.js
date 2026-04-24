window.App = window.App || {};

$(function () {
  $("#btnOpenModalLogout").on("click", function () {
    App.Component.Modal.open("#modalConfirmLogout");
  });

  $("#btnConfirmLogout").on("click", function () {
    // Sử dụng always để luôn chuyển hướng về login
    App.Auth.logout().always(function () {
      window.location.href = "/login";
    });
  });
});
