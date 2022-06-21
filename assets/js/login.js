class Login {
  static init() {
    $(document).ready(function () {
      Login.resizeSVG();
      $("#login-form-container").find("form")[0].reset();
      $("#register-form-container").find("form")[0].reset();

      $("#forgot-form-container").find("form")[0].reset();

      $("#birth_date").val("");
      var today = new Date();
      var max_date = today.getFullYear() + "-";
      var month = today.getMonth() + 1;
      if (month.toString().length == 1) {
        max_date += "0";
      }
      max_date += month + "-";
      if (today.getDate().toString().length == 1) {
        max_date += "0";
      }
      max_date += today.getDate();
      $("#birth_date").attr("max", max_date);
    });

    Login.resizeSVG();

    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has("token")) {
      $("#change-password-token").val(urlParams.get("token"));
      Login.showChangePasswordForm();
    }
    if (urlParams.has("confirm")) {
      Login.confirmRegistration(urlParams.get("confirm"));
    }

    $("#login-form").validate({
      submitHandler: function (form, event) {
        event.preventDefault();
        Login.doLogin();
      },
    });

    $("#register-form").validate({
      submitHandler: function (form, event) {
        event.preventDefault();
        Login.doRegister();
      },
    });

    $("#forgot-form").validate({
      submitHandler: function (form, event) {
        event.preventDefault();
        Login.doForgotPassword();
      },
    });

    $("#change-password-form").validate({
      submitHandler: function (form, event) {
        event.preventDefault();
        Login.doChangePassword();
      },
    });
  }

  static resizeSVG() {
    $(function () {
      $(window).on("resize", function (e) {
        for (var i = 0; i < $(".form-svg").length; i++) {
          if ($(window).width() > 700) {
            $(".form_svg_path")[i].setAttribute("stroke-width", 8);
          }
          if ($(window).width() < 700) {
            var stroke = (700 - $(window).width()) / 100 + 10;
            $(".form_svg_path")[i].setAttribute("stroke-width", stroke);
          }

          if ($(window).width() < 300) {
            var stroke = (700 - $(window).width()) / 100 + 15;
            $(".form_svg_path")[i].setAttribute("stroke-width", stroke);
          }

          $(".form-svg")[i].setAttribute(
            "width",
            document.getElementsByClassName("form-main")[i].offsetWidth
          );
        }
      });
    });
  }

  static showChangePasswordForm() {
    $("#change-password-form-container").removeClass("hidden");
    $("#login-form-container").addClass("hidden");
    $("#register-form-container").addClass("hidden");
    $("#register-form-container").addClass("hidden");
    $(window).resize();
  }

  static showForgotForm() {
    $("#login-form-container").addClass("hidden");
    $("#forgot-form-container").removeClass("hidden");
    $(window).resize();
  }

  static showRegisterForm() {
    $("#login-form-container").addClass("hidden");
    $("#register-form-container").removeClass("hidden");
    $(window).resize();
  }

  static showLoginForm() {
    $("#login-form-container").removeClass("hidden");
    $("#register-form-container").addClass("hidden");
    $("#forgot-form-container").addClass("hidden");
    $("#change-password-form-container").addClass("hidden");
    $(window).resize();
  }

  static doRegister() {
    $("#register-link").prop("disabled", true);
    RestClient.post(
      "api/register",
      jsonize_form("#register-form"),
      function (data) {
        console.log(data);
        $("#register-form-container").addClass("hidden");
        $("#form-alert").removeClass("hidden");
        $("#form-alert .alert").html(data.message);
      },
      function (jqXHR, textStatus, errorThrown) {
        $("#register-link").prop("disabled", false);
        console.log(error);
        toastr.error(error.responseJSON.message);
      }
    );
  }

  static confirmRegistration(token) {
    RestClient.get(
      "api/confirm/" + token,
      function (response) {
        toastr.success("You successfully activated your account");
        setTimeout(function () {
          window.localStorage.setItem("token", response.token);
          window.location = "index.html";
        }, 3000);
      },
      function (xhr) {
        toastr.error(xhr.responseJSON.message);
      }
    );
  }

  static doLogin() {
    $("#login-link").prop("disabled", true);
    RestClient.post("api/login", jsonize_form("#login-form"), function (data) {
      window.localStorage.setItem("token", data.token);
      window.location = "index.html";
    }),
      function (error) {
        $("#login-link").prop("disabled", false);
        toastr.error(error.responseJSON.message);
      };
  }

  static doForgotPassword() {
    $("#forgot-link").prop("disabled", true);
    RestClient.post(
      "api/forgot",
      jsonize_form("#forgot-form"),
      function (data) {
        console.log(data);
        $("#forgot-form-container").addClass("hidden");
        $("#form-alert").removeClass("hidden");
        $("#form-alert .alert").html(data.message);
      }
    ),
      function (error) {
        $("#forgot-link").prop("disabled", false);
        $("#forgot-form-container").addClass("hidden");
        $("#form-alert").removeClass("hidden");
        $("#form-alert .alert").html(error.responseJSON.message);
      };
  }

  static doChangePassword() {
    $("#change-link").prop("disabled", true);
    RestClient.post(
      "api/reset",
      jsonize_form("#change-password-form"),
      function (data) {
        window.localStorage.setItem("token", data.token);
        window.location = "index.html";
      },
      function (error) {
        $("#change-link").prop("disabled", false);
        toastr.error(error.responseJSON.message);
      }
    );
  }
}
