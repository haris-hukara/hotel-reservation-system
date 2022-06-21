class Home {
  static init() {
    $(document).ready(function () {
      if (localStorage.getItem("nav") == "1") {
        $("#about-link")[0].click();
        $("#close-mob-nav")[0].click();

        localStorage.setItem("nav", 0);
      }
    });
  }
}
