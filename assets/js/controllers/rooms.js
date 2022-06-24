class Rooms {
  static init() {
    $(document).ready(function () {
      Rooms.get_rooms();
    });
  }

  static get_rooms() {
    RestClient.get("api/rooms", function (data) {
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
            <h2>${name} <span class="room-id hidden">${id}</span></h2>
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
          <button class="room-button">Make reservation</button>
          <a class="forgot mt-1" onclick="">See more details </a>
        </div>
      </div>
    </div>`;
  }
}
