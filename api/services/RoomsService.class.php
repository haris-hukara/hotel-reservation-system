<?php 
require_once dirname(__FILE__)."/BaseService.class.php";
require_once dirname(__FILE__)."/../dao/RoomsDao.class.php";

class RoomsService extends BaseService{

    
   public function __construct(){ 
     $this->dao = new RoomsDao();   
   }

    public function get_rooms($search, $offset, $limit, $order){
      if($search){
        return ($this->dao->get_rooms($search, $offset, $limit, $order));
      }else{
        return ($this->dao->get_rooms($search ="", $offset, $limit, $order));
      }
    }

    public function get_avaliable_rooms_count($search){
      if ($search){
      return $this->dao->get_avaliable_rooms_count($search);
      }
      return $this->dao->get_avaliable_rooms_count("");
    }

    public function get_avaliable_rooms($search, $offset, $limit, $order, $check_in, $check_out){
      if ($search){
        return ($this->dao->get_avaliable_rooms($search, $offset, $limit, $order , $check_in, $check_out));
      }else{
        return ($this->dao->get_avaliable_rooms($search ="", $offset, $limit, $order , $check_in, $check_out));
      }
    }

    public function get_avaliable_room_by_id($id){
      return $this->dao->get_avaliable_room_by_id($id);
    }

    public function update_room($id, $data){
      
      if(!isset($data['name'])) throw new Exception("Name is missing");
      
      $room = parent::update($id,
      ["name" => ucwords(strtolower($data['name'])),
       "unit_price" => $data['unit_price'],
       "image_link" => $data['image_link'],
       "gender_category" => $data['gender_category'],
       "subcategory_id" => $data['subcategory_id']]
      ); 
        return $room;
    }

    public function add_room($details){
      if(!isset($details['name'])) throw new Exception("room name is missing");
      
      $room = parent::add([
        "name" => $details['name'],
        "unit_price" => $details['unit_price'],
        "image_link" => $details['image_link'],
        "gender_category" => $details['gender_category'],
        "subcategory_id" => $details['subcategory_id'],
        "created_at" => date(Config::DATE_FORMAT)
      ]); 
    
      return $room;
      }

      public function get_avaliable_sizes($id){
       return $this->dao->get_avaliable_sizes($id);
      }

      public function get_all_rooms(){
        return $this->dao->get_all_rooms();
      }

      
  }
?>




  