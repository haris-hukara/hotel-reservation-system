class Admin {
  static init() {
    $(document).ready(function () {
      Admin.getUserReservations();
    });

    $("#user-reservation-form").validate({
      submitHandler: function (form, event) {
        event.preventDefault();
        Admin.updateReservationDetails();
      },
    });

    $("#create-room-form").validate({
      submitHandler: function (form, event) {
        event.preventDefault();
        Admin.createRoom();
      },
    });
  }

  static updateUserEmail() {
    Admin.updateAccount("email");
  }

  static updateUserPassword() {
    Admin.updateAccount("password");
  }

  static showUserReservations() {
    $("#user-reservations-table").removeClass("hidden");
    $(".admin-rooms-container").addClass("hidden");

    $("#my-profile-btn").removeClass("profile-option-active");
    $("#my-profile-btn").addClass("profile-option-inactive");

    $("#my-reservations-btn").removeClass("profile-option-inactive");
    $("#my-reservations-btn").addClass("profile-option-active");
    $("#profile-page-heading").text("Admin reservations");
  }

  static showUserProfile() {
    $(".admin-rooms-container").removeClass("hidden");
    $("#user-reservations-table").addClass("hidden");

    $("#my-profile-btn").removeClass("profile-option-inactive");
    $("#my-profile-btn").addClass("profile-option-active");

    $("#my-reservations-btn").removeClass("profile-option-active");
    $("#my-reservations-btn").addClass("profile-option-inactive");
    $("#profile-page-heading").text("Admin rooms");
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
    $("#user-reservations-table").append(Admin.tableHead);
    RestClient.get("api/admin/reservations", function (data) {
      let rows = Admin.generateReservationRow(data);
      let table = Admin.generateReservationTable(rows);
      $("#user-reservations-table").append(table);
      $("#UserReservationsTable").DataTable({
        order: [[5, "asc"]],
      });
      $("#UserReservationsTable_filter").remove();
      $("#UserReservationsTable_wrapper").prepend($("#table-head"));
      $("#table-head").append($("#UserReservationsTable_length"));
    });
  }

  static generateReservationRow(data) {
    let rows = "";

    for (var i = 0; i < data.length; i++) {
      let status = data[i].status.toLowerCase();
      let row = `<tr>
    <td>
       <span style="display: block">${data[i].id}</span>
      <span  onclick="Admin.openReservationDetails(${
        data[i].id
      })" class="forgot font-size-inherit">Click to see more</span>
    </td>

    <td class="forgot font-size-inherit" onclick="Admin.openReservationUserDetailsInfo(${
      data[i].user_details_id
    })">
      <span hidden>${data[i].user_details_id}</span> Click to see
    </td>

    <td>${data[i].rooms}</td>

    <td>${data[i].check_in}</td>
    <td>${data[i].check_out}</td>
    <td>${data[i].created_at}</td>

    <td>
    <span class="status-bg-${status}"> 
    ${status[0].toUpperCase() + status.slice(1)}
    </span> 
    </td>

      <td class="table-accept-reject">
      <ion-icon class="table-accept" name="checkmark-circle" onclick="Admin.acceptReservation(${
        data[i].id
      })"></ion-icon>
      <ion-icon class="table-reject" name="close-circle" onclick="Admin.rejectReservation(${
        data[i].id
      })"></ion-icon>
      </td>
      </tr>`;

      rows += row;
    }
    return rows;
  }

  static generateReservationTable(rows) {
    let html = `<table id="UserReservationsTable">
    <thead>
      <tr>
        <th>Reservation id</th>
        <th>User details</th>
        <th>Rooms</th>
        <th>Check-in</th>
        <th>Check-out</th>
        <th>Created at</th>
        <th>Status</th>
        <th>Accept / Reject</th>
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
    $("#admin-update-reservation-details")
      .removeClass("submit")
      .addClass("submit-disabled");
    $("#admin-update-reservation-details").addAttr("disabled");
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
    $("#reservation-info-id").html(id);
    $("#profile-modal-reservation-id").val(id);
    RestClient.get("api/admin/reservation/" + id + "/details", function (data) {
      json2form("#user-reservation-form", data[0]);
      $("#profile-modal-reservation-old-room-id").val(data[0]["room_id"]);
      Admin.getReservationTotalPrice(id);
      Admin.openReservationInfoInModal();
      Admin.openInfoModal();
    });
  }

  static getReservationTotalPrice(reservation_id) {
    RestClient.get(
      "api/admin/reservation/" + reservation_id + "/price",
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
      Admin.openUserInfoInModal();
      Admin.openInfoModal();
    });
  }

  static acceptReservation(id) {
    Admin.changeReservationStatus(id, "ACCEPTED");
  }

  static rejectReservation(id) {
    Admin.changeReservationStatus(id, "REJECTED");
  }

  static changeReservationStatus(reservation_id, reservation_status) {
    RestClient.put(
      "api/admin/reservations/" + reservation_id + "/change_status",
      { status: reservation_status },
      function (data) {
        toastr.success(
          "Reservation " +
            reservation_id +
            " status changed to " +
            reservation_status
        );
        Admin.getUserReservations();
      },
      function (jqXHR, textStatus, errorThrown) {
        toastr.error(jqXHR.responseJSON.message);
      }
    );
  }

  static enableReservationDetailsEditing() {
    $("#admin-update-reservation-details")
      .removeClass("submit-disabled")
      .addClass("submit");
    $("#admin-update-reservation-details").removeAttr("disabled");
    $("#profile-modal-room-id").removeAttr("readonly");
    $("#profile-modal-room-check-in").removeAttr("readonly");
    $("#profile-modal-room-check-out").removeAttr("readonly");
    $("#profile-modal-room-adults").removeAttr("readonly");
    $("#profile-modal-room-children").removeAttr("readonly");
  }

  static updateReservationDetails() {
    let data = jsonize_form("#user-reservation-form");
    data["new_room_id"] = data["room_id"];
    data["room_id"] = data["old_room_id"];

    let reservation_id = data["reservation_id"];
    let reservation_room_id = data["old_room_id"];

    delete data.reservation_id;
    delete data.old_room_id;
    delete data.room_id;

    RestClient.put(
      "api/admin/reservation/" +
        reservation_id +
        "/details/room/" +
        reservation_room_id,
      data,
      function (data) {
        toastr.success("Reservation details changed");
        Admin.getUserReservations();
        Admin.closeInfoModal();
      },
      function (jqXHR, textStatus, errorThrown) {
        toastr.error(jqXHR.responseJSON.message);
      }
    );
  }

  static setRoomPreviewInfo() {
    let data = jsonize_form("#create-room-form");

    $("#room-create-name").html(data["name"]);
    $("#room-create-description").html(data["description"]);
    $("#room-create-price").html(data["night_price"]);
    $("#room-create-image-link").attr("src", data["image_link"]);
  }

  static createRoom() {
    RestClient.post(
      "api/admin/rooms",
      jsonize_form("#create-room-form"),
      function (data) {
        toastr.success("Room created successfully");
      }
    ),
      function (error) {
        toastr.error(error.responseJSON.message);
      };
  }
}
