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
 * @OA\Get(path="/admin/reservation/{reservation_id}/details", tags={"Reservation Details", "admin"},security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="reservation_id", default=1, description="Reservation ID"),
 *     @OA\Response(response="200", description="Reservation details")
 * )
 */
Flight::route('GET /admin/reservation/@reservation_id/details', function($reservation_id){
    Flight::json(Flight::reservationDetailsService()->get_reservation_details_by_id(Flight::get('user'), $reservation_id));  
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


?>