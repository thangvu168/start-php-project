(function ($) {
  function showModal() {
    $("#logoutModal").removeClass("hidden");
  }

  function hideModal() {
    $("#logoutModal").addClass("hidden");
  }

  function confirmLogout() {
    const csrfToken = $('input[name="_csrf_token"]').val();

    $.ajax({
      url: "/logout",
      method: "POST",
      headers: {
        "X-CSRF-Token": csrfToken,
      },
      success: function () {
        window.location.href = "/login";
      },
      error: function () {
        alert("Đăng xuất thất bại. Vui lòng thử lại.");
      },
    });
  }

  $(function () {
    console.log("Sidebar JS loaded");

    $("#sidebar__logout").on("click", function () {
      console.log("Logout button clicked");
      console.log("Logout modal exists:", $("#logoutModal").length);
      showModal();
      console.log(
        "Has hidden class after show:",
        $("#logoutModal").hasClass("hidden"),
      );
    });

    $("#cancelLogoutBtn, .modal__overlay").on("click", function () {
      hideModal();
    });

    $(document).on("keydown", function (e) {
      if (e.key === "Escape") {
        hideModal();
      }
    });

    $("#confirmLogoutBtn").on("click", function () {
      confirmLogout();
    });
  });
})(jQuery);
