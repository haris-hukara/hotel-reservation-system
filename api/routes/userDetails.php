<?php 
/**
*  @OA\Post(path="/details/add",tags={"User Details"},
*  @OA\RequestBody(description ="Body for adding order details", required = true,
*          @OA\MediaType(mediaType="application/json",
*                 @OA\Schema(
*                     @OA\Property(property="first_name", type="string",example="name",description="first name"),           
*                     @OA\Property(property="last_name", type="string",example="surname",description="last name"),           
*                     @OA\Property(property="email", type="string",example="haris.hukara@stu.ibu.edu.ba",description="email"),           
*                     @OA\Property(property="phone_number", type="string",example="000 000 000",description="phone number"), 
*                     @OA\Property(property="country", type="string",example="Bosnia nad Herzegovina",description="Country name"),
*                     @OA\Property(property="city", type="string",example="city",description="City name"),           
*                     @OA\Property(property="birth_date", type="string",example="1999-02-16",description="yyyy-mm-dd")           
*            ) 
*        )
*   ),
*  @OA\Response(response="200", description="Added order details")
* )     
*/ 
Flight::route('POST /details/add', function(){
    $data = Flight::request()->data->getdata();
    Flight::json(Flight::userDetailsService()->add($data));
});

/**
 * @OA\Get(path="/user/details", tags={"User Details","admin"},security={{"ApiKeyAuth":{}}},
  *    @OA\Parameter(type="integer", in="query", name="offset", default=0, description="Offset for pagination"),
 *     @OA\Parameter(type="integer", in="query", name="limit", default=25, description="Limit for pagination"),
 *     @OA\Parameter(type="string", in="query", name="search", description="Search string for accounts. Case insensitive search."),
 *     @OA\Parameter(type="string", in="query", name="order", default="-id", description="Sorting for return elements. -column_name ascending order by column_name or +column_name descending order by column_name"),
 *     @OA\Response(response="200", description="List user details from database")
 * )
 */
Flight::route('GET /user/details', function(){  
    $offset = Flight::query('offset', 0);
    $limit = Flight::query('limit', 10);
    $search = Flight::query('search');
    $order = Flight::query('order', "-id");    

    flight::json(Flight::userDetailsService()->get_user_details($search, $offset, $limit, $order));
});


/**
 * @OA\Get(path="/user/details/{id}", tags={"User Details","admin"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="id", default=1, description="Id of user details"),
 *     @OA\Response(response="200", description="Fetch individual user details")
 * )
 */

Flight::route('GET /user/details/@id', function($id){
   Flight::json(Flight::userDetailsService()->get_user_details_by_account_id_and_details_id(Flight::get('user'), $id));
});


/**
* @OA\Put(path="/user/details/{id}",tags={"User Details"},security={{"ApiKeyAuth": {}}},
* @OA\Parameter(@OA\Schema(type="integer"), in="path", name="id", example = "1", description="Update account by account_id"),
**@OA\RequestBody(description ="Basic account info that is going to be updated", required = true,
*          @OA\MediaType(mediaType="application/json",
*                 @OA\Schema(
*                     @OA\Property(property="first_name", type="string",example="name",description="first name"),           
*                     @OA\Property(property="last_name", type="string",example="surname",description="last name"),           
*                     @OA\Property(property="email", type="string",example="haris.hukara@stu.ibu.edu.ba",description="email"),           
*                     @OA\Property(property="phone_number", type="string",example="000 000 000",description="phone number"), 
*                     @OA\Property(property="country", type="string",example="Bosnia nad Herzegovina",description="Country name"),
*                     @OA\Property(property="city", type="string",example="city",description="City name"),           
*                     @OA\Property(property="birth_date", type="string",example="1999-02-16",description="yyyy-mm-dd")           
*            ) 
*        )
*   ), 
* @OA\Response(response="200", description="Update account message")
* )     
*/ 
Flight::route('PUT /user/details/@id', function($id){
    $data = Flight::request()->data->getdata();
    flight::json(Flight::userDetailsService()->update_user_details(Flight::get('user'), $id, $data));
});

?>