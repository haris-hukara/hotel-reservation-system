<?php 
require_once dirname(__FILE__)."/BaseService.class.php";
require_once dirname(__FILE__)."/../dao/ReservationDetailsDao.class.php";
require_once dirname(__FILE__)."/../dao/ReservationsDao.class.php";
require_once dirname(__FILE__)."/../dao/UserAccountDao.class.php";

class ReservationsService extends BaseService{
  protected $userAccountDao;
  protected $ReservationDetailsDao;

 public function __construct(){
   $this->dao = new ReservationsDao();   
   $this->userAccountDao = new UserAccountDao();   
   $this->reservationDetailsDao = new ReservationDetailsDao();   
  }



  public function add_reservation($data){   
    
   $reservation = parent::add([ 
      "user_details_id" => $data['user_details_id'],
      "payment_method_id" => $data['payment_method_id'],
      "created_at" => date(Config::DATE_FORMAT)
    ]);
    return $reservation;
  }

  

  public function get_reservations($search, $offset, $limit, $order){
    if ($search){
      return ($this->dao->get_reservations($search, $offset, $limit, $order));
    }else{
      return ($this->dao->get_all($offset,$limit, $order));
    }
  }

  public function update_reservation($id, $data){
    
    $reservation = parent::update($id,$data); 
  
    return $reservation;
  }

    public function get_all_user_reservations($user_id, $user, $offset, $limit, $order ){
    $user_account;
    try {
      $user_account = $this->userAccountDao->get_by_id($user_id);
    } catch (\Exception $e) {
      throw $e;
    }
    if($user_account && $user['id'] == $user_account['id'] || $user['rl'] == "ADMIN" ){
    $reservations = $this->dao->get_all_user_reservations($user_id , $offset, $limit, $order);

      if(!empty($reservations)){
        return $reservations;
      }elseif(!$user_account){
        throw new Exception("This account doesn't exist", 404);
      }else{
        return ["message"=>"No reservations avaliable"];
      }
    }else{
      throw new Exception("Not your account", 401);
    }
  }


    public function change_reservation_status($user, $reservation_id, $status){

      $reservation_details = $this->get_reservation_details_by_id($user, $reservation_id);
      $check_in = $reservation_details[0]["check_in"];
      $check_out = $reservation_details[0]["check_out"];

      $reservation_status = $this->get_by_id($reservation_id)["status"];

      if($status == $reservation_status){
        return ["message" => "Nothing to change"];
      };

      if(in_array($reservation_status, ["FINALISED", "ACTIVE"]) && in_array($status, ["PENDING", "ACTIVE", "REJECTED"])){
        throw new Exception("Forbidden change", 404);
      }


      $rooms = [];
      foreach($reservation_details as $value){
        array_push($rooms, $value["room_id"]);
      }
      $rooms_ids = implode(", ", $rooms); 

       $active = $this->dao->get_reservations_for_change($reservation_id, $check_in, $check_out, $rooms_ids, "ACTIVE");
       $accepted = $this->dao->get_reservations_for_change($reservation_id, $check_in, $check_out, $rooms_ids, "ACCEPTED");
       $pending = $this->dao->get_reservations_for_change($reservation_id, $check_in, $check_out, $rooms_ids, "PENDING");
       $rejected = $this->dao->get_reservations_for_change($reservation_id, $check_in, $check_out, $rooms_ids, "REJECTED");

      if ($reservation_status == "PENDING" && $status == "ACCEPTED"){
      
      if($accepted OR $active){
              throw new Exception("It is not possible to accept this reservation, rooms are occupied", 404);
            }
        if($pending){
          $this->dao->update_reservation_status( strval($pending["reservations"]) , "REJECTED");
        }
      }

      if ($reservation_status == "ACCEPTED" && $status == "REJECTED"){
       if($rejected){
         $this->dao->update_reservation_status( strval($rejected["reservations"]) , "PENDING");
        }
      } 
      

      if ($reservation_status == "REJECTED" && $status == "ACCEPTED"){
       if($accepted OR $active){
         throw new Exception("It is not possible to accept this reservation, rooms are occupied", 404);
        }
        if($pending){
          $this->dao->update_reservation_status(strval($pending["reservations"]) , "REJECTED");
        }
      } 
      
         
      return $this->dao->update_reservation_status($reservation_id, $status);

    }

    public function get_reservation_details_by_id($user, $reservation_id){
      if( $user['rl'] == "ADMIN" ){
        $reservations = $this->reservationDetailsDao->get_reservation_details_by_reservation_id($reservation_id);
          if(empty($reservations)){
            throw new Exception("Reservation details don't exist", 404);
          }
          return $reservations;
      }else{
          throw new Exception("You are not allowed",403);
        }
    }

   
    



}

?>
