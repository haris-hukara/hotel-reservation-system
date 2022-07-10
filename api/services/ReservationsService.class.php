<?php 
require_once dirname(__FILE__)."/BaseService.class.php";
require_once dirname(__FILE__)."/../dao/ReservationsDao.class.php";
require_once dirname(__FILE__)."/../dao/UserAccountDao.class.php";

class ReservationsService extends BaseService{
  protected $userAccountDao;

 public function __construct(){
   $this->dao = new ReservationsDao();   
   $this->userAccountDao = new UserAccountDao();   
  }



  public function add_reservation($data){   
    
   $reservation = parent::add([ 
      "user_details_id" => $data['user_details_id'],
      "payment_method_id" => $data['payment_method_id'],
      "check_in" => $data['check_in'],
      "check_out" => $data['check_out'],
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
        return ["message"=>"This account doesn't exist"];
      }else{
        return ["message"=>"No reservations avaliable"];
      }
    }else{
      throw new Exception("Not your account", 401);
    }
  }


}

?>
