class Profile {
  static init() {
    $(document).ready(function () {
      $("#myTable").DataTable();
      $("#myTable_filter").remove();
      $("#myTable_wrapper").prepend($("#table-head"));
      $("#table-head").prepend($("#myTable_length"));
    });
  }

  static showUserReservations() {
    $("#user-reservations-table").removeClass("hidden");
    $("#user-profile-form-container").addClass("hidden");

    $("#my-profile-btn").removeClass("profile-option-active");
    $("#my-profile-btn").addClass("profile-option-inactive");

    $("#my-reservations-btn").removeClass("profile-option-inactive");
    $("#my-reservations-btn").addClass("profile-option-active");
    $("#profile-page-heading").text("My Reservations");
  }

  static showUserProfile() {
    $("#user-profile-form-container").removeClass("hidden");
    $("#user-reservations-table").addClass("hidden");

    $("#my-profile-btn").removeClass("profile-option-inactive");
    $("#my-profile-btn").addClass("profile-option-active");

    $("#my-reservations-btn").removeClass("profile-option-active");
    $("#my-reservations-btn").addClass("profile-option-inactive");
    $("#profile-page-heading").text("My Profile");
  }
}
