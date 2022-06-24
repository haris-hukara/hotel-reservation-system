<?php 
 require_once dirname(__FILE__)."/BaseDao.class.php";

class ProductsDao extends BaseDao{

    public function __construct(){
        parent::__construct("products");
    }

            
    public function get_all_rooms(){
    return $this->query("SELECT * FROM rooms", []);
    }

 public function get_products($search, $offset, $limit, $order = "-id", $category =""){

    switch (substr($order, 0, 1)){
        case '-': $order_direction = 'ASC'; break;
        case '+': $order_direction = 'DESC'; break;
        default: throw new Exception("Invalid order format"); break;
    };

    $params = [];
    $params["search"] = $search;
    
    $query = "SELECT   p.id,
                       p.name, 
                       ps.name AS 'category',
                       p.gender_category,                     
                       p.unit_price,
                       p.image_link,
                       p.subcategory_id,
                       p.created_at
               FROM products p
               JOIN product_subcategory ps ON p.subcategory_id = ps.id  
               WHERE LOWER(p.name) LIKE CONCAT('%', :search, '%')";

    if ($category != ""){
        $query .= " AND LOWER(ps.name) LIKE CONCAT('%', :category, '%')";
        $params["category"] = strtolower($category);
    }
        
        $order = substr($order, 1);
        
        if( strtolower($order) == "category"){
            $order = "ps.name";
        }else{
            $order = "p.".$order;
        }

        $query .= "ORDER BY ${order} ${order_direction}
                   LIMIT ${limit} OFFSET ${offset}";

        return $this->query($query,$params);
 }

 public function get_avaliable_products_count($search=""){
    $params = [];
    $params["search"] = $search;

        $avaliable_products = "SELECT DISTINCT ps.product_id
                                    FROM products p 
                                    JOIN product_stock ps ON p.id = ps.product_id
                                    WHERE ps.quantity_avaliable > 0";

        $query = "SELECT COUNT(p.id) AS avaliable_products
                  FROM products p
                  JOIN product_subcategory ps ON p.subcategory_id = ps.id  
                  WHERE p.id IN ({$avaliable_products})
                  AND LOWER(p.name) LIKE CONCAT('%', :search, '%')";
        
        return $this->query_unique($query,$params);
 }

    public function get_avaliable_products($search, $offset, $limit, $order = "-id", $category =""){

        switch (substr($order, 0, 1)){
            case '-': $order_direction = 'ASC'; break;
            case '+': $order_direction = 'DESC'; break;
            default: throw new Exception("Invalid order format"); break;
        };

        $params = [];
        $params["search"] = $search;
        
        $avaliable_products = "SELECT DISTINCT ps.product_id
                                FROM products p 
                                JOIN product_stock ps ON p.id = ps.product_id
                                WHERE ps.quantity_avaliable > 0";

        $query = "SELECT p.id,
                        p.name, 
                        ps.name AS 'category',
                        p.gender_category,                     
                        p.unit_price,
                        p.image_link
                FROM products p
                JOIN product_subcategory ps ON p.subcategory_id = ps.id  
                WHERE p.id IN ( {$avaliable_products} )
                AND LOWER(p.name) LIKE CONCAT('%', :search, '%')";

        if ($category != ""){
            $query .= " AND LOWER(ps.name) LIKE CONCAT('%', :category, '%')";
            $params["category"] = strtolower($category);
        }
            
            $order = substr($order, 1);
            
            if( strtolower($order) == "category"){
                $order = "ps.name";
            }else{
                $order = "p.".$order;
            }

            $query .= "ORDER BY ${order} ${order_direction}
                    LIMIT ${limit} OFFSET ${offset}";

            return $this->query($query,$params);
    }

        public function get_avaliable_sizes($product_id){
            $query =  "SELECT ps.product_id, ps.size_id, s.name, ps.quantity_avaliable
                       FROM products p 
                       JOIN product_stock ps ON p.id = ps.product_id
                       JOIN sizes s ON s.id = ps.size_id
                       WHERE p.id = :product_id
                       AND ps.quantity_avaliable > 0";

            $params = [];
            $params["product_id"] = $product_id;

            return $this->query($query,$params);
        }
        
        public function get_avaliable_product_by_id($product_id){
            
            $avaliable_products = "SELECT DISTINCT ps.product_id
                                FROM products p 
                                JOIN product_stock ps ON p.id = ps.product_id
                                WHERE ps.quantity_avaliable > 0";

            $query =  "SELECT p.*
                       FROM products p
                       JOIN product_subcategory ps ON p.subcategory_id = ps.id  
                       WHERE p.id IN ( {$avaliable_products} )
                       AND p.id = :product_id
                            ";

            $params = [];
            $params["product_id"] = $product_id;

            return $this->query_unique($query,$params);
        }




}
?>