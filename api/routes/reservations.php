<?php
/**
 * @OA\Get(path="/user/{user_id}/reservations", tags={"reservations"},security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="user_id", default=1, description="Id of user account"),
 *     @OA\Parameter(type="integer", in="query", name="offset", default=0, description="Offset for pagination"),
 *     @OA\Parameter(type="integer", in="query", name="limit", default=25, description="Limit for pagination"),
 *     @OA\Parameter(type="string", in="query", name="order", default="-id", description="Sorting for return elements. -column_name ascending order by column_name or +column_name descending order by column_name"),
 *     @OA\Response(response="200", description="Fetch all reservations for user")
 * )
 */
Flight::route('GET /user/@user_id/reservations', function($user_id){
    $offset = Flight::query('offset', 0);
    $limit = Flight::query('limit', 10);
    $order = Flight::query('order', "+created_at"); 
    Flight::json(Flight::reservationsService()->get_all_user_reservations($user_id, Flight::get('user'),$offset,$limit, $order));
});


/**
 * @OA\Get(path="/admin/reservations", tags={"reservations","admin"},security={{"ApiKeyAuth":{}}},
  *    @OA\Parameter(type="integer", in="query", name="offset", default=0, description="Offset for pagination"),
 *     @OA\Parameter(type="integer", in="query", name="limit", default=25, description="Limit for pagination"),
 *     @OA\Parameter(type="string", in="query", name="search", description="reservation status"),
 *     @OA\Parameter(type="string", in="query", name="order", default="-id", description="Sorting for return elements. -column_name ascending order by column_name or +column_name descending order by column_name"),
 *     @OA\Response(response="200", description="List accounts from database")
 * )
 */
Flight::route('GET /admin/reservations', function(){  
    $offset = Flight::query('offset', 0);
    $limit = Flight::query('limit', 100000);
    $search = Flight::query('search');
    $order = Flight::query('order', "-created_at");    
    flight::json(Flight::reservationsService()->get_reservations($search, $offset, $limit, $order));
});

/**
 * @OA\Get(path="/admin/reservations/{id}", tags={"reservations","admin"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="id", default=1, description="Id of resrvation"),
 *     @OA\Response(response="200", description="Fetch individual account")
 * )
 */
Flight::route('GET /admin/reservations/@id', function($id){
    Flight::json(Flight::reservationsService()->get_by_id($id));  
});


/**
*  @OA\Post(path="/reservation",tags={"reservations"},
*  @OA\RequestBody(description ="Body for reservation", required = true,
*          @OA\MediaType(mediaType="application/json",
*                 @OA\Schema(
*                     @OA\Property(property="user_details_id", 
*                                      type="int",
*                                      example="1",
*                                      description="ID of user details"),           
*                     @OA\Property(property="payment_method_id", 
*                                      type="int",
*                                      example="1",
*                                      description="Payment method"),          
*            ) 
*        )
*   ),
*  @OA\Response(response="200", description="Register account")
* )     
*/ 
Flight::route('POST /reservation', function(){
    $data = Flight::request()->data->getdata();
    Flight::json(Flight::reservationsService()->add_reservation($data));
});


 /**
* @OA\Put(path="/admin/reservation/{id}",tags={"reservations","admin"},security={{"ApiKeyAuth":{}}},
* @OA\Parameter(@OA\Schema(type="integer"), in="path", name="id", example = "1", description="Update reservation by its id"),
**@OA\RequestBody(description ="Basic resrvation info that is going to be updated", required = true,
*          @OA\MediaType(mediaType="application/json",
*                 @OA\Schema(
*               @OA\Property(property="payment_method_id", 
*                                type="integer",
*                              example=1,
*                          description="Payment method"),           
*                @OA\Property(property="payment_method_id", 
*                                      type="int",
*                                      example="1",
*                                      description="Payment method"),  
*                     @OA\Property(property="status", 
*                                      type="string",
*                                   example="ACCEPTED",
*                               description="Order status")       
*           ) 
*        )
*   ), 
* @OA\Response(response="200", description="Update account message")
* )     
*/ 
Flight::route('PUT /admin/reservation/@id', function($id){  
    $data = Flight::request()->data->getdata();
    flight::json(Flight::reservationsService()->update_reservation($id, $data));
});


/**
 * @OA\Put(path="/admin/reservations/{reservation_id}/change_status", tags={"reservations","admin"},security={{"ApiKeyAuth":{}}},
 *     @OA\Parameter(type="integer", required=true, in="path", name="reservation_id", default=1, description="Reservation ID"),
 **@OA\RequestBody(description ="Basic reservation status info that is going to be updated", required = true,
*          @OA\MediaType(mediaType="application/json",
*                 @OA\Schema(
*               @OA\Property(property="status", 
*                                type="text",
*                              example="ACCEPTED",
*                          description="Status to set")      
*           ) 
*        )
*   ), 
 *     @OA\Response(response="200", description="Reservation details")
 * )
 */
Flight::route('PUT /admin/reservations/@reservation_id/change_status', function($reservation_id){
    $data = Flight::request()->data->getdata();
    Flight::json(Flight::reservationsService()->change_reservation_status(Flight::get('user'),$reservation_id,  $data["status"]));  
});


?>