window.App = window.App || {};

App.User = (function () {
  function getProfile() {
    return $.ajax({
      url: "/profile",
      method: "GET",
    });
  }

  function updateProfile(formData) {
    return $.ajax({
      url: "/profile",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
    });
  }

  return {
    getProfile,
    updateProfile,
  };
})();
