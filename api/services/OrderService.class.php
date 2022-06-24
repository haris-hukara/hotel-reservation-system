<?php 
require_once dirname(__FILE__)."/BaseService.class.php";
require_once dirname(__FILE__)."/../dao/OrdersDao.class.php";

class OrderService extends BaseService{

 public function __construct(){
   $this->dao = new OrdersDao();   
  }

  public function get_resrvation_by_user_id($user_id){
    return $this->dao->get_resrvation_by_user_id($user_id);
  }

  public function add_order($order){   
    
   $order = parent::add([ 
      "user_details_id" => $order['user_details_id'],
      "shipping_address" => $order['shipping_address'],
      "payment_method_id" => $order['payment_method_id'],
      "order_date" => date(Config::DATE_FORMAT),
      "status" => $order['status']
    ]);
    return $order;
  }

  

  public function get_orders($search, $offset, $limit, $order){
    if ($search){
      return ($this->dao->get_orders($search, $offset, $limit, $order));
    }else{
      return ($this->dao->get_all($offset,$limit, $order));
    }
  }

  public function update_order($id, $data){
    
    $order = parent::update($id,
    ["shipping_address" => ucwords(strtolower($data['shipping_address'])),
     "payment_method_id" => $data['payment_method_id'],
     "status" => $data['status']
    ]
    ); 
  
    return $order;
  }

  public function get_all_orders_for_user($user){
    return $this->dao->get_all_orders_by_account_id($user['id']);
  }


}

?>
