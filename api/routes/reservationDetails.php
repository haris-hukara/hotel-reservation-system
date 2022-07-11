<?php
/**
 * @OA\Get(path="/user/{user_id}/reservation/{reservation_id}/details", tags={"Reservation Details"},security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="user_id", default=1, description="User ID"),
 *     @OA\Parameter(type="integer", in="path", name="reservation_id", default=1, description="Reservation ID"),
 *     @OA\Response(response="200", description="Detailed info about order")
 * )
 */
Flight::route('GET /user/@user_id/reservation/@reservation_id/details', function($user_id, $reservation_id){
    Flight::json(Flight::reservationDetailsService()->get_reservation_details_by_id(Flight::get('user'),$user_id, $reservation_id));  
});

/**
 * @OA\Get(path="/user/{user_id}/reservation/{reservation_id}/price", tags={"Reservation Details"},security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="user_id", default=1, description="User ID"),
 *     @OA\Parameter(type="integer", in="path", name="reservation_id", default=1, description="Reservation ID"),
 *     @OA\Response(response="200", description="Detailed info about order")
 * )
 */
Flight::route('GET /user/@user_id/reservation/@reservation_id/price', function($user_id, $reservation_id){
    Flight::json(Flight::reservationDetailsService()->get_reservation_price_by_account_id_and_reservation_id(Flight::get('user'),$user_id, $reservation_id));  
});

/**
*  @OA\Post(path="/reservation/details",tags={"Order Details"},
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
* @OA\Put(path="/order/details/quantity",tags={"Order Details"},
**@OA\RequestBody(description ="Body for updating order details quantity", required = true,
*          @OA\MediaType(mediaType="application/json",
*                 @OA\Schema(
*                     @OA\Property(property="order_id", 
*                                      type="integer",
*                                      example=1,
*                                      description="ID of order"),           
*                     @OA\Property(property="product_id", 
*                                      type="integer",
*                                      example=1,
*                                      description="Product ID"),           
*                     @OA\Property(property="size_id", 
*                                      type="integer",
*                                      example=1,
*                                      description="Size ID"),                     
*                     @OA\Property(property="quantity", 
*                                      type="integer",
*                                      example=1,
*                                      description="Quantity"),  ) 
*        )
*   ), 
* @OA\Response(response="200", description="Update account message")
* )     
*/ 
Flight::route('PUT /order/details/quantity', function(){  
    $data = Flight::request()->data->getdata();
    flight::json(Flight::reservationDetailsService()->update_order_details_quantity($data));
});
?>