<?php 
 require_once dirname(__FILE__)."/BaseDao.class.php";

class RoomsDao extends BaseDao{

    public function __construct(){
        parent::__construct("rooms");
    }

            
    public function get_all_rooms(){
    return $this->query("SELECT * FROM rooms", []);
    }

 public function get_rooms($search, $offset, $limit, $order = "-id"){

    switch (substr($order, 0, 1)){
        case '-': $order_direction = 'ASC'; break;
        case '+': $order_direction = 'DESC'; break;
        default: throw new Exception("Invalid order format"); break;
    };

    $params = [];
    $params["search"] = $search;
    
    $query = "SELECT   r.id,
                       r.name, 
                       r.description,                     
                       r.night_price,
                       r.image_link
               FROM rooms r
               WHERE LOWER(r.name) LIKE CONCAT('%', :search, '%')";

        
        $order = substr($order, 1);
        $order = "r.".$order;
        $query .= "ORDER BY ${order} ${order_direction}
                   LIMIT ${limit} OFFSET ${offset}";

        return $this->query($query,$params);
 }

 public function get_avaliable_rooms_count($search=""){
    $params = [];
    $params["search"] = $search;

        $avaliable_rooms = "SELECT DISTINCT ps.room_id
                                    FROM rooms p 
                                    JOIN room_stock ps ON p.id = ps.room_id
                                    WHERE ps.quantity_avaliable > 0";

        $query = "SELECT COUNT(p.id) AS avaliable_rooms
                  FROM rooms p
                  JOIN room_subcategory ps ON p.subcategory_id = ps.id  
                  WHERE p.id IN ({$avaliable_rooms})
                  AND LOWER(p.name) LIKE CONCAT('%', :search, '%')";
        
        return $this->query_unique($query,$params);
 }

    
 public function get_avaliable_rooms($search, $offset, $limit, $order, $check_in, $check_out){

        switch (substr($order, 0, 1)){
                case '-': $order_direction = 'ASC'; break;
                case '+': $order_direction = 'DESC'; break;
                default: throw new Exception("Invalid order format"); break;
            };

        $params = [];
        $params["search"] = $search;
        $params["check_in"] = $check_in;
        $params["check_out"] = $check_out;
    
  
        $unavaliable_rooms = "SELECT DISTINCT rd.room_id
                              FROM reservations r
                              JOIN reservation_details rd ON r.id = rd.reservation_id
                              WHERE r.check_in BETWEEN :check_in AND :check_out
                              AND r.status = 'FINALISE'";

        $query = "SELECT * FROM rooms ro
                  WHERE ro.id NOT IN ( ${unavaliable_rooms} )
                  AND LOWER(ro.name) LIKE CONCAT('%', :search, '%')";
                        
        $order = substr($order, 1);
        $order = "ro.".$order;

        $query .= "ORDER BY ${order} ${order_direction}
                LIMIT ${limit} OFFSET ${offset}";

        return $this->query($query,$params);

        }

        public function get_avaliable_sizes($room_id){
            $query =  "SELECT ps.room_id, ps.size_id, s.name, ps.quantity_avaliable
                       FROM rooms p 
                       JOIN room_stock ps ON p.id = ps.room_id
                       JOIN sizes s ON s.id = ps.size_id
                       WHERE p.id = :room_id
                       AND ps.quantity_avaliable > 0";

            $params = [];
            $params["room_id"] = $room_id;

            return $this->query($query,$params);
        }
        
        public function get_avaliable_room_by_id($room_id){
            
            $avaliable_rooms = "SELECT DISTINCT ps.room_id
                                FROM rooms p 
                                JOIN room_stock ps ON p.id = ps.room_id
                                WHERE ps.quantity_avaliable > 0";

            $query =  "SELECT p.*
                       FROM rooms p
                       JOIN room_subcategory ps ON p.subcategory_id = ps.id  
                       WHERE p.id IN ( {$avaliable_rooms} )
                       AND p.id = :room_id
                            ";

            $params = [];
            $params["room_id"] = $room_id;

            return $this->query_unique($query,$params);
        }




}
?>