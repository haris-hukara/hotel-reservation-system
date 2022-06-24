<?php 
 require_once dirname(__FILE__)."/BaseDao.class.php";

class OrdersDao extends BaseDao{

    public function __construct(){
        parent::__construct("orders");    
    }

    public function get_resrvation_by_user_id($user_details_id){
        return $this->query( "SELECT * 
                              FROM reservations
                              WHERE user_details_id = :user_details_id", 
                             ["user_details_id" => $user_details_id]);
    }
   
    public function get_orders($search, $offset, $limit, $order = "-id"){
        switch (substr($order, 0, 1)){
            case '-': $order_direction = 'ASC'; break;
            case '+': $order_direction = 'DESC'; break;
            default: throw new Exception("Invalid order format"); break;
        };
        
        return $this->query( "SELECT * 
                              FROM orders
                              WHERE LOWER(shipping_address) LIKE CONCAT('%', :shipping_address, '%')
                              ORDER BY ${order} ${order_direction}
                              LIMIT ${limit} OFFSET ${offset}", 
                             ["shipping_address" => strtolower($search)]);
    }

    public function get_all_orders_by_account_id($account_id){
        return $this->query( "SELECT o.*
                              FROM user_account ua
                              JOIN orders o ON o.user_details_id = ua.user_details_id
                              WHERE ua.id = :account_id", 
                             ["account_id" => $account_id]);
    }


}
?>

