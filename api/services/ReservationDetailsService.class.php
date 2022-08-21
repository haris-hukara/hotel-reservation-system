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
   $this->roomsDao = new RoomsDao();
  }
 
  public function get_reservation_details_by_user_id_and_reservation_id($user, $user_id, $reservation_id){
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
    
    public function get_reservation_details($user, $user_id, $reservation_id){
      $user_account;
    try {
      $user_account = $this->userAccountDao->get_by_id($user_id);
    } catch (\Exception $e) {
      throw $e;
    }

    if(!$user_account){
      throw new Exception("This account doesn't exist", 404);
    }
    if( $user_id == $user_account['id'] || $user['rl'] == "ADMIN" ){
      $reservations = $this->dao->get_reservation_details($user_id, $reservation_id);
      if(empty($reservations)){
        throw new Exception("This reservation doesn't exist", 404);
      }
      return $reservations;
    }elseif ($user['id'] !== $user_account['id']){
      throw new Exception("Not your account", 401);
    }else{
      throw new Exception("Oops something went wrong",400);
    }
    }

    public function get_reservation_price($user, $user_id, $reservation_id){
    $user_account;
    try {
      $user_account = $this->userAccountDao->get_by_id($user_id);
    } catch (\Exception $e) {
      throw $e;
    }
    
    if(!$user_account){
      throw new Exception("This account doesn't exist", 404);
    }
    if( $user_id == $user_account['id'] || $user['rl'] == "ADMIN" ){
      $reservations = $this->dao->get_reservation_details($user_id, $reservation_id);
      if(empty($reservations)){
        throw new Exception("This reservation doesn't exist", 404);
      }

      $total_price = 0;
      foreach($reservations as $arr => $val) {
        $total_price += ($reservations[$arr]["price"]);
      }
      return ["total_price"=>$total_price];
    }elseif ($user['id'] !== $user_account['id']){
      throw new Exception("Not your account", 401);
    }else{
      throw new Exception("Oops something went wrong",400);
    }
  }

  public function delete_all_details_by_reservation_id($id){
    return $this->dao->delete_all_details_by_reservation_id($id);
  }

  public function add_reservation_details($details){
    if(!isset($details['reservation_id'])) throw new Exception("Reservation ID is missing",400);
    if(!isset($details['check_in'])) throw new Exception("Check in date is missing",400);
    if(!isset($details['check_out'])) throw new Exception("Check out date is missing",400);
    if(!isset($details['room_id'])) throw new Exception("Room ID is missing",400);
    if(!isset($details['children'])) throw new Exception("Number of children is missing",400);
    if(!isset($details['adults'])) throw new Exception("Number of adults is missing",400);
    

    if(!parent::date_format_check($details['check_in'])) throw new Exception("Check-in date format is not valid",400);
    if(!parent::date_format_check($details['check_out'])) throw new Exception("Check-out date format is not valid",400);
    if( $details['check_out'] < $details['check_in'] ) throw new Exception("Check-out date can't be lower than check-in date",400);
    
    $room = $this->roomsDao->check_room_availability($details['room_id'], $details['check_in'], $details['check_out']);
    if(!empty($room)){
      $this->delete_all_details_by_reservation_id($details['reservation_id']);
      throw new Exception("Room id: " .$details['room_id']. " is not avaliable between ". $details['check_in']." and ". $details['check_out'], 400);
    }
    
    try {
      // add details
      $reservation_details = $this->dao->add($details);    
      return $reservation_details;
      
    } catch (\Exception $e) {
      if(str_contains($e->getMessage(), 'reservation_details.PRIMARY')){
              throw new Exception("This reservation details already exist", 400, $e);
          } else { 
            throw new Exception($e, 400);
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
  
  public function get_reservation_details_by_id($user, $reservation_id){

  if( $user['rl'] == "ADMIN" ){
      $reservations = $this->dao->get_reservation_details_by_reservation_id( $reservation_id);
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
