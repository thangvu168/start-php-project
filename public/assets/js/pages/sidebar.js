$(function () {
  $("#btnOpenModalLogout").on("click", function () {
    App.Component.Modal.open("#modalConfirmLogout");
  });

  $("#btnConfirmLogout").on("click", function () {
    App.Auth.logout().finally(function () {
      window.location.href = "/login";
    });
  });
});
