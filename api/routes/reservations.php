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
 * @OA\Get(path="/admin/resrvations", tags={"reservations","admin"},security={{"ApiKeyAuth":{}}},
  *    @OA\Parameter(type="integer", in="query", name="offset", default=0, description="Offset for pagination"),
 *     @OA\Parameter(type="integer", in="query", name="limit", default=25, description="Limit for pagination"),
 *     @OA\Parameter(type="string", in="query", name="search", description="reservation status"),
 *     @OA\Parameter(type="string", in="query", name="order", default="-id", description="Sorting for return elements. -column_name ascending order by column_name or +column_name descending order by column_name"),
 *     @OA\Response(response="200", description="List accounts from database")
 * )
 */
Flight::route('GET /admin/resrvations', function(){  
    $offset = Flight::query('offset', 0);
    $limit = Flight::query('limit', 10);
    $search = Flight::query('search');
    $order = Flight::query('order', "-id");    
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
*                     @OA\Property(property="check_in", 
*                                      type="string",
*                                   example="2022-01-01", 
*                               description="Check-in date"),
*                     @OA\Property(property="check_out", 
*                                      type="string",
*                                   example="2022-01-31", 
*                               description="Check-out date")         
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
* @OA\Parameter(@OA\Schema(type="integer"), in="path", name="id", example = "1", description="Update country by id"),
**@OA\RequestBody(description ="Basic account info that is going to be updated", required = true,
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
*                     @OA\Property(property="check_in", 
*                                      type="string",
*                                   example="2022-01-01", 
*                               description="Check-in date"),
*                     @OA\Property(property="check_out", 
*                                      type="string",
*                                   example="2022-01-31", 
*                               description="Check-out date"),
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

?>