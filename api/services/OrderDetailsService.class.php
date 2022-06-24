<?php 
require_once dirname(__FILE__)."/BaseService.class.php";
require_once dirname(__FILE__)."/../dao/OrderDetailsDao.class.php";
require_once dirname(__FILE__)."/../dao/ProductsDao.class.php";

class OrderDetailsService extends BaseService{

  private $productStockDao;
  private $userAccountDao;

 public function __construct(){
   $this->dao = new OrderDetailsDao();   
   $this->productStockDao = new ProductStockDao();
   $this->userAccountDao = new UserAccountDao();
  }

  public function get_order_details_by_id($user, $order_id){
    if( $user['rl'] != "ADMIN"){
        return $this->dao->get_order_details_by_account_id_and_order_id($user['id'], $order_id);
    }
    return $this->dao->get_order_details_by_order_id($order_id);
  } 

  public function get_order_price_by_id($user, $id){
    if( $user['rl'] == "ADMIN"){
      return ($this->dao->get_order_price_by_id($id));
    }else{
      return ($this->dao->get_order_price_by_account_id($user['id'], $id));
    }
  }

  public function add_order_details($details){
    if(!isset($details['order_id'])) throw new Exception("Order ID is missing");
    if(!isset($details['product_id'])) throw new Exception("Product ID is missing");
    if(!isset($details['size_id'])) throw new Exception("Size ID is missing");
    if(!isset($details['quantity'])) throw new Exception("Quantity is missing");
    
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
