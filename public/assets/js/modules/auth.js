window.App = window.App || {};

App.Auth = (function () {
  function login({ email, password }) {
    return $.ajax({
      url: "login",
      method: "POST",
      data: {
        email: email,
        password: password,
      },
    });
  }

  function register({
    firstName,
    lastName,
    username,
    email,
    password,
    confirm_password,
  }) {
    return $.ajax({
      url: "register",
      method: "POST",
      data: {
        first_name: firstName,
        last_name: lastName,
        username: username,
        email: email,
        password: password,
        confirm_password: confirm_password,
      },
    });
  }

  function logout() {
    return $.ajax({
      url: "/logout",
      method: "POST",
      data: {},
    });
  }

  return {
    login,
    register,
    logout,
  };
})();
