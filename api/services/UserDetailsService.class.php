<?php 
require_once dirname(__FILE__)."/BaseService.class.php";
require_once dirname(__FILE__)."/../dao/UserDetailsDao.class.php";
require_once dirname(__FILE__)."/../dao/UserAccountDao.class.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class UserDetailsService extends BaseService{
    
    private $userAccountDao;
  
    public function __construct(){
     $this->dao = new UserDetailsDao();
     $this->userAccountDao = new UserAccountDao();   
    }
    /* add override */
    public function add($userDetails){
      if(!isset($userDetails['name'])) throw new Exception("Name is missing");
      if(!isset($userDetails['surname'])) throw new Exception("Surname is missing");
      if(!isset($userDetails['email'])) throw new Exception("Email is missing");
      if(!isset($userDetails['phone_number'])) throw new Exception("Phone is missing");
      if(!isset($userDetails['city'])) throw new Exception("City is missing");
      if(!isset($userDetails['zip_code'])) throw new Exception("Zip Code is missing");
      if(!isset($userDetails['address'])) throw new Exception("Address is missing");
       
        $details = $this->dao->add([
        "name" => $userDetails['name'],
        "surname" => $userDetails['surname'],
        "email" => $userDetails['email'],
        "phone_number" => $userDetails['phone_number'],
        "city" => $userDetails['city'],
        "zip_code" => $userDetails['zip_code'],
        "address" => $userDetails['address'],
        "created_at" => date(Config::DATE_FORMAT)
      ]);

      return $details;
  }

  public function get_user_details($search, $offset, $limit, $order){
    if ($search){
      return ($this->dao->get_user_details($search, $offset, $limit, $order));
    }else{
      return ($this->dao->get_all($offset,$limit, $order));
    }
  }
  
  public function get_user_details_by_account_id_and_details_id($user, $details_id){
    if($user['rl'] == "ADMIN"){
      return $this->dao->get_by_id($details_id);
    }
      return $this->dao->get_user_details_by_account_id_and_details_id($user['id'], $details_id);
    }

    public function update_user_details($user, $details_id, $details){
      $user_account = $this->userAccountDao->get_by_id($user['id']);
      if($user_account['user_details_id'] != $details_id ){
          throw new Exception("Invalid details", 403);
      }
         return $this->update($details_id, $details);
  }
  

}

?>