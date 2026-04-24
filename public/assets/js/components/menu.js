window.App = window.App || {};
App.Component = App.Component || {};

App.Component.Menu = (function () {
  function init(selector, options) {
    var $menu = $(selector);
    if (!$menu.length) return;

    var opts = $.extend({ active: undefined, onSelect: null }, options);
    $menu.data("menu-opts", opts);

    // Nếu có options.active, tìm phần tử tương ứng và set active, nếu không thì set active phần tử đầu tiên có class .menu__link
    var $initial;
    if (opts.active !== undefined) {
      $initial = _find($menu, opts.active);
    } else {
      $initial = $menu.find(".menu__link:not([disabled])").first();
    }
    if ($initial && $initial.length) _setActive($menu, $initial);

    // Click handler
    $menu.on("click", ".menu__link", function (e) {
      if ($(this).is("[disabled]")) {
        e.preventDefault();
        return;
      }

      var $link = $(this);
      _setActive($menu, $link);

      if (typeof opts.onSelect === "function") {
        opts.onSelect($link.attr("href"), $link);
      }
    });
  }

  function _find($menu, value) {
    if (typeof value === "number") {
      return $menu.find(".menu__link").eq(value);
    }
    return $menu.find('.menu__link[href="' + value + '"]').first();
  }

  function _setActive($menu, $link) {
    if ($link.is("[disabled]")) return;
    $menu.find(".menu__link--active").removeClass("menu__link--active");
    $link.addClass("menu__link--active");
  }

  /**
   * Cho phép set active menu item sau khi khởi tạo menu
   * Có thể truyền vào index (number) hoặc path (string)
   */
  function setActive(selector, value) {
    var $menu = $(selector);
    var $link = _find($menu, value);
    if ($link && $link.length) _setActive($menu, $link);
  }

  return { init, setActive };
})();
