class Reservation {
  static init() {
    $(document).ready(function () {
      var user_id = parse_jwt(window.localStorage.getItem("token"))["id"];
      Reservation.get_reservations(user_id);
    });
  }

  static get_reservations(user_id) {
    RestClient.get("api/reservations/" + user_id, function (data) {
      console.log(data);
    });
  }

  static createTable() {
    return `  <table id="myTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Guest ID</th>
        <th>Room ID</th>
        <th>Date of reservation</th>
        <th>Check-in date</th>
        <th>Check-out date</th>
      </tr>
    </thead>

    <tbody>
      <tr>
      
      <th> <ion-icon class="small-icon" name="pencil-outline"></ion-icon> 1 </th>
        <td>100</td>
        <td>500</td>
        <td>22/04/2022</td>
        <td>25/04/2022</td>
        <td>29/04/2022</td>
      </tr>
      <tr>
        <th><ion-icon class="small-icon" name="pencil-outline"></ion-icon>2</th>
        <td>101</td>
        <td>500</td>
        <td>22/04/2022</td>
        <td>22/04/2022</td>
        <td>22/04/2022</td>
      </tr>
      <tr>
        <th><ion-icon class="small-icon" name="pencil-outline"></ion-icon>3</th>
        <td>102</td>
        <td>500</td>
        <td>22/04/2022</td>
        <td>22/04/2022</td>
        <td>22/04/2022</td>
      </tr>
      <tr>
        <th><ion-icon class="small-icon" name="pencil-outline"></ion-icon>4</th>
        <td>103</td>
        <td>500</td>
        <td>22/04/2022</td>
        <td>22/04/2022</td>
        <td>22/04/2022</td>
      </tr>
      <tr>
      
      <th> <ion-icon class="small-icon" name="pencil-outline"></ion-icon> 1 </th>
        <td>100</td>
        <td>500</td>
        <td>22/04/2022</td>
        <td>25/04/2022</td>
        <td>29/04/2022</td>
      </tr>
      <tr>
        <th><ion-icon class="small-icon" name="pencil-outline"></ion-icon>2</th>
        <td>101</td>
        <td>500</td>
        <td>22/04/2022</td>
        <td>22/04/2022</td>
        <td>22/04/2022</td>
      </tr>
      <tr>
        <th><ion-icon class="small-icon" name="pencil-outline"></ion-icon>3</th>
        <td>102</td>
        <td>500</td>
        <td>22/04/2022</td>
        <td>22/04/2022</td>
        <td>22/04/2022</td>
      </tr>
      <tr>
        <th><ion-icon class="small-icon" name="pencil-outline"></ion-icon>4</th>
        <td>103</td>
        <td>500</td>
        <td>22/04/2022</td>
        <td>22/04/2022</td>
        <td>22/04/2022</td>
      </tr>
      <tr>
      
      <th> <ion-icon class="small-icon" name="pencil-outline"></ion-icon> 1 </th>
        <td>100</td>
        <td>500</td>
        <td>22/04/2022</td>
        <td>25/04/2022</td>
        <td>29/04/2022</td>
      </tr>
      <tr>
        <th><ion-icon class="small-icon" name="pencil-outline"></ion-icon>2</th>
        <td>101</td>
        <td>500</td>
        <td>22/04/2022</td>
        <td>22/04/2022</td>
        <td>22/04/2022</td>
      </tr>
      <tr>
        <th><ion-icon class="small-icon" name="pencil-outline"></ion-icon>3</th>
        <td>102</td>
        <td>500</td>
        <td>22/04/2022</td>
        <td>22/04/2022</td>
        <td>22/04/2022</td>
      </tr>
      <tr>
        <th><ion-icon class="small-icon" name="pencil-outline"></ion-icon>4</th>
        <td>103</td>
        <td>500</td>
        <td>22/04/2022</td>
        <td>22/04/2022</td>
        <td>22/04/2022</td>
      </tr>
    </tbody>
  </table>`;
  }
}
