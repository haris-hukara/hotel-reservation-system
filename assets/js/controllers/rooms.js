class Rooms {
  static init() {
    $(document).ready(function () {
      Rooms.get_rooms();
      if (!localStorage.getItem("token")) {
        $("#checkbox-label").remove();
      }
    });
  }

  static openModal($id) {
    console.log($id);
    var elements = $(".modal-overlay, .modal");
    elements.addClass("active");
  }

  static closeModal() {
    var elements = $(".modal-overlay, .modal");
    elements.removeClass("active");
  }

  static getUserInfo() {
    if ($("#profile-info-box").val() == "yes") {
      $("[id^=modal]").removeAttr("disabled");
      $("#profile-info-box").attr("value", "no");
    } else {
      const account_id = parse_jwt(window.localStorage.getItem("token")).id;
      RestClient.get("api/user/" + account_id + "/details", function (data) {
        $("#modal-email").val(data.email);
        $("#modal-first-name").val(data.first_name);
        $("#modal-last-name").val(data.last_name);
        $("#modal-phone-number").val(data.phone_number);
        $("#modal-birth-date").val(data.birth_date);
        $("#modal-country").val(data.country);
        $("#modal-city").val(data.city);
        $("[id^=modal]").prop("disabled", true);
        $("#profile-info-box").attr("value", "yes");
      });
    }
  }

  static get_avaliable_rooms() {
    localStorage.setItem("check-in", $("#resrvations-check-in").val());
    localStorage.setItem("check-out", $("#resrvations-check-out").val());
    localStorage.setItem("adults", $("#resrvations-adults").val());
    localStorage.setItem("children", $("#resrvations-children").val());
    localStorage.setItem("rooms", $("#resrvations-rooms").val());
    $(".room-container").remove();

    $("#home-check-in").val($("#resrvations-check-in").val());
    $("#home-check-out").val($("#resrvations-check-out").val());
    $("#home-adults").val($("#resrvations-adults").val());
    $("#home-children").val($("#resrvations-children").val());
    $("#home-rooms").val($("#resrvations-rooms").val());

    Rooms.get_rooms();
  }

  static get_rooms() {
    var check_in = localStorage.getItem("check-in");
    var check_out = localStorage.getItem("check-out");
    var url = "api/rooms";

    if (
      check_in != null &&
      check_out != null &&
      check_in != undefined &&
      check_out != undefined
    ) {
      $("#resrvations-check-in").val(localStorage.getItem("check-in"));
      $("#resrvations-check-out").val(localStorage.getItem("check-out"));
      url += "?order=-id&check_in=" + check_in + "&check_out=" + check_out;
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
      $("#resrvations-check-in").val(today);
      $("#resrvations-check-out").val(seven_days);
    }
    $("#resrvations-rooms").val(1);
    $("#resrvations-adults").val(1);
    $("#resrvations-children").val(0);

    RestClient.get(url, function (data) {
      console.log(data);
      for (var i = 0; i < data.length; i++) {
        $("#rooms-box").append(
          Rooms.createRoom(
            data[i].id,
            data[i].name,
            data[i].description,
            data[i].night_price,
            data[i].image_link
          )
        );
      }
    });
  }

  static createRoom(id, name, description, night_price, image_link) {
    return `<div class="room-container">
      <figure class="room-figure">
        <img alt="" src="${image_link}" />
      </figure>
      <div class="room-main">
        <div class="room-main-info">
          <div class="room-head">
            <h2>${name} <span id="room-id-${id}" class="room-id hidden">${id}</span></h2>
            <p>From <span class="room-price"> $${night_price}</span>/night</p>
          </div>
          <p class="room-description">
          ${description}
          </p>
          <div class="room-services">
            <div class="room-service">
              <ion-icon
                name="wifi-outline"
                role="img"
                class="md hydrated"
                aria-label="wifi outline"
              ></ion-icon>
              <p class="room-service-name">Wifi</p>
            </div>
            <div class="room-service">
              <ion-icon
                name="fast-food-outline"
                role="img"
                class="md hydrated"
                aria-label="fast food outline"
              ></ion-icon>
              <p class="room-service-name">Breakfast</p>
            </div>
            <div class="room-service">
              <ion-icon
                name="car-sport-outline"
                role="img"
                class="md hydrated"
                aria-label="car sport outline"
              ></ion-icon>
              <p class="room-service-name">Parking</p>
            </div>
            <div class="room-service">
              <ion-icon
                name="desktop-outline"
                role="img"
                class="md hydrated"
                aria-label="desktop outline"
              ></ion-icon>
              <p class="room-service-name">TV</p>
            </div>
            <div class="room-service">
              <ion-icon
                name="paw-outline"
                role="img"
                class="md hydrated"
                aria-label="paw outline"
              ></ion-icon>
              <p class="room-service-name">Pet free</p>
            </div>
            <div class="room-service">
              <ion-icon
                name="barbell-outline"
                role="img"
                class="md hydrated"
                aria-label="barbell outline"
              ></ion-icon>
              <p class="room-service-name">Gym</p>
            </div>
          </div>
        </div>
        <div class="flex-col-center">
          <button class="room-button modal-button" onclick="Rooms.openModal(${id})">Make reservation</button>
          <a class="forgot mt-1" onclick="">See more details </a>
        </div>
      </div>
    </div>`;
  }
}
