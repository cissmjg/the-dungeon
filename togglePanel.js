export function initTogglePanels() {
  $(".togglePanel > a").on("click", function() {
    if ($(this).hasClass("active")) {
      $(this).removeClass("active");
      $(this)
        .siblings(".togglePanelContent")
        .slideUp(200);
      $(this)
        .find("span")
        .removeClass("fa-minus")
        .addClass("fa-plus");
    } else {
      $(this)
        .find("span")
        .removeClass("fa-plus")
        .addClass("fa-minus");
      $(this).addClass("active");
      $(this)
        .siblings(".togglePanelContent")
        .slideDown(200);
    }
  });
}

window.addEventListener('load', initTogglePanels);