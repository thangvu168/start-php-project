window.App = window.App || {};

App.Auth = (function () {
  function login({ email, password, remember_me }) {
    return $.ajax({
      url: "login",
      method: "POST",
      data: {
        email: email,
        password: password,
        remember_me: remember_me,
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

  function forgotPassword({ email, captcha }) {
    return $.ajax({
      url: "/password/forgot",
      method: "POST",
      data: {
        email: email,
        "g-recaptcha-response": captcha || "",
      },
    });
  }

  function resetPassword({ token, password, confirm_password }) {
    return $.ajax({
      url: "/password/reset",
      method: "POST",
      data: {
        token: token,
        password: password,
        confirm_password: confirm_password,
      },
    });
  }

  return {
    login,
    register,
    logout,
    forgotPassword,
    resetPassword,
  };
})();
