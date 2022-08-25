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

 public function get_avaliable_rooms_count($search, $check_in, $check_out){
      
    $params = [];
    $params["search"] = $search;
    $params["check_in"] = $check_in;
    $params["check_out"] = $check_out;

    $unavaliable_rooms = "SELECT rd.room_id
                          FROM reservation_details rd
                          JOIN reservations r ON r.id = rd.reservation_id
                          WHERE ((rd.check_in BETWEEN :check_in AND :check_out) OR
                                 (rd.check_out BETWEEN :check_in AND :check_out))
                          AND r.status IN ('ACCEPTED','ACTIVE')";

    $query = "SELECT COUNT(ro.id) AS avaliable_rooms_count FROM rooms ro
              WHERE ro.id NOT IN ( ${unavaliable_rooms} )
              AND LOWER(ro.name) LIKE CONCAT('%', :search, '%')";
   
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
    
  
        $unavaliable_rooms = "SELECT rd.room_id
                              FROM reservation_details rd
                              JOIN reservations r ON r.id = rd.reservation_id
                              WHERE ((rd.check_in BETWEEN :check_in AND :check_out) OR
                                     (rd.check_out BETWEEN :check_in AND :check_out))
                              AND r.status IN ('ACCEPTED','ACTIVE')";

        $query = "SELECT * FROM rooms ro
                  WHERE ro.id NOT IN ( ${unavaliable_rooms} )
                  AND LOWER(ro.name) LIKE CONCAT('%', :search, '%')";
                        
        $order = substr($order, 1);
        $order = "ro.".$order;

        $query .= "ORDER BY ${order} ${order_direction} ";
        if($limit){$query .= "LIMIT ${limit} OFFSET ${offset}";};
      
        return $this->query($query,$params);

        }

        public function check_room_availability($room_id, $check_in, $check_out){
        $params = [];
        $params["check_in"] = $check_in;
        $params["check_out"] = $check_out;
        $params["room_id"] = $room_id;
    
  
        $unavaliable_rooms = "SELECT rd.room_id
                          FROM reservation_details rd
                          JOIN reservations r ON r.id = rd.reservation_id
                          WHERE ((rd.check_in BETWEEN :check_in AND :check_out) OR
                                 (rd.check_out BETWEEN :check_in AND :check_out))
                          AND r.status IN ('ACCEPTED','ACTIVE')";

        $query = "SELECT ro.id FROM rooms ro
                  WHERE ro.id = :room_id AND ro.id IN ( ${unavaliable_rooms} )";
                       
        return $this->query_unique($query,$params);

        }




}
?>