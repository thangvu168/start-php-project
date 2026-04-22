window.App = window.App || {};
App.Component = App.Component || {};

App.Component.Modal = (function () {
  function setContent(selector, content) {
    $(selector).find(".modal__body").html(content);
  }

  function open(selector) {
    $(selector).removeClass("hidden");
  }

  function close(selector) {
    $(selector).addClass("hidden");
  }

  function toggle(selector) {
    $(selector).toggleClass("hidden");
  }

  function bindAutoClose() {
    $(document).on("click", "[data-close='true']", function () {
      $(this).closest(".modal").addClass("hidden");
    });

    $(document).on("keydown", function (e) {
      if (e.key === "Escape") {
        $(".modal:not(.hidden)").addClass("hidden");
      }
    });
  }

  function init() {
    bindAutoClose();
  }

  return {
    init,
    open,
    close,
    toggle,
    setContent,
  };
})();
