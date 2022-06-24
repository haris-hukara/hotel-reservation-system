 <?php 
 require_once dirname(__FILE__)."/BaseDao.class.php";

class UserAccountDao extends BaseDao{

    public function __construct(){
        parent::__construct("user_account");
    }
    
    // $order = "-id" sort by id in desc 
    public function get_user_account($search, $offset, $limit, $order){
    list($order_column, $order_direction) = self::parse_order($order);
        
        return $this->query( "SELECT * 
                              FROM user_account
                              WHERE LOWER(email) LIKE CONCAT('%', :email, '%')
                              ORDER BY ${order} ${order_direction}
                              LIMIT ${limit} OFFSET ${offset}", 
                             ["email" => strtolower($search)]);
    }

    public function get_user_by_email($email){
        return $this->query_unique("SELECT * FROM user_account
                                    WHERE email = :email", ["email" => $email]);
    }

    public function update_user_by_email($email, $entity){
        $this->execute_update("user_account", $email, $entity, "email");
    }

    public function get_user_by_token($token){
        return $this->query_unique("SELECT * FROM user_account
                                    WHERE token = :token", ["token" => $token]);
    }

}
?>