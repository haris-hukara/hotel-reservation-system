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

    public function get_avaliable_rooms_count($search, $check_in, $check_out){
      $today = date('Y-m-d');
      if(!$check_in || !$check_out) {
        $check_in = $today;
        $check_out = date('Y-m-d', strtotime($today. ' + 7 days'));
      }
      if(!parent::date_format_check($check_in)) throw new Exception("Check-in date format is not valid");
      if(!parent::date_format_check($check_out)) throw new Exception("Check-out date format is not valid");
      if( $check_out < $check_in ) throw new Exception("Check-out date can't be lower than check-in date");
  
      if ($search){
      return $this->dao->get_avaliable_rooms_count($search, $check_in, $check_out);
      }
      return $this->dao->get_avaliable_rooms_count("", $check_in, $check_out);
    }

    public function get_avaliable_rooms($search, $offset, $limit, $order, $check_in, $check_out){
      $today = date('Y-m-d');
      if(!$check_in || !$check_out) {
        $check_in = $today;
        $check_out = date('Y-m-d', strtotime($today. ' + 7 days'));
      }
      if(!parent::date_format_check($check_in)) throw new Exception("Check-in date format is not valid");
      if(!parent::date_format_check($check_out)) throw new Exception("Check-out date format is not valid");
      if( $check_out < $check_in ) throw new Exception("Check-out date can't be lower than check-in date");
      
      if ($search){
        return ($this->dao->get_avaliable_rooms($search, $offset, $limit, $order , $check_in, $check_out));
      }else{
        return ($this->dao->get_avaliable_rooms("" , $offset, $limit, $order , $check_in, $check_out));
      }
    }

    public function get_room_by_id($id){
      return $this->dao->get_room_by_id($id);
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
      if(!isset($details['name'])) throw new Exception("Room name is missing");
      if(!isset($details['description'])) throw new Exception("Description is missing");
      if(!isset($details['night_price'])) throw new Exception("Night price is missing");
      if(!isset($details['image_link'])) throw new Exception("Image link is missing");
      
      $room = parent::add([
        "name" => $details['name'],
        "description" => $details['description'],
        "night_price" => $details['night_price'],
        "image_link" => $details['image_link']
      ]); 
    
      return $room;
      }

      public function get_avaliable_sizes($id){
       return $this->dao->get_avaliable_sizes($id);
      }

      public function get_all_rooms(){
        return $this->dao->get_all_rooms();
      }

      public function delete_room_by_id($id){
        $delete_count = $this->delete_by_id($id);
        if ($delete_count > 0){
         return ["message"=>"Successfully deleted ".$delete_count." rooms"];
        }else{
          throw new Exception("Room with id ".$id. " doesn't exist", 404);
        }
      }
  }
?>




  