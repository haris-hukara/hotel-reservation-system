<?php

/**
 * @OA\Info(title="HotelSea API", version="0.1")
 *    @OA\OpenApi(
 *      @OA\Server(url="http://localhost/hotelsea/api/", description="Developer environment")
 * ), 
 *  @OA\SecurityScheme(
 *      securityScheme="ApiKeyAuth",
 *      name="Authentication",
 *      in="header",
 *      type="apiKey",
 * )    
 */

/**
 * @OA\Get(path="/admin/accounts", tags={"user account","admin"},security={{"ApiKeyAuth":{}}},
  *    @OA\Parameter(type="integer", in="query", name="offset", default=0, description="Offset for pagination"),
 *     @OA\Parameter(type="integer", in="query", name="limit", default=25, description="Limit for pagination"),
 *     @OA\Parameter(type="string", in="query", name="search", description="Search string for accounts. Case insensitive search."),
 *     @OA\Parameter(type="string", in="query", name="order", default="-id", description="Sorting for return elements. -column_name ascending order by column_name or +column_name descending order by column_name"),
 *     @OA\Response(response="200", description="List accounts from database")
 * )
 */
Flight::route('GET /admin/accounts', function(){  
    $offset = Flight::query('offset', 0);
    $limit = Flight::query('limit', 10);
    $search = Flight::query('search');
    $order = Flight::query('order', "-id");    

    flight::json(Flight::userAccountService()->get_user_account($search, $offset, $limit, $order));
});

/**
 * @OA\Get(path="/admin/accounts/{id}", tags={"user account","admin"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="id", default=1, description="Id of account"),
 *     @OA\Response(response="200", description="Fetch individual account")
 * )
 */
Flight::route('GET /admin/accounts/@id', function($id){
         Flight::json(Flight::userAccountService()->get_by_id($id));  
});

/**
 * @OA\Get(path="/user/account/{id}", tags={"user account"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="id", default=1, description="Id of account"),
 *     @OA\Response(response="200", description="Fetch individual account")
 * )
 */
Flight::route('GET /user/account/@id', function($id){
    Flight::json(Flight::userAccountService()->getUserAccountById(Flight::get('user'),$id));  
});

/**
 * @OA\Get(path="/user/account/{id}/email", tags={"user account"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="id", default=1, description="Id of account"),
 *     @OA\Response(response="200", description="Get user email")
 * )
 */
Flight::route('GET /user/account/@id/email', function($id){
    $data = (Flight::userAccountService()->getUserAccountById(Flight::get('user'),$id));  
    Flight::json(["email" => $data['email']]);  
});

/**
 * @OA\Get(path="/user/password/account/{id}", tags={"user account"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="id", default=1, description="Id of account"),
 *     @OA\Parameter(type="string", in="query", name="password", default=1234, description="Password to be checked"),
 *     @OA\Response(response="200", description="Fetch individual account")
 * )
 */
Flight::route('GET /user/password/account/@id', function($id){
    $password = Flight::query('password');
    Flight::json(Flight::userAccountService()->checkAccountPassword(Flight::get('user'), $id, $password));  
});


/**
* @OA\Put(path="/user/account/{id}",tags={"user account"},security={{"ApiKeyAuth":{}}},
* @OA\Parameter(@OA\Schema(type="integer"), in="path", name="id", example = "1", description="Update account by account_id"),
**@OA\RequestBody(description ="Basic account info that is going to be updated", required = true,
*          @OA\MediaType(mediaType="application/json",
*                 @OA\Schema(
*                     @OA\Property(property="email", type="string",example="haris.hukara@stu.ibu.edu.ba",description="email"),           
*                     @OA\Property(property="current_password", type="string",example="password",description="current password"),           
*                     @OA\Property(property="new_password", type="string",example="password",description="new password"),           
*            ) 
*        )
*   ), 
* @OA\Response(response="200", description="Update account message")
* )     
*/ 
Flight::route('PUT /user/account/@id', function($id){
    $data = Flight::request()->data->getdata();
    flight::json(Flight::userAccountService()->update_account($id, $data, Flight::get('user')));
});


