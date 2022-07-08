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
      if(!isset($userDetails['first_name'])) throw new Exception("Name is missing");
      if(!isset($userDetails['last_name'])) throw new Exception("Surname is missing");
      if(!isset($userDetails['email'])) throw new Exception("Email is missing");
      if(!isset($userDetails['phone_number'])) throw new Exception("Phone is missing");
      if(!isset($userDetails['city'])) throw new Exception("City is missing");
      if(!isset($userDetails['country'])) throw new Exception("Country is missing");
      if(!isset($userDetails['birth_date'])) throw new Exception("Birth date is missing");
       
        $details = $this->dao->add([
          "first_name" => $userDetails['first_name'],
          "last_name" => $userDetails['last_name'],
          "email" => $userDetails['email'],
          "phone_number" => $userDetails['phone_number'],
          "city" => $userDetails['city'],
          "country" => $userDetails['country'],
          "birth_date" => $userDetails['birth_date'],
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