<?php 
require_once dirname(__FILE__)."/BaseService.class.php";
require_once dirname(__FILE__)."/../dao/ReservationDetailsDao.class.php";
require_once dirname(__FILE__)."/../dao/RoomsDao.class.php";

class ReservationDetailsService extends BaseService{

  private $userAccountDao;

 public function __construct(){
   $this->dao = new ReservationDetailsDao();   
   $this->userAccountDao = new UserAccountDao();
   $this->roomsDao = new RoomsDao();
   $this->reservationsDao = new ReservationsDao();
  }
 
  public function get_reservation_details_by_user_id_and_reservation_id($user, $user_id, $reservation_id){
    $user_account;
    try {
      $user_account = $this->userAccountDao->get_by_id($user_id);
    } catch (\Exception $e) {
      throw $e;
    }

    if(!$user_account){
      throw new Exception("This account doesn't exist", 404);
    }
    
    if( $user['id'] == $user_account['id'] || $user['rl'] == "ADMIN" ){
      $reservation_details =
      $this->dao->get_reservation_details_by_account_id_and_reservation_id($user_id,$reservation_id);
  
        if(!empty($reservation_details)){
          return $reservation_details;
        }else{
          return ["message"=>"No reservations avaliable"];
        }
    }else{
        throw new Exception("Not your account", 401);
      }
    } 
    
    public function get_reservation_details($user, $user_id, $reservation_id){
      $user_account;
    try {
      $user_account = $this->userAccountDao->get_by_id($user_id);
    } catch (\Exception $e) {
      throw $e;
    }

    if(!$user_account){
      throw new Exception("This account doesn't exist", 404);
    }
    if( $user_id == $user_account['id'] || $user['rl'] == "ADMIN" ){
      $reservations = $this->dao->get_reservation_details($user_id, $reservation_id);
      if(empty($reservations)){
        throw new Exception("This reservation doesn't exist", 404);
      }
      return $reservations;
    }elseif ($user['id'] !== $user_account['id']){
      throw new Exception("Not your account", 401);
    }else{
      throw new Exception("Oops something went wrong",400);
    }
  }
  
  
  public function get_reservation_total_price($user, $reservation_id){
    if( $user['rl'] == "ADMIN" ){
      $reservations = $this->dao->get_reservation_details_by_reservation_id( $reservation_id);
      if(empty($reservations)){
        throw new Exception("Reservation details don't exist", 404);
      }
      $total_price = 0;
      foreach($reservations as $arr => $val) {
        $total_price += ($reservations[$arr]["price"]);
      }
      return ["total_price"=>$total_price];
  }else{
      throw new Exception("You are not allowed",403);
    }
  
    }


    public function get_reservation_price($user, $user_id, $reservation_id){
    $user_account;
    try {
      $user_account = $this->userAccountDao->get_by_id($user_id);
    } catch (\Exception $e) {
      throw $e;
    }
    
    if(!$user_account){
      throw new Exception("This account doesn't exist", 404);
    }
    if( $user_id == $user_account['id'] || $user['rl'] == "ADMIN" ){
      $reservations = $this->dao->get_reservation_details($user_id, $reservation_id);
      if(empty($reservations)){
        throw new Exception("This reservation doesn't exisrrt", 404);
      }

      $total_price = 0;
      foreach($reservations as $arr => $val) {
        $total_price += ($reservations[$arr]["price"]);
      }
      return ["total_price"=>$total_price];
    }elseif ($user['id'] !== $user_account['id']){
      throw new Exception("Not your account", 401);
    }else{
      throw new Exception("Oops something went wrong",400);
    }
  }

  public function delete_all_details_by_reservation_id($id){
    return $this->dao->delete_all_details_by_reservation_id($id);
  }

   

