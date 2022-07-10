<?php 
require_once dirname(__FILE__)."/BaseService.class.php";
require_once dirname(__FILE__)."/../dao/UserAccountDao.class.php";
require_once dirname(__FILE__)."/../dao/UserDetailsDao.class.php";

require_once dirname(__FILE__)."/../clients/SMTPClient.class.php";


class UserAccountService extends BaseService{

  private $userDetailsDao;
  private $smtpClient;

   public function __construct(){ 
     $this->dao = new UserAccountDao();   
     $this->userDetailsDao = new UserDetailsDao();
     $this->smtpClient = new SMTPClient();
    }

    public function login($userAccount){
      $db_user = $this->dao->get_user_by_email($userAccount['email']);

      if(!isset($db_user['id'])) throw new Exception("User doesn't exist", 400);
      if($db_user['status'] != 'ACTIVE') throw new Exception("Account not active", 400);
      /* user password is hashed using md5 because same hashing is used when user is registering*/
      if($db_user['password'] != md5($userAccount['password'])) throw new Exception("Invalid password", 400);

      return $db_user;
    }

    public function getUserAccountById($user, $id){
      $user_account;
      try {
        $user_account = $this->dao->get_by_id($id);
      } catch (\Exception $e) {
        throw $e;
      }

      if($user_account && $user['id'] == $user_account['id'] ){
      return $user_account;
      }else{
        throw new Exception("Not your account", 401);
      }
    }

 

    public function forgot($userAccount){
      $db_user = $this->dao->get_user_by_email($userAccount['email']);
      if(!isset($db_user['id'])) throw new Exception("User doesn't exist", 400);

      
      $time_difference = strtotime(strval(date(Config::DATE_FORMAT))) 
                       - strtotime(strval($db_user['token_created_at']));
     

     // token can not be created more then once in 10 mins ( 600sec -> 10 mins)
      if ($time_difference < 600 ) throw new Exception("Be patient token is on the way", 400);
      
      $db_user = $this->update( $db_user['id'], 
                                ['token' => md5(random_bytes(16)),
                                 'token_created_at' => date(Config::DATE_FORMAT)]
                              );
      
      $this->smtpClient->send_recovery_token($db_user);
    }


    public function reset($userAccount){
      $db_user = $this->dao->get_user_by_token($userAccount['token']);

      if(!isset($db_user['id'])) throw new Exception("Invalid token", 400);
      
      $time_difference = strtotime(date(Config::DATE_FORMAT)) 
                       - strtotime($db_user['token_created_at']);

      if ($time_difference > 600 ) throw new Exception("Token expired", 400);

      $this->dao->update( $db_user['id'],
                          ['password' => md5($userAccount ['password']),
                           'token' => NULL ]
                        );
      return $db_user;
    }



    public function register($userAccount){
      if(!isset($userAccount['email'])) throw new Exception("Email is missing");
      
    try{
	  $this->dao->beginTransaction();

        $details = $this->userDetailsDao->add([
        "first_name" => $userAccount['first_name'],
        "last_name" => $userAccount['last_name'],
        "email" => $userAccount['email'],
        "phone_number" => $userAccount['phone_number'],
        "city" => $userAccount['city'],
        "country" => $userAccount['country'],
        "birth_date" => $userAccount['birth_date'],
        "created_at" => date(Config::DATE_FORMAT)
      ]);
      
      $userAccount = parent::add([
        "email" => $details['email'],
        "password" => md5($userAccount['password']),
        "user_details_id" => $details['id'],
        "status" => "PENDING",
        "role" => "USER",
        "created_at" => date(Config::DATE_FORMAT),
        "token" => md5(random_bytes(16))
      ]); 
      
       $this->dao->commit();
	    } catch (\Exception $e){
    	 $this->dao->rollBack();
       $this->userDetailsDao->remove_user_details_by_id($details['id']);
       if(str_contains($e->getMessage(), 'user_account.email_UNIQUE')){
         throw new Exception("Account with same email exsists in the database", 400, $e);
        }else{
          throw $e;    
        }  
      }
    
      $this->smtpClient->send_registration_token($userAccount);
  
      return $userAccount;
      }
    
    public function confirm($token){
      $userAccount = $this->dao->get_user_by_token($token);

      if(!isset($userAccount['id'])) throw new Exception("Invalid token", 400);

      $this->dao->update($userAccount['id'], ["status" => "ACTIVE", 'token' => NULL]);

       return $userAccount;
    }
  
    
   public function get_user_account($search, $offset, $limit, $order){
              if ($search){
                return ($this->dao->get_user_account($search, $offset, $limit, $order));
              }else{
                return ($this->dao->get_all($offset,$limit, $order));
        }
   }


   public function update_account($id, $data, $user){
    if($this->checkAccountPassword($user, $id, $data['current_password'])){
      $data['password'] = $data['new_password'];
      unset($data['current_password']);
      unset($data['new_password']);
      try{
      $this->dao->update($id, $data);
      } catch (\Exception $e) {
        if(str_contains($e->getMessage(), 'user_account.email_UNIQUE')){
          throw new Exception("Account with same email already exsists", 400, $e);
         }else{
           throw $e;    
         }  
        }
        return ["message"=>"Account info has been updated"];
      }else{
        return ["message"=>"Password is not correct"];
      };
   }

   public function checkAccountPassword($user, $id, $password){
    $user_account;
    try {
      $user_account =  $this->dao->get_by_id($id);
    } catch (\Exception $e) {
      throw $e;
    }

    if($user_account && $user['id'] == $user_account['id'] ){
      if ($user_account['password'] == md5($password)){
        return true;
      };
    }else{
      throw new Exception("Not your account", 401);
    }
      return false;
  }
  
  }
?>




  