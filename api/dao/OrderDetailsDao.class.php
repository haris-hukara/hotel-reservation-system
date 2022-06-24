<?php 
 require_once dirname(__FILE__)."/BaseDao.class.php";

class OrderDetailsDao extends BaseDao{

    public function __construct(){
        parent::__construct("order_details");
    }
    
    public function get_order_details_by_account_id_and_order_id($account_id, $order_id){
      $details =  $this->query("SELECT od.order_id, 
                                        p.name AS 'product_name', 
                                        ps.name AS 'category',
                                        p.gender_category AS 'gender',
                                        s.name AS 'size',
                                        od.quantity,
                                        p.unit_price, 
                                        (quantity*unit_price) AS total,
                                        p.image_link
                                  FROM order_details od 
                                  JOIN products p ON p.id = od.product_id
                                  JOIN orders o ON o.id = od.order_id
                                  JOIN user_details ud ON ud.id = o.user_details_id
                                  JOIN user_account ua ON ua.user_details_id = o.user_details_id
                                  JOIN sizes s ON s.id = od.size_id
                                  JOIN product_subcategory ps ON ps.id = p.subcategory_id
                                  WHERE ua.id = :account_id AND o.id = :order_id", 
                                ["order_id" => $order_id,
                                 "account_id" => $account_id]);
        return $details;
    }   
    
    public function get_order_details_by_order_id($order_id){
    return $this->query("SELECT 	od.order_id, 
                                  p.name AS 'product_name', 
                                  ps.name AS 'category',
                                  p.gender_category AS 'gender',
                                  s.name AS 'size',
                                  od.quantity,
                                  p.unit_price, 
                                  (quantity*unit_price) AS total,
                                  p.image_link

                          FROM order_details od 
                          JOIN products p ON p.id = od.product_id
                          JOIN sizes s ON s.id = od.size_id
                          JOIN product_subcategory ps ON ps.id = p.subcategory_id
                          WHERE od.order_id = :order_id", 
                          ["order_id" => $order_id]);
    }
     
    public function get_order_price_by_id($id){
      $details =  $this->query("SELECT 	od.order_id, 
                                            SUM(od.quantity * p.unit_price) AS 'total_price'
                                       FROM order_details od 
                                       JOIN products p ON p.id = od.product_id
                                       WHERE od.order_id = :order_id", 
                                       ["order_id" => $id]);
        return $details;
    }   
    public function get_order_price_by_account_id($account_id, $id){
      $details =  $this->query("SELECT 	od.order_id, ua.id AS 'user_id', 
                                SUM(od.quantity * p.unit_price) AS 'total_price'
                                FROM order_details od 
                                JOIN products p ON p.id = od.product_id
                                JOIN orders o ON o.id = od.order_id
                                JOIN user_account ua ON ua.user_details_id = o.user_details_id
                                WHERE od.order_id = :order_id
                                AND ua.id = :account_id", 
                                       ["order_id" => $id,
                                        "account_id" => $account_id]);
        return $details;
    }   

    
    public function get_order_details($order_id, $product_id, $size_id){

        $query = "SELECT *
                  FROM order_details
                  WHERE order_id = :order_id  
                  AND product_id = :product_id  
                  AND size_id = :size_id" ;

        return $this->query_unique($query, 
                            ["order_id" => $order_id,
                             "product_id" => $product_id,
                             "size_id" => $size_id]
                            );   
    
    }

    public function update_order_details_quantity($order_id, $product_id, $size_id, $quantity){
        $params = [ "order_id" => $order_id, 
                    "product_id" => $product_id, 
                    "size_id" => $size_id, 
                    "quantity" => $quantity];
        $this->query(
                    ("UPDATE order_details
                      SET quantity = :quantity
                      WHERE order_id = :order_id 
                      AND product_id = :product_id 
                      AND size_id = :size_id"),
                   
                    $params);
       return $params;
       }


}
?>