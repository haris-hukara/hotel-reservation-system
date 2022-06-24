<?php 
 require_once dirname(__FILE__)."/BaseDao.class.php";

class PaymentMethodDao extends BaseDao{

    public function __construct(){
        parent::__construct("payment_method");
    }
   
 
    public function get_payment_method_name_by_id($id){
        $payment_name = $this->get_by_id($id);
        return $payment_name['name'];
    }
    
    public function get_payment_method($search, $offset, $limit, $order = "-id"){
        switch (substr($order, 0, 1)){
            case '-': $order_direction = 'ASC'; break;
            case '+': $order_direction = 'DESC'; break;
            default: throw new Exception("Invalid order format"); break;
        };
        
        return $this->query( "SELECT * 
                              FROM payment_method
                              WHERE LOWER(name) LIKE CONCAT('%', :name, '%')
                              ORDER BY ${order} ${order_direction}
                              LIMIT ${limit} OFFSET ${offset}", 
                             ["name" => strtolower($search)]);
    }

}
?>