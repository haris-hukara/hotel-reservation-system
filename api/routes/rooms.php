<?php
/**
 * @OA\Get(path="/admin/rooms", tags={"rooms"},security={{"ApiKeyAuth":{}}},
 *                    @OA\Parameter( type="integer", in="query",name="offset", default=0, description= "Offset for paggination"),           
*                     @OA\Parameter( type="integer", in="query",name="limit", default=10, description= "Limit for paggination"),
*                     @OA\Parameter( type="integer", in="query",name="search", default="Standard Room", description= "Case insensitive search for room name"),
*                     @OA\Parameter( type="string", in="query",name="order", default="-id", description= "Sorting elements by column_name <br><br>  -column_name for ascending order <br>+column_name for descending order"),
 *     @OA\Response(response="200", description="List of all rooms from database with paggination")
 * )
 */
Flight::route('GET /admin/rooms', function(){  
    $offset = Flight::query('offset', 0);
    $limit = Flight::query('limit', 10);
    $search = Flight::query('search');
    $order = Flight::query('order', "-id");    

    flight::json(Flight::roomsService()->get_rooms($search, $offset, $limit, $order));
});
/**
 * @OA\Get(path="/rooms", tags={"rooms"},
  *                   @OA\Parameter( type="integer", in="query",name="offset", default=0, description= "Offset for paggination"),           
*                     @OA\Parameter( type="integer", in="query",name="limit", default=10, description= "Limit for paggination"),
*                     @OA\Parameter( type="integer", in="query",name="search", default="Standard Room", description= "Case insensitive search for room name"),
*                     @OA\Parameter( type="string", in="query",name="order", default="-id", description= "Sorting elements by column_name <br><br>  -column_name for ascending order <br>+column_name for descending order"),
*                     @OA\Parameter( type="string", in="query",name="check_in", default="2022-01-01", description= "Check-in date"),
*                     @OA\Parameter( type="string", in="query",name="check_out", default="2022-01-31", description= "Check-out date"),
*     @OA\Response(response="200", description="List of all avaliable rooms between two dates from database with paggination")
* )
*/
Flight::route('GET /rooms', function(){  
    $offset = Flight::query('offset', 0);
    $limit = Flight::query('limit', 10);
    $search = Flight::query('search');
    $check_in = Flight::query('check_in');
    $check_out = Flight::query('check_out');
    $order = Flight::query('order');    
    
    flight::json(Flight::roomsService()->get_avaliable_rooms($search, $offset, $limit, $order, $check_in, $check_out));
});



/**
 * @OA\Get(path="/avaliable_rooms_count", tags={"rooms"},
 *                     @OA\Parameter( type="integer", in="query",name="search", default="Standard Room", description= "Case insensitive search for room name"),
 *                     @OA\Parameter( type="string", in="query",name="check_in",  description= "date format YYYY-MM-DD"),
 *                     @OA\Parameter( type="string", in="query",name="check_out",  description= "date format YYYY-MM-DD"),
 *     @OA\Response(response="200", description="Returns count of all avaliable rooms ")
 * )
 */
Flight::route('GET /avaliable_rooms_count', function(){  
    $search = Flight::query('search');
    $check_in = Flight::query('check_in');
    $check_out = Flight::query('check_out');
    flight::json(Flight::roomsService()->get_avaliable_rooms_count($search, $check_in, $check_out));
});

/**
 * @OA\Get(path="/room/{id}", tags={"rooms"},
*  @OA\Parameter(@OA\Schema(type="integer"), in="path", name="id", example = "1", description="Id of avaliable room"),
 *     @OA\Response(response="200", description="Returns room info of avaliable room")
 * )
 */
Flight::route('GET /room/@id', function($id){  
    Flight::json(Flight::roomsService()->get_by_id($id));
});


 /**
* @OA\Put(path="/admin/rooms/{id}",tags={"rooms","admin"},security={{"ApiKeyAuth":{}}},
* @OA\Parameter(@OA\Schema(type="integer"), in="path", name="id", example = "1", description="Update city by city id"),
**@OA\RequestBody(description ="Basic account info that is going to be updated", required = true,
*          @OA\MediaType(mediaType="application/json",
*                @OA\Schema(
*                     @OA\Property(property="name", 
*                                      type="string",
*                                      example="Standard Room",
*                                      description="room name"),           
*                     @OA\Property(property="description", 
*                                      type="string",
*                                      example="Lorem ipsum dolor sit, amet consectetur adipisicing elit. Id hic repudiandae ut, fugit magni esse minima maiores doloribus possimus nam.",
*                                      description="Room description"),           
*                     @OA\Property(property="night_price", 
*                                      type="integer",
*                                      example=100,
*                                      description="Night price"),           
*                     @OA\Property(property="image_link", 
*                                      type="string",
*                                      example="assets/img/room-1.png",
*                                      description="Image link")       
*            ) 
*        )
*   ), 
* @OA\Response(response="200", description="Update account message")
* )     
*/ 
Flight::route('PUT /admin/rooms/@id', function($id){  
    $data = Flight::request()->data->getdata();
    flight::json(Flight::roomsService()->update($id, $data));
});


/**
*  @OA\Post(path="/admin/rooms",tags={"rooms","admin"}, security={{"ApiKeyAuth": {}}},
*  @OA\RequestBody(description ="Body for room", required = true,
*          @OA\MediaType(mediaType="application/json",
*                 @OA\Schema(
*                     @OA\Property(property="name", 
*                                      type="string",
*                                      example="Standard Room",
*                                      description="room name"),           
*                     @OA\Property(property="description", 
*                                      type="string",
*                                      example="Lorem ipsum dolor sit, amet consectetur adipisicing elit. Id hic repudiandae ut, fugit magni esse minima maiores doloribus possimus nam.",
*                                      description="Room description"),           
*                     @OA\Property(property="night_price", 
*                                      type="integer",
*                                      example=100,
*                                      description="Night price"),           
*                     @OA\Property(property="image_link", 
*                                      type="string",
*                                      example="assets/img/room-1.png",
*                                      description="Image link")       
*            ) 
*        )
*   ),
*  @OA\Response(response="200", description="Register account")
* )     
*/ 
Flight::route('POST /admin/rooms', function(){
    $data = Flight::request()->data->getdata();
    Flight::json(Flight::roomsService()->add_room($data));
});


/**
* @OA\Delete(path="/admin/rooms/{id}",tags={"rooms","admin"},security={{"ApiKeyAuth":{}}},
* @OA\Parameter(@OA\Schema(type="integer"), in="path", name="id", example = "1", description="Delete room by id"),
* @OA\Response(response="200", description="Room deleted message")
* )     
*/ 
Flight::route('DELETE /admin/rooms/@id', function($id){  
    $data = Flight::request()->data->getdata();
    flight::json(Flight::roomsService()->delete_room_by_id($id));
});
?>