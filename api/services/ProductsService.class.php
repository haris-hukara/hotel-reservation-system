<?php 
require_once dirname(__FILE__)."/BaseService.class.php";
require_once dirname(__FILE__)."/../dao/ProductsDao.class.php";

class ProductsService extends BaseService{

    
   public function __construct(){ 
     $this->dao = new ProductsDao();   
   }

    public function get_products($search, $offset, $limit, $order ,$category){
      if ($search && $category ){
        return ($this->dao->get_products($search, $offset, $limit, $order ,$category));
      }if($search){
        return ($this->dao->get_products($search, $offset, $limit, $order ,$category =""));
      }if($category){
        return ($this->dao->get_products($search ="", $offset, $limit, $order ,$category));
      }else{
        return ($this->dao->get_products($search ="", $offset, $limit, $order ,$category=""));
      }
    }

    public function get_avaliable_products_count($search){
      if ($search){
      return $this->dao->get_avaliable_products_count($search);
      }
      return $this->dao->get_avaliable_products_count("");
    }


      public function get_avaliable_products($search, $offset, $limit, $order ,$category){
      if ($search && $category ){
        return ($this->dao->get_avaliable_products($search, $offset, $limit, $order ,$category));
      }if($search){
        return ($this->dao->get_avaliable_products($search, $offset, $limit, $order ,$category =""));
      }if($category){
        return ($this->dao->get_avaliable_products($search ="", $offset, $limit, $order ,$category));
      }else{
        return ($this->dao->get_avaliable_products($search ="", $offset, $limit, $order ,$category=""));
      }
    }

    public function get_avaliable_product_by_id($id){
      return $this->dao->get_avaliable_product_by_id($id);
    }

    public function update_product($id, $data){
      
      if(!isset($data['name'])) throw new Exception("Name is missing");
      
      $product = parent::update($id,
      ["name" => ucwords(strtolower($data['name'])),
       "unit_price" => $data['unit_price'],
       "image_link" => $data['image_link'],
       "gender_category" => $data['gender_category'],
       "subcategory_id" => $data['subcategory_id']]
      ); 
        return $product;
    }

    public function add_product($details){
      if(!isset($details['name'])) throw new Exception("Product name is missing");
      
      $product = parent::add([
        "name" => $details['name'],
        "unit_price" => $details['unit_price'],
        "image_link" => $details['image_link'],
        "gender_category" => $details['gender_category'],
        "subcategory_id" => $details['subcategory_id'],
        "created_at" => date(Config::DATE_FORMAT)
      ]); 
    
      return $product;
      }

      public function get_avaliable_sizes($id){
       return $this->dao->get_avaliable_sizes($id);
      }

      public function get_all_rooms(){
        return $this->dao->get_all_rooms();
      }

      
  }
?>




  