class Index {
  static init() {
    $(document).ready(function () {
      var app = $.jQuerySPApp({ defaultView: "#homepage" }); // initialize

      if (!window.localStorage.getItem("token")) {
        $(".user-stuff").remove();
        $(".admin-stuff").remove();
      } else {
        $(".nav-button")[0].text = "Log out";
        $(".nav-button")[0].setAttribute("onclick", "doLogOut()");
        $(".nav-button")[0].setAttribute("href", "index.html");

        var user_info = parse_jwt(window.localStorage.getItem("token"));
        if (user_info.rl == "ADMIN") {
        } else {
          $(".admin-stuff").remove();
        }
      }
      // run app
      app.run();
    });
  }
}