  public function add_reservation_details($details){
    if(!isset($details['reservation_id'])) throw new Exception("Reservation ID is missing",400);
    if(!isset($details['check_in'])) throw new Exception("Check in date is missing",400);
    if(!isset($details['check_out'])) throw new Exception("Check out date is missing",400);
    if(!isset($details['room_id'])) throw new Exception("Room ID is missing",400);
    if(!isset($details['children'])) throw new Exception("Number of children is missing",400);
    if(!isset($details['adults'])) throw new Exception("Number of adults is missing",400);
    

    if(!parent::date_format_check($details['check_in'])) throw new Exception("Check-in date format is not valid",400);
    if(!parent::date_format_check($details['check_out'])) throw new Exception("Check-out date format is not valid",400);
    if( $details['check_out'] < $details['check_in'] ) throw new Exception("Check-out date can't be lower than check-in date",400);
    
    $room = $this->roomsDao->check_room_availability($details['room_id'], $details['check_in'], $details['check_out']);
    $is_avaliable = empty($room);

    if(!$is_avaliable){
      $this->delete_all_details_by_reservation_id($details['reservation_id']);
      throw new Exception("Room id: " .$details['room_id']. " is not avaliable between ". $details['check_in']." and ". $details['check_out'], 400);
    }
    
    try {
      // add details
      $reservation_details = $this->dao->add($details);    
      return $reservation_details;
      
    } catch (\Exception $e) {
      if(str_contains($e->getMessage(), 'reservation_details.PRIMARY')){
              throw new Exception("This reservation details already exist", 400, $e);
          } else { 
            throw new Exception($e, 400);
            }
      }
     
  }


  
  public function get_reservation_details_by_id($user, $reservation_id){

  if( $user['rl'] == "ADMIN" ){
      $reservations = $this->dao->get_reservation_details_by_reservation_id( $reservation_id);
      if(empty($reservations)){
        throw new Exception("Reservation details don't exist", 404);
      }
      return $reservations;
  }else{
      throw new Exception("You are not allowed",403);
    }
  }

  public function get_reservation_details_by_reservation_id_and_room_id($user, $reservation_id, $room_id){

  if( $user['rl'] == "ADMIN" ){
      $reservations = $this->dao->get_reservation_details_by_reservation_id_and_room_id($reservation_id, $room_id);
      if(empty($reservations)){
        throw new Exception("Reservation $reservation_id, details for room $room_id don't exist", 404);
      }
      return $reservations;
  }else{
      throw new Exception("You are not allowed",403);
    }
  }
  
  
  public function check_if_details_changable($reservation_id , $room_id, $check_in, $check_out){
    if( $check_out < $check_in ) throw new Exception("Check-out date can't be lower than check-in date",400);
    
    $is_changable = $this->dao->check_if_details_changable($reservation_id , $room_id, $check_in, $check_out);
    if($is_changable){
      return true;
    }else{
      return false;
    }

  }


  public function update_reservation_details ($user, $data){
    $reservation_id = $data["reservation_id"];
    $room_id = $data["room_id"];
    $new_room_id = $data["new_room_id"];
    $children = $data["children"];
    $adults = $data["adults"];
    $check_in = $data["check_in"];
    $check_out = $data["check_out"];

    $is_changable = $this->check_if_details_changable($reservation_id , $new_room_id, $check_in, $check_out);
    if ($is_changable){
     $old_reservation_details =  $this->get_reservation_details_by_reservation_id_and_room_id($user, $reservation_id, $room_id);;
     $info = $this->update_reservation_details_for_existing_room($user,$data);
     $new_reservation_details = $this->get_reservation_details_by_reservation_id_and_room_id($user, $reservation_id, $new_room_id);
     
     $this->update_reservations_status_on_details_change($old_reservation_details, $new_reservation_details);
     return $info;
    }
    if(!$is_changable){
      $avaliable_rooms = $this->roomsDao->get_unoccupied_rooms($check_in, $check_out);
      $rooms_ids = implode(", ", $avaliable_rooms); 
      throw new Exception("Room " .$new_room_id. " is occupied, avaliable rooms are: ". strval($rooms_ids), 400);
    }

  }


  public function update_reservation_details_for_existing_room($user, $data){
    if( $user['rl'] == "ADMIN" ){
      return $update_info = $this->dao->update_reservation_details($data);
    }else{
      throw new Exception("You are not allowed",403);
    }
  }



  public function update_reservations_status_on_details_change($old_details, $new_details){
    $reservation_id = $new_details["reservation_id"];
    $room_id = $new_details["room_id"];
    $children = $new_details["children"];
    $adults = $new_details["adults"];
    $check_in = $new_details["check_in"];
    $check_out = $new_details["check_out"];

    $reservation = $this->reservationsDao->get_by_id($reservation_id);
    $reservation_status = $reservation["status"]; 

    if(in_array($reservation_status, ["FINALISED", "ACTIVE"]) && in_array($status, ["PENDING", "ACCEPTED", "REJECTED"])){
      throw new Exception("Forbidden change", 404);
    }

    if(in_array($reservation_status, ["ACCEPTED", "ACTIVE"])){

      $pending = $this->reservationsDao->get_reservations_for_change($reservation_id, $check_in, $check_out, $room_id, "PENDING");
      if($pending){
        $this->reservationsDao->update_reservation_status( strval($pending["reservations"]) , "REJECTED");
      }
    }
    
    
      // if active or accepted and date > change pending reservations 
      // if Rejected just change
      // if finalised just change
      // if pending just change 
      // if active or accepted and date < change pending reservations and rejected 


  }

}
?>
