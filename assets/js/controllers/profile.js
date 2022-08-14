class Profile {
  static init() {
    $(document).ready(function () {
      Profile.getUserInfo();
      Profile.getUserAccountInfo();
      Profile.getUserResrvations();
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

  static getUserResrvations() {
    const account_id = parse_jwt(window.localStorage.getItem("token")).id;
    RestClient.get("api/user/" + account_id + "/reservations", function (data) {
      let rows = Profile.generateReservationRow(data);
      let table = Profile.generateReservationTable(rows);
      $("#user-reservations-table").append(table);
      $("#UserReservationsTable").DataTable();
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
    <td>${data[i].id}</td>
    <td class="forgot font-size-inherit">
      <span hidden>${data[i].user_details_id}</span> Click to se more
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
}
