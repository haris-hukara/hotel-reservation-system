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
        
        $order = substr($order, 1);
        
        $query ="SELECT r.id, ua.user_details_id, r.status, rd.check_in, rd.check_out, r.created_at, GROUP_CONCAT(rd.room_id) AS rooms
        FROM user_account ua
        JOIN reservations r ON r.user_details_id = ua.user_details_id 
        JOIN reservation_details rd ON rd.reservation_id = r.id
        JOIN rooms rm ON rm.id = rd.room_id
        WHERE LOWER(r.status) LIKE CONCAT('%', :status, '%')
        GROUP BY r.id
        ORDER BY ${order} ${order_direction}
        LIMIT ${limit} OFFSET ${offset}";

        $old_query = "SELECT * 
        FROM reservations
        WHERE LOWER(status) LIKE CONCAT('%', :status, '%')
        ORDER BY ${order} ${order_direction}
        LIMIT ${limit} OFFSET ${offset}";

        return $this->query( $query, ["status" => strtolower($search)]);
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

    public function get_pending_reservations(){
      return $this->query( "SELECT *
                            FROM reservations r
                            JOIN reservation_details rd ON rd.reservation_id = r.id
                            WHERE r.status LIKE 'PENDING'
                            ORDER BY r.created_at ASC", 
                            []);
    }


    public function get_reservations_for_change($reservation_id, $check_in, $check_out, $rooms_ids, $status){
        
        $rooms_ids = "( ".strval($rooms_ids)." )";
        $query= "SELECT GROUP_CONCAT( DISTINCT(r.id)) AS reservations
        FROM reservations r
        JOIN reservation_details rd ON rd.reservation_id = r.id
        WHERE r.id != :reservation_id
        AND rd.room_id IN $rooms_ids
        AND r.status = :status
        AND 
        ((rd.check_in BETWEEN :check_in AND :check_out) OR
         (rd.check_out BETWEEN :check_in AND :check_out))
        GROUP BY r.status";
        return $this->query_unique($query,        
          [
            "reservation_id" => $reservation_id,
            "status" => $status,
            "check_in" => $check_in,
            "check_out" => $check_out,
        ]);

    
    }


    public function update_reservation_status($reservations, $status){
        $re = "(". $reservations . ")";
        $params = [ "status" => $status];
        $this->query(
                    ("UPDATE reservations r
                    SET r.status = :status
                    WHERE r.id IN ". $re),
                    $params);

       return $params;
       }


   
     
}
?>

