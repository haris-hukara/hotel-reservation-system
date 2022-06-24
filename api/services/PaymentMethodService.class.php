<?php 
require_once dirname(__FILE__)."/BaseService.class.php";
require_once dirname(__FILE__)."/../dao/PaymentMethodDao.class.php";



class PaymentMethodService extends BaseService{
 
  
   public function __construct(){
     $this->dao = new PaymentMethodDao();   
    }

    public function get_payment_method($search, $offset, $limit, $order){
            if ($search){
              return ($this->dao->get_payment_method($search, $offset, $limit, $order));
            }else{
              return ($this->dao->get_all($offset,$limit, $order));
      }
 }

    public function add_payment_method($data){
        if(!isset($data['name'])) throw new Exception("Country name is missing");

        $payment_name = ucwords(strtolower($data['name']));       
        
      try {
        $payment_method = parent::add([
        "name" => $payment_name ]); 
        
      } catch (\Exception $e) {
        if(str_contains($e->getMessage(), 'payment_method.name_UNIQUE')){
          throw new Exception("Payment Method with same name already exist", 400, $e);
        }else{
          throw $e;    
        }  
        
      }
      return $payment_method;
        
    }
    
    public function update_payment_method($id, $data){
      
      if(!isset($data['name'])) throw new Exception("Name is missing");
      
      $payment_method = parent::update($id,
                            [ "name" => ucwords(strtolower($data['name'])) ]
      ); 
        return $payment_method;
    }

}
?>




  