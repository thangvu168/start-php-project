$(function () {
  $("#btnOpenModalLogout").on("click", function () {
    App.Component.Modal.open("#modalConfirmLogout");
  });

  $("#btnConfirmLogout").on("click", function () {
    console.log("Confirm logout clicked");
    App.Auth.logout().then(function () {
      window.location.href = "/login";
    });
  });
});
