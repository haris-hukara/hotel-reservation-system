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
 *                    @OA\Parameter( type="integer", in="query",name="offset", default=0, description= "Offset for paggination"),           
*                     @OA\Parameter( type="integer", in="query",name="limit", default=10, description= "Limit for paggination"),
*                     @OA\Parameter( type="integer", in="query",name="search", default="Adidas", description= "Case insensitive search for room name"),
*                     @OA\Parameter( type="integer", in="query",name="category", default="Hoodie", description= "Case insensitive search for room category"),
*                     @OA\Parameter( type="string", in="query",name="order", default="-id", description= "Sorting elements by column_name <br><br>  -column_name for ascending order <br>+column_name for descending order"),
 *     @OA\Response(response="200", description="List of all rooms from database with paggination")
 * )
 */
Flight::route('GET /rooms', function(){  
    $offset = Flight::query('offset', 0);
    $limit = Flight::query('limit', 10);
    $search = Flight::query('search');
    $category = Flight::query('category');
    $order = Flight::query('order', "-id");    

    flight::json(Flight::roomsService()->get_avaliable_rooms($search, $offset, $limit, $order, $category));
});

/**
 * @OA\Get(path="/rooms", tags={"rooms"},
 *     @OA\Response(response="200", description="List of all rooms")
 * )
 */
Flight::route('GET /rooms', function(){  
    flight::json(Flight::roomsService()->get_all_rooms());
});

/**
 * @OA\Get(path="/rooms_count", tags={"rooms"},
*                     @OA\Parameter( type="integer", in="query",name="search", default="Adidas", description= "Case insensitive search for room name"),
 *     @OA\Response(response="200", description="Returns count of all avaliable rooms ")
 * )
 */
Flight::route('GET /rooms_count', function(){  
    $search = Flight::query('search');
    flight::json(Flight::roomsService()->get_avaliable_rooms_count($search));
});

/**
 * @OA\Get(path="/room/{id}", tags={"rooms"},
*  @OA\Parameter(@OA\Schema(type="integer"), in="path", name="id", example = "1", description="Id of avaliable room"),
 *     @OA\Response(response="200", description="Returns room info of avaliable room")
 * )
 */
Flight::route('GET /room/@id', function($id){  
        flight::json(Flight::roomsService()->get_avaliable_room_by_id($id));
});


 /**
* @OA\Put(path="/admin/rooms/{id}",tags={"rooms","admin"},security={{"ApiKeyAuth":{}}},
* @OA\Parameter(@OA\Schema(type="integer"), in="path", name="id", example = "1", description="Update city by city id"),
**@OA\RequestBody(description ="Basic account info that is going to be updated", required = true,
*          @OA\MediaType(mediaType="application/json",
*                 @OA\Schema(
*                     @OA\Property(property="name", type="string",example="Adidas Hoodie",description="123"),           
*                     @OA\Property(property="unit_price", type="integer",example=10, description="123"),           
*                     @OA\Property(property="image_link", type="string",example="link.com" , description="123"),           
*                     @OA\Property(property="gender_category", type="string",example="M",description="123"),           
*                     @OA\Property(property="subcategory_id", type="integer",example=1, description="123"),           
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
 * @OA\Get(path="/admin/rooms/{id}", tags={"rooms","admin"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="id", default=1, description="Id of room"),
 *     @OA\Response(response="200", description="Fetch individual room by id")
 * )
 */
Flight::route('GET /admin/rooms/@id', function($id){
    Flight::json(Flight::roomsService()->get_by_id($id));  
});

/**
 * @OA\Get(path="/room/avaliable_sizes/{id}", tags={"rooms"},
 *     @OA\Parameter(type="integer", in="path", name="id", default=1, description="Id of room"),
 *     @OA\Response(response="200", description="Fetch avaliable room sizes and quantity their quantity in stock")
 * )
 */
Flight::route('GET /room/avaliable_sizes/@id', function($id){
    Flight::json(Flight::roomsService()->get_avaliable_sizes($id));  
});

/**
*  @OA\Post(path="/admin/rooms",tags={"rooms","admin"}, security={{"ApiKeyAuth": {}}},
*  @OA\RequestBody(description ="Body for room", required = true,
*          @OA\MediaType(mediaType="application/json",
*                 @OA\Schema(
*                     @OA\Property(property="name", 
*                                      type="string",
*                                      example="Adidas Hoodie",
*                                      description="room name"),           
*                     @OA\Property(property="unit_price", 
*                                      type="integer",
*                                      example=10,
*                                      description="Unit price"),           
*                     @OA\Property(property="image_link", 
*                                      type="string",
*                                      example="link.com",
*                                      description="Image link"),                     
*                     @OA\Property(property="gender_category", 
*                                      type="string",
*                                      example="M",
*                                      description="Gender category"),           
*                     @OA\Property(property="subcategory_id", 
*                                      type="integer",
*                                      example= 1,
*                                      description="Sub category of room"),           
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


?>