<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once dirname(__FILE__).'/dao/CountryDao.class.php';
require_once dirname(__FILE__).'/dao/OrderDetailsDao.class.php';
require_once dirname(__FILE__).'/dao/UserAccountDao.class.php';


   // $dao = new CountryDao();
   // $result = $dao->get_country_name_by_id(1);
   
  $entity = [];
  $entity['password'] ="password";
   $dao = new UserAccountDao();
    $result = $dao->update_user_by_email("example@email.ba", $entity);
        print_r($result);

?>
 