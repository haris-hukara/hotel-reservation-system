<?php 
require_once dirname(__FILE__)."/BaseDao.class.php";

class UserDetailsDao extends BaseDao{

    public function __construct(){
        parent::__construct("user_details");
    }

    public function get_user_details($search, $offset, $limit, $order){
        list($order_column, $order_direction) = self::parse_order($order);
            
            return $this->query( "SELECT * 
                                  FROM user_details
                                  WHERE LOWER(email) LIKE CONCAT('%', :email, '%')
                                  ORDER BY ${order} ${order_direction}
                                  LIMIT ${limit} OFFSET ${offset}", 
                                 ["email" => strtolower($search)]);
    }

    public function get_user_details_by_account_id_and_details_id($account_id, $details_id){
            
            return $this->query_unique( "SELECT ud.*
                                         FROM user_details ud
                                         JOIN user_account ua ON ud.id = ua.user_details_id 
                                         WHERE ua.id = :account_id
                                         AND ud.id = :details_id",
                                 ["account_id" => $account_id,
                                  "details_id" => $details_id ]);
    }

    public function remove_user_details_by_id($id){
        $this->query_unique("DELETE FROM user_details WHERE id = :id", ["id" => $id]);
    }

    public function get_user_details_by_account_id($id){
        
       return $this->query_unique("SELECT ud.* 
                                    FROM user_details ud 
                                    JOIN user_account ua ON ud.id =  ua.user_details_id
                                    WHERE ua.id = :id", 
                                    ["id" => $id]);
    }
   
}
?>