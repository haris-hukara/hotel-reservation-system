class Rooms {
  static init() {
    $(document).ready(function () {
      Rooms.get_rooms();
      if (!localStorage.getItem("token")) {
        $("#checkbox-label").remove();
      }

      $("#reservation-form").validate({
        submitHandler: function (form, event) {
          event.preventDefault();
          Rooms.makeReservation();
        },
      });
    });
  }

  static makeReservationForRoomUsingDetailsId(details_id, room_info) {
    RestClient.post(
      "api/reservation",
      {
        user_details_id: details_id.toString(),
        payment_method_id: "1",
      },
      function (data) {
        Object.assign(room_info, { reservation_id: data.id });
        console.log(room_info);
        RestClient.post(
          "api/reservation/details",
          room_info,
          function (data) {
            toastr.success("Successfully created reservation");
            Rooms.closeModal();
          },
          function (jqXHR, textStatus, errorThrown) {
            toastr.error(jqXHR.responseJSON.message);
          }
        );
      },
      function (jqXHR, textStatus, errorThrown) {
        toastr.error(jqXHR.responseJSON.message);
      }
    );
  }

  static makeReservation() {
    var user_info = jsonize_form("#reservation-form");
    let room_info = jsonize_form("#room-form");

    if (user_info["profile-info"] == "yes") {
      const account_id = parse_jwt(window.localStorage.getItem("token")).id;
      RestClient.get("api/user/" + account_id + "/details", function (data) {
        var details_id = data.id;
        Rooms.makeReservationForRoomUsingDetailsId(details_id, room_info);
      });
    } else {
      RestClient.post(
        "api/details/add",
        user_info,
        function (data) {
          var details_id = data.id;
          Rooms.makeReservationForRoomUsingDetailsId(details_id, room_info);
        },
        function (jqXHR, textStatus, errorThrown) {
          toastr.error(jqXHR.responseJSON.message);
        }
      );
    }
  }

  static openUserInfoForm() {
    $("#room-form").attr("hidden", true);
    $("#reservation-form").removeAttr("hidden");
  }

  static openReservationRoomInfo() {
    $("#reservation-form").attr("hidden", true);
    $("#room-form").removeAttr("hidden");
  }

  static openModal(id) {
    Rooms.openReservationRoomInfo();
    $("#modal-room-id").val(id);
    $("#modal-room-name").val($("#room-" + id + "-name").html());
    $("#modal-room-price").val($("#room-" + id + "-price").html());

    var date1 = new Date(localStorage.getItem("check-in"));
    var date2 = new Date(localStorage.getItem("check-out"));
    var diffDays = date2.getDate() - date1.getDate();
    var total_price = diffDays * $("#modal-room-price").val();
    $("#room-total-price").html("$" + total_price + ".00");

    $("#modal-room-adults").val(localStorage.getItem("adults"));
    $("#modal-room-children").val(localStorage.getItem("children"));
    $("#modal-room-check-in").val(localStorage.getItem("check-in"));
    $("#modal-room-check-out").val(localStorage.getItem("check-out"));

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

      $("[id^=modal-room]").removeAttr("disabled");
      $("[id^=modal-room-name]").prop("disabled", true);
      $("[id^=modal-room-price]").prop("disabled", true);
    } else {
      const account_id = parse_jwt(window.localStorage.getItem("token")).id;
      RestClient.get("api/user/" + account_id + "/details", function (data) {
        json2form("#reservation-form", data);

        $("[id^=modal]").prop("disabled", true);

        $("[id^=modal-room]").removeAttr("disabled");
        $("[id^=modal-room-name]").prop("disabled", true);
        $("[id^=modal-room-price]").prop("disabled", true);

        $("#profile-info-box").attr("value", "yes");
      });
    }
  }

  static storeParams() {
    localStorage.setItem("check-in", $("#reservations-check-in").val());
    localStorage.setItem("check-out", $("#reservations-check-out").val());
    localStorage.setItem("adults", $("#reservations-adults").val());
    localStorage.setItem("children", $("#reservations-children").val());
    localStorage.setItem("rooms", $("#reservations-rooms").val());
  }

  static get_avaliable_rooms() {
    Rooms.storeParams();
    $(".room-container").remove();

    $("#home-check-in").val($("#reservations-check-in").val());
    $("#home-check-out").val($("#reservations-check-out").val());
    $("#home-adults").val($("#reservations-adults").val());
    $("#home-children").val($("#reservations-children").val());
    $("#home-rooms").val($("#reservations-rooms").val());

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
      $("#reservations-check-in").val(localStorage.getItem("check-in"));
      $("#reservations-check-out").val(localStorage.getItem("check-out"));
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
      $("#reservations-check-in").val(today);
      $("#reservations-check-out").val(seven_days);
    }
    $("#reservations-rooms").val(localStorage.getItem("rooms"));
    $("#reservations-adults").val(localStorage.getItem("adults"));
    $("#reservations-children").val(localStorage.getItem("children"));
    Rooms.storeParams();

    RestClient.get(url, function (data) {
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
            <h2> <span id="room-${id}-name">${name}</span> <span id="room-id-${id}" class="room-id hidden">${id}</span></h2>
            <p>From <span class="room-price"> <span>$</span><span id="room-${id}-price">${night_price}</span> </span>/night</p>
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
