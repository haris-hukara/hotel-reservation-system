<?php 
require_once dirname(__FILE__)."/BaseService.class.php";
require_once dirname(__FILE__)."/../dao/ReservationDetailsDao.class.php";
require_once dirname(__FILE__)."/../dao/RoomsDao.class.php";

class ReservationDetailsService extends BaseService{

  private $productStockDao;
  private $userAccountDao;

 public function __construct(){
   $this->dao = new ReservationDetailsDao();   
   $this->userAccountDao = new UserAccountDao();
  }
 
  public function get_reservation_details_by_id($user, $user_id, $reservation_id){
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
    
    public function get_reservation_price_by_account_id_and_reservation_id($user, $user_id, $reservation_id){
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
      $reservation_price = $this->dao->get_reservation_price_by_account_id_and_reservation_id($user_id, $reservation_id);
      if(!$reservation_price['reservation_id']){
        throw new Exception("This reservation doesn't exist", 404);
      }
      return $reservation_price;
    }elseif ($user['id'] !== $user_account['id']){
      throw new Exception("Not your account", 401);
    }else{
      return ["message"=>"Oops something went wrong"];
    }
  }

 
  public function add_reservation_details($details){
    if(!isset($details['reservation_id'])) throw new Exception("Reservation ID is missing");
    if(!isset($details['room_id'])) throw new Exception("Room ID is missing");
    if(!isset($details['children'])) throw new Exception("Number of children is missing");
    if(!isset($details['adults'])) throw new Exception("Number of adults is missing");
    
    $product = $this->productStockDao->get_product_stock_by_size_id($details['product_id'],
                                                                    $details['size_id']);
    
    $product_stock = $product['quantity_avaliable']; 
    $user_quantity = $details['quantity'];

    if ( $details['quantity'] <= 0 || $details['quantity'] > $product_stock){
        throw new Exception("Enter a valid quantity. Quantity in stock: ". $product_stock );
        
    }else{ 
      try {
        // add details
        $order_details = $this->dao->add($details);
        // update stock
        $this->productStockDao->change_product_stock($details['product_id'],
                                                     $details['size_id'],
                                                     $details['quantity']);
      
      return $order_details;
      
        } catch (\Exception $e) {
          if(str_contains($e->getMessage(), 'order_details.PRIMARY')){
              throw new Exception("This order already exist", 400, $e);
          } else { 
            throw $e;
            }
      }
    }
     
  }

  public function update_order_details_quantity($details){
    
    if(!isset($details['order_id'])) throw new Exception("Order ID is missing");
    if(!isset($details['product_id'])) throw new Exception("Product ID is missing");
    if(!isset($details['size_id'])) throw new Exception("Size ID is missing");
    if(!isset($details['quantity'])) throw new Exception("Quantity is missing");

    $new_quantity = $details['quantity']; 

    $stored_details = $this->dao->get_order_details($details['order_id'],
                                                    $details['product_id'],
                                                    $details['size_id']);
    $old_order_quantity  = $stored_details['quantity'];

    $product = $this->productStockDao->get_product_stock_by_size_id($details['product_id'],
                                                                    $details['size_id']);
    $current_product_stock = $product['quantity_avaliable']; 

    $stock_quantity = $old_order_quantity + $current_product_stock;

    if ( $new_quantity <= 0 || $new_quantity > $stock_quantity){
         throw new Exception("Enter a valid quantity. Quantity in stock: ". $stock_quantity );
    }else{
    
      if($new_quantity != $old_order_quantity) { 
        // update stock and update order details
        $new_stock = $current_product_stock - ( $new_quantity - $old_order_quantity );
        $this->productStockDao->set_product_stock($details['product_id'],
                                                  $details['size_id'], 
                                                  $new_stock);

       return $this->dao->update_order_details_quantity($details['order_id'],
                                                        $details['product_id'],
                                                        $details['size_id'], 
                                                        $details['quantity']);
      }else{
        throw new Exception("Quantity input is same, nothing to change");
      }
    }
  }   
                 
}
?>
