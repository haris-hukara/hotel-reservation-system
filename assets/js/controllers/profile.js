class Profile {
  static init() {
    $(document).ready(function () {
      Profile.getUserInfo();
      Profile.getUserAccountInfo();
      Profile.getUserReservations();
    });

    $("#profile-form").validate({
      submitHandler: function (form, event) {
        event.preventDefault();
        Profile.updateUserDetailsInfo();
      },
    });

    $("#password-form").validate({
      submitHandler: function (form, event) {
        event.preventDefault();
        Profile.updateUserPassword();
      },
    });

    $("#email-form").validate({
      submitHandler: function (form, event) {
        event.preventDefault();
        Profile.updateUserEmail();
      },
    });
  }

  static getUserAccountInfo() {
    const account_id = parse_jwt(window.localStorage.getItem("token")).id;
    RestClient.get(
      "api/user/account/" + account_id + "/email",
      function (data) {
        $("#account-email").val(data.email);
      }
    );
  }

  static updateUserEmail() {
    Profile.updateAccount("email");
  }

  static updateUserPassword() {
    Profile.updateAccount("password");
  }

  static updateAccount(param) {
    const details = jsonize_form("#" + param + "-form");
    const account_id = parse_jwt(window.localStorage.getItem("token")).id;
    console.log(details);
    $("#" + param + "-profile-submit").prop("disabled", true);

    RestClient.put(
      "api/user/account/" + account_id,
      details,
      function (data) {
        toastr.success("Your profile " + param + " is updated");
        $("#" + param + "-profile-submit").removeAttr("disabled");
        if (param == "email") {
          Profile.getUserAccountInfo();
        }
        $("#" + param + "-form")[0].reset();
      },
      function (jqXHR, textStatus, errorThrown) {
        $("#" + param + "-profile-submit").removeAttr("disabled");
        toastr.error(jqXHR.responseJSON.message);
      }
    );
  }

  static updateUserDetailsInfo() {
    const details = jsonize_form("#profile-form");
    const account_id = parse_jwt(window.localStorage.getItem("token")).id;
    $("#edit-profile-submit").prop("disabled", true);

    RestClient.put(
      "api/user/details/" + account_id,
      details,
      function (data) {
        toastr.success("Your profile details have been updated");
        Profile.getUserInfo();
        $("#edit-profile-submit").removeClass("disabled");
        $("#edit-profile-submit").addClass("submit-disabled");
      },
      function (jqXHR, textStatus, errorThrown) {
        $("#edit-profile-submit").removeAttr("disabled");
        toastr.error(jqXHR.responseJSON.message);
      }
    );
  }

  static showUserReservations() {
    $("#user-reservations-table").removeClass("hidden");
    $(".user-profile-form-container").addClass("hidden");

    $("#my-profile-btn").removeClass("profile-option-active");
    $("#my-profile-btn").addClass("profile-option-inactive");

    $("#my-reservations-btn").removeClass("profile-option-inactive");
    $("#my-reservations-btn").addClass("profile-option-active");
    $("#profile-page-heading").text("My Reservations");
  }

  static showUserProfile() {
    $(".user-profile-form-container").removeClass("hidden");
    $("#user-reservations-table").addClass("hidden");

    $("#my-profile-btn").removeClass("profile-option-inactive");
    $("#my-profile-btn").addClass("profile-option-active");

    $("#my-reservations-btn").removeClass("profile-option-active");
    $("#my-reservations-btn").addClass("profile-option-inactive");
    $("#profile-page-heading").text("My Profile");
  }

  static getUserInfo() {
    const account_id = parse_jwt(window.localStorage.getItem("token")).id;
    RestClient.get("api/user/" + account_id + "/details", function (data) {
      json2form("#profile-form", data);
      $("input[id^=profile]").prop("readonly", true);
    });
  }

  static enableEditing() {
    $("#edit-profile-submit").addClass("submit");
    $("#edit-profile-submit").removeClass("submit-disabled");
    $("input[id^=profile]").removeAttr("readonly");
    $("#edit-profile-submit").removeAttr("disabled");
  }

  static tableHead() {
    let html = `<div id="table-head">
            <h2 id="my-table-name">Reservations table</h2>
            </div>`;
    return html;
  }

  static getUserReservations() {
    $("#user-reservations-table").empty();
    $("#user-reservations-table").append(Profile.tableHead);
    const account_id = parse_jwt(window.localStorage.getItem("token")).id;
    RestClient.get(
      "api/user/" + account_id + "/reservations?order",
      function (data) {
        let rows = Profile.generateReservationRow(data);
        let table = Profile.generateReservationTable(rows);
        $("#user-reservations-table").append(table);
        $("#UserReservationsTable").DataTable({
          order: [[3, "desc"]],
        });
        $("#UserReservationsTable_filter").remove();
        $("#UserReservationsTable_wrapper").prepend($("#table-head"));
        $("#table-head").append($("#UserReservationsTable_length"));
      }
    );
  }

  static generateReservationRow(data) {
    let rows = "";

    for (var i = 0; i < data.length; i++) {
      let status = data[i].status.toLowerCase();
      let row = `<tr>
    <td>
       <span style="display: block">${data[i].id}</span>
      <span  onclick="Profile.openReservationDetails(${
        data[i].id
      })" class="forgot font-size-inherit">Click to see details</span></td>
    <td class="forgot font-size-inherit" onclick="Profile.openReservationUserDetailsInfo(${
      data[i].user_details_id
    })">
      <span hidden>${data[i].user_details_id}</span> Click to see
    </td>
    <td>At Arrival</td>
    <td>${data[i].created_at}</td>
    <td class="status-bg-${status}">${
        status[0].toUpperCase() + status.slice(1)
      }</td>
    </tr>`;
      rows += row;
    }
    return rows;
  }

  static generateReservationTable(rows) {
    let html = `<table id="UserReservationsTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>User details</th>
        <th>Payment method</th>
        <th>Reservation created at</th>
        <th>Status</th>
      </tr>
    </thead>

    <tbody>
      ${rows}
    </tbody>
  </table>`;
    return html;
  }

  static closeInfoModal() {
    var elements = $("[id^=profile-reservation-modal]");
    elements.removeClass("active");
  }

  static openInfoModal() {
    var elements = $("[id^=profile-reservation-modal]");
    elements.addClass("active");
  }

  static openUserInfoInModal() {
    $("#user-reservation-form").attr("hidden", true);
    $("#user-details-form").removeAttr("hidden");
  }

  static openReservationInfoInModal() {
    $("#user-details-form").attr("hidden", true);
    $("#user-reservation-form").removeAttr("hidden");
  }

  static openReservationDetails(id) {
    const account_id = parse_jwt(window.localStorage.getItem("token")).id;
    $("#reservation-info-id").html(id);
    RestClient.get(
      "api/user/" + account_id + "/reservation/" + id + "/details",
      function (data) {
        json2form("#user-reservation-form", data[0]);
        Profile.getReservationTotalPrice(id);
        Profile.openReservationInfoInModal();
        Profile.openInfoModal();
      }
    );
  }

  static getReservationTotalPrice(reservation_id) {
    const account_id = parse_jwt(window.localStorage.getItem("token")).id;
    RestClient.get(
      "api/user/" + account_id + "/reservation/" + reservation_id + "/price",
      function (data) {
        $("#profile-modal-room-total-price").html(
          "$" + data.total_price + ".00"
        );
      }
    );
  }

  static setRoomInfo(room_id) {
    RestClient.get("api/room/" + room_id, function (data) {
      var room_info = {
        night_price: data.night_price,
        room_name: data.name,
      };
      json2form("#user-reservation-form", room_info);
    });
  }

  static openReservationUserDetailsInfo(details_id) {
    RestClient.get("api/user/details/" + details_id, function (data) {
      json2form("#user-details-form", data);
      Profile.openUserInfoInModal();
      Profile.openInfoModal();
    });
  }
}
