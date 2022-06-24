<?php
/**
 * @OA\Get(path="/admin/orders", tags={"order","admin"},security={{"ApiKeyAuth":{}}},
  *    @OA\Parameter(type="integer", in="query", name="offset", default=0, description="Offset for pagination"),
 *     @OA\Parameter(type="integer", in="query", name="limit", default=25, description="Limit for pagination"),
 *     @OA\Parameter(type="string", in="query", name="search", description="Address search string for orders. Case insensitive search."),
 *     @OA\Parameter(type="string", in="query", name="order", default="-id", description="Sorting for return elements. -column_name ascending order by column_name or +column_name descending order by column_name"),
 *     @OA\Response(response="200", description="List accounts from database")
 * )
 */
Flight::route('GET /admin/orders', function(){  
    $offset = Flight::query('offset', 0);
    $limit = Flight::query('limit', 10);
    $search = Flight::query('search');
    $order = Flight::query('order', "-id");    

    flight::json(Flight::orderService()->get_orders($search, $offset, $limit, $order));
});

/**
 * @OA\Get(path="/admin/orders/{id}", tags={"order","admin"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="id", default=1, description="Id of account"),
 *     @OA\Response(response="200", description="Fetch individual account")
 * )
 */
Flight::route('GET /admin/orders/@id', function($id){
    Flight::json(Flight::orderService()->get_by_id($id));  
});

/**
 * @OA\Get(path="/api/resrvations/{user_id}", tags={"resrevations"},
 *     @OA\Parameter(type="integer", in="path", name="user_id", default=1, description="Id of user account"),
 *     @OA\Response(response="200", description="Fetch all reservations")
 * )
 */
Flight::route('GET /api/resrvations/@user_id', function($user_id){
    Flight::json(Flight::orderService()->get_resrvation_by_user_id($user_id));
});

/**
 * @OA\Get(path="/user/orders", tags={"order"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Response(response="200", description="Fetch all orders by account that is logged in")
 * )
 */
Flight::route('GET /user/orders', function(){
    Flight::json(Flight::orderService()->get_all_orders_for_user(Flight::get('user')));  
});

/**
*  @OA\Post(path="/order",tags={"order"},
*  @OA\RequestBody(description ="Body for order", required = true,
*          @OA\MediaType(mediaType="application/json",
*                 @OA\Schema(
*                     @OA\Property(property="user_details_id", 
*                                      type="int",
*                                      example="38",
*                                      description="ID of user details"),           
*                     @OA\Property(property="shipping_address", 
*                                      type="string",
*                                      example="Adresa 123",
*                                      description="Shipping address"),           
*                     @OA\Property(property="payment_method_id", 
*                                      type="int",
*                                      example="1",
*                                      description="Payment method"),                     
*                     @OA\Property(property="status", 
*                                      type="string",
*                                      example="ACTIVE",
*                                      description="status"),           
*
*            ) 
*        )
*   ),
*  @OA\Response(response="200", description="Register account")
* )     
*/ 
Flight::route('POST /order', function(){
    $data = Flight::request()->data->getdata();
    Flight::json(Flight::orderService()->add_order($data));
});


 /**
* @OA\Put(path="/admin/order/{id}",tags={"order","admin"},security={{"ApiKeyAuth":{}}},
* @OA\Parameter(@OA\Schema(type="integer"), in="path", name="id", example = "1", description="Update country by id"),
**@OA\RequestBody(description ="Basic account info that is going to be updated", required = true,
*          @OA\MediaType(mediaType="application/json",
*                 @OA\Schema(
*                     @OA\Property(property="shipping_address", type="string",example="Shipping address",description="Shipping addres"),           
*                     @OA\Property(property="payment_method_id", type="integer",example=1,description="Payment method"),           
*                     @OA\Property(property="status", type="string",example="DELIVERED",description="Order status"),           
*            ) 
*        )
*   ), 
* @OA\Response(response="200", description="Update account message")
* )     
*/ 
Flight::route('PUT /admin/order/@id', function($id){  
    $data = Flight::request()->data->getdata();
    flight::json(Flight::orderService()->update($id, $data));
});

?>