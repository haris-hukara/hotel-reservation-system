<?php 
 require_once dirname(__FILE__)."/BaseDao.class.php";

class ReservationsDao extends BaseDao{

    public function __construct(){
        parent::__construct("reservations");    
    }

    public function get_resrvation_by_user_id($user_details_id){
        return $this->query( "SELECT * 
                              FROM reservations
                              WHERE user_details_id = :user_details_id", 
                             ["user_details_id" => $user_details_id]);
    }
   
    public function get_reservations($search, $offset, $limit, $order = "-id"){
        switch (substr($order, 0, 1)){
            case '-': $order_direction = 'ASC'; break;
            case '+': $order_direction = 'DESC'; break;
            default: throw new Exception("Invalid order format"); break;
        };
        
        return $this->query( "SELECT * 
                              FROM reservations
                              WHERE LOWER(status) LIKE CONCAT('%', :status, '%')
                              ORDER BY ${order} ${order_direction}
                              LIMIT ${limit} OFFSET ${offset}", 
                             ["status" => strtolower($search)]);
    }

    
    public function get_all_user_reservations($account_id, $offset, $limit, $order){
        $order_direction;
        switch (substr($order, 0, 1)){
            case '-': $order_direction = 'ASC'; break;
            case '+': $order_direction = 'DESC'; break;
            default: throw new Exception("Invalid order format"); break;
        };

        $order = substr($order, 1);
        $order = "r.".$order;

        $query =   "SELECT r.*
                    FROM user_account ua
                    JOIN reservations r ON r.user_details_id = ua.user_details_id
                    WHERE ua.id = :account_id
                    ORDER BY ${order} ${order_direction}
                    LIMIT ${limit} OFFSET ${offset}";

        return $this->query($query,["account_id" => $account_id]);
    }


}
?>

