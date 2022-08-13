class Profile {
  static init() {
    $(document).ready(function () {
      Profile.getUserInfo();
      Profile.getUserAccountInfo();
      $("#myTable").DataTable();
      $("#myTable_filter").remove();
      $("#myTable_wrapper").prepend($("#table-head"));
      $("#table-head").prepend($("#myTable_length"));
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
        $("#edit-profile-submit").removeAttr("disabled");
        Profile.getUserInfo();
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
      $("#profile-password").val("********");
      $("input[id^=profile]").prop("readonly", true);
    });
  }

  static enableEditing() {
    $("#edit-profile-submit").addClass("submit");
    $("#edit-profile-submit").removeClass("submit-disabled");
    $("input[id^=profile]").removeAttr("readonly");
    $("#edit-profile-submit").removeAttr("disabled");
  }
}