/* user account registration route*/
/**
*@OA\Post(path="/register",tags={"user account", "login"},
*@OA\RequestBody(description ="Body for user registrations", required = true,
*          @OA\MediaType(mediaType="application/json",
*                 @OA\Schema(
*                     @OA\Property(property="first_name", type="string",example="name",description="first name"),           
*                     @OA\Property(property="last_name", type="string",example="surname",description="last name"),           
*                     @OA\Property(property="email", type="string",example="haris.hukara@stu.ibu.edu.ba",description="email"),           
*                     @OA\Property(property="password", type="string",example="password",description="password"),           
*                     @OA\Property(property="phone_number", type="string",example="000 000 000",description="phone number"), 
*                     @OA\Property(property="country", type="string",example="Bosnia nad Herzegovina",description="Country name"),
*                     @OA\Property(property="city", type="string",example="city",description="City name"),           
*                     @OA\Property(property="birth_date", type="string",example="1999-02-16",description="yyyy-mm-dd")           
*            ) 
*        )
*   ),
*  @OA\Response(response="200", description="Register account")
* )     
*/ 
Flight::route('POST /register', function(){
    $data = Flight::request()->data->getdata();
    Flight::userAccountService()->register($data);
    Flight::json(["message"=>"Confirmation email has been sent. Please confirm your account !"]);
});

/**
 * @OA\Get(path="/confirm/{token}", tags={"login"},
 *     @OA\Parameter(type="string", in="path", name="token", default=123, description="Temporary token for activating account"),
 *     @OA\Response(response="200", description="Message upon successfull activation.")
 * )
 */
Flight::route('GET /confirm/@token', function($token){
    $user = Flight::userAccountService()->confirm($token);
    Flight::json(Flight::jwt($user));
});


/**
*@OA\Post(path="/login",tags={"login"},
*@OA\RequestBody(description ="Basic user login info", required = true,
*          @OA\MediaType(mediaType="application/json",
*                 @OA\Schema(
*                     @OA\Property(property="email",required = true, type="string",example="haris.hukara@stu.ibu.edu.ba",description="User's email"),           
*                     @OA\Property(property="password",required = true, type="string",example="password",description="User password")           
*            ) 
*        )
*   ),
*  @OA\Response(response="200", description="Message that user logged in")
* )     
*/ 
Flight::route('POST /login', function(){
    $data = Flight::request()->data->getdata();
    $user = Flight::userAccountService()->login($data);
    Flight::json(Flight::jwt($user));
});

/**
*@OA\Post(path="/forgot",tags={"login"}, description="Send recovery URL to user email",
*@OA\RequestBody(description ="Basic user login info", required = true,
*          @OA\MediaType(mediaType="application/json",
*                 @OA\Schema(
*                     @OA\Property(property="email",required = true, type="string",example="haris.hukara@stu.ibu.edu.ba",description="User's email")
*            ) 
*        )
*   ),
*  @OA\Response(response="200", description="Message from recovery link has been sent")
* )     
*/ 
Flight::route('POST /forgot', function(){
    $data = Flight::request()->data->getdata();
    Flight::userAccountService()->forgot($data);
    Flight::json(["message" => "Recovery link has been sent to your email"]);
});

/**
*@OA\Post(path="/reset",tags={"login"}, description="Reset user password using recovery token",
*@OA\RequestBody(description ="Basic user login info", required = true,
*          @OA\MediaType(mediaType="application/json",
*                 @OA\Schema(
*                     @OA\Property(property="token",required = true, type="string",example="token123",description="Recovery token"),
*                     @OA\Property(property="password",required = true, type="string",example="password123",description="New password")
*            ) 
*        )
*   ),
*  @OA\Response(response="200", description="Message that has been sent by recovery link")
* )     
*/ 
Flight::route('POST /reset', function(){
    $data = Flight::request()->data->getdata();
    $user = Flight::userAccountService()->reset($data);
    Flight::json(Flight::jwt($user));
});








?>