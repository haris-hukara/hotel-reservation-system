function jsonize_form(selector) {
  var data = $(selector).serializeArray();
  var form_data = {};
  for (let i = 0; i < data.length; i++) {
    form_data[data[i].name] = data[i].value;
  }
  return form_data;
}

function json2form(selector, data) {
  for (const attr in data) {
    $(selector + " *[name='" + attr + "']").val(data[attr]);
  }
}

/* decoding jwt token */
function parse_jwt(token) {
  var base64Url = token.split(".")[1];
  var base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
  var jsonPayload = decodeURIComponent(
    atob(base64)
      .split("")
      .map(function (c) {
        return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
      })
      .join("")
  );
  return JSON.parse(jsonPayload);
}

function getLocalStorageItemByName(name) {
  return JSON.parse(localStorage.getItem(name));
}

function getElementValue(element_id) {
  return document.getElementById(element_id).value;
}

function getDropdownValue(id) {
  var e = document.getElementById(id);
  return e.options[e.selectedIndex].value;
}

function doLogOut() {
  window.localStorage.clear();
  window.location = "index.html";
}
