<?php
/**
 * @OA\Get(path="/user/{user_id}/reservation/{reservation_id}/details", tags={"Reservation Details"},security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="user_id", default=1, description="User ID"),
 *     @OA\Parameter(type="integer", in="path", name="reservation_id", default=1, description="Reservation ID"),
 *     @OA\Response(response="200", description="Detailed info about order")
 * )
 */
Flight::route('GET /user/@user_id/reservation/@reservation_id/details', function($user_id, $reservation_id){
    Flight::json(Flight::reservationDetailsService()->get_reservation_details(Flight::get('user'),$user_id, $reservation_id));  
});

/**
 * @OA\Get(path="/user/{user_id}/reservation/{reservation_id}/price", tags={"Reservation Details"},security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="user_id", default=1, description="User ID"),
 *     @OA\Parameter(type="integer", in="path", name="reservation_id", default=1, description="Reservation ID"),
 *     @OA\Response(response="200", description="Detailed info about order")
 * )
 */
Flight::route('GET /user/@user_id/reservation/@reservation_id/price', function($user_id, $reservation_id){
    Flight::json(Flight::reservationDetailsService()->get_reservation_price(Flight::get('user'),$user_id, $reservation_id));  
});

/**
 * @OA\Get(path="/admin/reservation/{reservation_id}/price", tags={"Reservation Details"},security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="user_id", default=1, description="User ID"),
 *     @OA\Parameter(type="integer", in="path", name="reservation_id", default=1, description="Reservation ID"),
 *     @OA\Response(response="200", description="Detailed info about order")
 * )
 */
Flight::route('GET /admin/reservation/@reservation_id/price', function($reservation_id){
    Flight::json(Flight::reservationDetailsService()->get_reservation_total_price(Flight::get('user'), $reservation_id));  
});

/**
 * @OA\Get(path="/admin/reservation/{reservation_id}/details", tags={"Reservation Details", "admin"},security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="reservation_id", default=1, description="Reservation ID"),
 *     @OA\Response(response="200", description="Reservation details")
 * )
 */
Flight::route('GET /admin/reservation/@reservation_id/details', function($reservation_id){
    Flight::json(Flight::reservationDetailsService()->get_reservation_details_by_id(Flight::get('user'), $reservation_id));  
});

/**
 * @OA\Get(path="/admin/reservation/{reservation_id}/details/room/{room_id}", tags={"Reservation Details", "admin"},security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="reservation_id", default=1, description="Reservation ID"),
 *     @OA\Parameter(type="integer", in="path", name="room_id", default=1, description="Room id"),
 *     @OA\Response(response="200", description="Reservation details")
 * )
 */
Flight::route('GET /admin/reservation/@reservation_id/details/room/@room_id', function($reservation_id,$room_id){
    Flight::json(Flight::reservationDetailsService()->get_reservation_details_by_reservation_id_and_room_id(Flight::get('user'), $reservation_id,$room_id));  
});

/**
*  @OA\Post(path="/reservation/details",tags={"Reservation Details"},
*  @OA\RequestBody(description ="Body for adding order details", required = true,
*          @OA\MediaType(mediaType="application/json",
*                 @OA\Schema(
*                     @OA\Property(property="reservation_id", 
*                                      type="integer",
*                                      example=1,
*                                      description="ID of reservation"),           
*                     @OA\Property(property="room_id", 
*                                      type="integer",
*                                      example=1,
*                                      description="Room ID"),           
*                     @OA\Property(property="children", 
*                                      type="integer",
*                                      example=1,
*                                      description="Number of childrens"),                     
*                     @OA\Property(property="adults", 
*                                      type="integer",
*                                      example=1,
*                                      description="Number of adults"),           
*                     @OA\Property(property="check_in", 
*                                      type="string",
*                                      example="2022-01-01",
*                                      description="Check in date of reservation"),           
*                     @OA\Property(property="check_out", 
*                                      type="string",
*                                      example="2022-01-31",
*                                      description="Check out date of reservation"),           
*
*            ) 
*        )
*   ),
*  @OA\Response(response="200", description="Added order details")
* )     
*/ 
Flight::route('POST /reservation/details', function(){
    $data = Flight::request()->data->getdata();
    Flight::json(Flight::reservationDetailsService()->add_reservation_details($data));
});



/**
 * @OA\Put(path="/admin/reservation/{reservation_id}/details/room/{room_id}", tags={"Reservation Details", "admin"},security={{"ApiKeyAuth": {}}},
*  @OA\Parameter( type="string", in="path",name="reservation_id",  description= "Reservation ID", example="1"),
*  @OA\Parameter( type="string", in="path",name="room_id",  description= "Reservation room ID", example="1"),
*  **@OA\RequestBody(description ="Basic reservation details info that is going to be updated", required = true,
*          @OA\MediaType(mediaType="application/json",
*                @OA\Schema(
*                     @OA\Property( type="int", in="query",property="new_room_id",  description= "Update current Room ID to new Room ID", example="3"),
*                     @OA\Property( type="int", in="query",property="adults",  description= "Number of adults", example="3"),
*                     @OA\Property( type="int", in="query",property="children",  description= "NUmber of children", example="3"),
*                     @OA\Property( type="string", in="query",property="check_in",  description= "date format YYYY-MM-DD", example="2022-01-01"),
*                     @OA\Property( type="string", in="query",property="check_out",  description= "date format YYYY-MM-DD",  example="2022-01-31")   
*            ) 
*        )
*   ), 
*     @OA\Response(response="200", description="Returns status if changable ")
* )
*/
Flight::route('PUT /admin/reservation/@reservation_id/details/room/@room_id', function($reservation_id, $room_id){  
    $data = Flight::request()->data->getdata();
    $data["reservation_id"] = $reservation_id;
    $data["room_id"] = $room_id;
    flight::json(Flight::reservationDetailsService()-> update_reservation_details(Flight::get('user'), $data));
});
?>