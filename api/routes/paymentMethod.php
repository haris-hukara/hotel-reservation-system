<?php
/**
 * @OA\Get(path="/admin/payment_method", tags={"Payment Method","admin"},security={{"ApiKeyAuth":{}}},
 *                    @OA\Parameter( type="integer", in="query",name="offset", default=0, description= "Offset for paggination"),           
*                     @OA\Parameter( type="integer", in="query",name="limit", default=10, description= "Limit for paggination"),
*                     @OA\Parameter( type="integer", in="query",name="search", default="Sarajevo", description= "Case insensitive search for cities"),
*                     @OA\Parameter( type="string", in="query",name="order", default="-id", description= "Sorting elements by column_name <br><br>  -column_name for ascending order <br>+column_name for descending order"),
 *     @OA\Response(response="200", description="List of all cities from database with paggination")
 * )
 */
Flight::route('GET /admin/payment_method', function(){  
    $offset = Flight::query('offset', 0);
    $limit = Flight::query('limit', 10);
    $search = Flight::query('search');
    $order = Flight::query('order', "-id");    

    flight::json(Flight::paymentMethodService()->get_payment_method($search, $offset, $limit, $order));
});


/**
 * @OA\Get(path="/admin/payment_method/{id}", tags={"Payment Method","admin"}, security={{"ApiKeyAuth":{}}},
 *     @OA\Parameter(type="integer", in="path", name="id", default=1, description="Payment method id"),
 *     @OA\Response(response="200", description="Payment method info")
 * )
 */
Flight::route('GET /admin/payment_method/@id', function($id){  
    flight::json(Flight::paymentMethodService()->get_by_id($id));
});

 /**
* @OA\Put(path="/admin/payment_method/{id}",tags={"Payment Method","admin"},security={{"ApiKeyAuth":{}}},
* @OA\Parameter(@OA\Schema(type="integer"), in="path", name="id", example = "1", description="Update country by id"),
**@OA\RequestBody(description ="Basic account info that is going to be updated", required = true,
*          @OA\MediaType(mediaType="application/json",
*                 @OA\Schema(
*                     @OA\Property(property="name", type="string",example="Payment method name",description="123"),           
*            ) 
*        )
*   ), 
* @OA\Response(response="200", description="Update payment method")
* )     
*/ 
Flight::route('PUT /admin/payment_method/@id', function($id){  
    $data = Flight::request()->data->getdata();
    flight::json(Flight::paymentMethodService()->update_payment_method($id, $data));
});



/**
*  @OA\Post(path="/admin/payment_method",tags={"Payment Method","admin"},security={{"ApiKeyAuth":{}}},
*  @OA\RequestBody(description ="Body for order", required = true,
*          @OA\MediaType(mediaType="application/json",
*                 @OA\Schema(
*                     @OA\Property(property="name", 
*                                      type="string",
*                                      example="Payment method",
*                                      description="Input for adding payment method name. First letter of each word in payment method name is converted to uppercase, other letters are lowercase"), 
*
*            ) 
*        )
*   ),
*    @OA\Response(response="200", description="Adding city to a country if that country exists in database")
* )     
*/ 
Flight::route('POST /admin/payment_method', function(){
    $data = Flight::request()->data->getdata();
    Flight::json(Flight::paymentMethodService()->add_payment_method($data));
});
?>