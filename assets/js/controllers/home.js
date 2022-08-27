class Home {
  static init() {
    $(document).ready(function () {
      Home.setBasicValues();
    });
  }

  static setBasicValues() {
    var check_in = localStorage.getItem("check-in");
    var check_out = localStorage.getItem("check-out");
    var rooms = localStorage.getItem("rooms");
    var adults = localStorage.getItem("adults");
    var children = localStorage.getItem("children");

    if (
      check_in != null &&
      check_out != null &&
      rooms != null &&
      adults != null &&
      children != null &&
      check_in != undefined &&
      check_out != undefined &&
      rooms != undefined &&
      adults != undefined &&
      children != undefined
    ) {
      $("#home-check-in").val(localStorage.getItem("check-in"));
      $("#home-check-out").val(localStorage.getItem("check-out"));
      $("#home-rooms").val(localStorage.getItem("rooms"));
      $("#home-adults").val(localStorage.getItem("adults"));
      $("#home-children").val(localStorage.getItem("children"));
    } else {
      var current_date = new Date();
      var today = current_date.toLocaleDateString("fr-CA", {
        year: "numeric",
        month: "numeric",
        day: "numeric",
      });

      var future_date = new Date();
      future_date.setDate(future_date.getDate() + 7);
      var seven_days = future_date.toLocaleDateString("fr-CA", {
        year: "numeric",
        month: "numeric",
        day: "numeric",
      });
      $("#home-check-in").val(today);
      $("#home-check-out").val(seven_days);
      $("#home-rooms").val(1);
      $("#home-adults").val(1);
      $("#home-children").val(0);
    }
  }

  static checkAvaliability() {
    localStorage.setItem("check-in", $("#home-check-in").val());
    localStorage.setItem("check-out", $("#home-check-out").val());
    localStorage.setItem("adults", $("#home-adults").val());
    localStorage.setItem("children", $("#home-children").val());
    localStorage.setItem("rooms", $("#home-rooms").val());

    window.location.href = "#rooms";

    $("#reservations-check-in").val($("#home-check-in").val());
    $("#reservations-check-out").val($("#home-check-out").val());
    $("#reservations-adults").val($("#home-adults").val());
    $("#reservations-children").val($("#home-children").val());
    $("#reservations-rooms").val($("#home-rooms").val());

    $("#reservations-button").click();
  }
}
