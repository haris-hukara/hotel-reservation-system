<?php
/**
 * @OA\Get(path="/admin/products", tags={"products"},security={{"ApiKeyAuth":{}}},
 *                    @OA\Parameter( type="integer", in="query",name="offset", default=0, description= "Offset for paggination"),           
*                     @OA\Parameter( type="integer", in="query",name="limit", default=10, description= "Limit for paggination"),
*                     @OA\Parameter( type="integer", in="query",name="search", default="Adidas", description= "Case insensitive search for product name"),
*                     @OA\Parameter( type="integer", in="query",name="category", default="Hoodie", description= "Case insensitive search for product category"),
*                     @OA\Parameter( type="string", in="query",name="order", default="-id", description= "Sorting elements by column_name <br><br>  -column_name for ascending order <br>+column_name for descending order"),
 *     @OA\Response(response="200", description="List of all products from database with paggination")
 * )
 */
Flight::route('GET /admin/products', function(){  
    $offset = Flight::query('offset', 0);
    $limit = Flight::query('limit', 10);
    $search = Flight::query('search');
    $category = Flight::query('category');
    $order = Flight::query('order', "-id");    

    flight::json(Flight::productsService()->get_products($search, $offset, $limit, $order, $category));
});

/**
 * @OA\Get(path="/products", tags={"products"},
 *                    @OA\Parameter( type="integer", in="query",name="offset", default=0, description= "Offset for paggination"),           
*                     @OA\Parameter( type="integer", in="query",name="limit", default=10, description= "Limit for paggination"),
*                     @OA\Parameter( type="integer", in="query",name="search", default="Adidas", description= "Case insensitive search for product name"),
*                     @OA\Parameter( type="integer", in="query",name="category", default="Hoodie", description= "Case insensitive search for product category"),
*                     @OA\Parameter( type="string", in="query",name="order", default="-id", description= "Sorting elements by column_name <br><br>  -column_name for ascending order <br>+column_name for descending order"),
 *     @OA\Response(response="200", description="List of all products from database with paggination")
 * )
 */
Flight::route('GET /products', function(){  
    $offset = Flight::query('offset', 0);
    $limit = Flight::query('limit', 10);
    $search = Flight::query('search');
    $category = Flight::query('category');
    $order = Flight::query('order', "-id");    

    flight::json(Flight::productsService()->get_avaliable_products($search, $offset, $limit, $order, $category));
});

/**
 * @OA\Get(path="/rooms", tags={"rooms"},
 *     @OA\Response(response="200", description="List of all rooms")
 * )
 */
Flight::route('GET /rooms', function(){  
    flight::json(Flight::productsService()->get_all_rooms());
});

/**
 * @OA\Get(path="/products_count", tags={"products"},
*                     @OA\Parameter( type="integer", in="query",name="search", default="Adidas", description= "Case insensitive search for product name"),
 *     @OA\Response(response="200", description="Returns count of all avaliable products ")
 * )
 */
Flight::route('GET /products_count', function(){  
    $search = Flight::query('search');
    flight::json(Flight::productsService()->get_avaliable_products_count($search));
});

/**
 * @OA\Get(path="/product/{id}", tags={"products"},
*  @OA\Parameter(@OA\Schema(type="integer"), in="path", name="id", example = "1", description="Id of avaliable product"),
 *     @OA\Response(response="200", description="Returns product info of avaliable product")
 * )
 */
Flight::route('GET /product/@id', function($id){  
        flight::json(Flight::productsService()->get_avaliable_product_by_id($id));
});


 /**
* @OA\Put(path="/admin/products/{id}",tags={"products","admin"},security={{"ApiKeyAuth":{}}},
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
Flight::route('PUT /admin/products/@id', function($id){  
    $data = Flight::request()->data->getdata();
    flight::json(Flight::productsService()->update($id, $data));
});

/**
 * @OA\Get(path="/admin/products/{id}", tags={"products","admin"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="id", default=1, description="Id of product"),
 *     @OA\Response(response="200", description="Fetch individual product by id")
 * )
 */
Flight::route('GET /admin/products/@id', function($id){
    Flight::json(Flight::productsService()->get_by_id($id));  
});

/**
 * @OA\Get(path="/product/avaliable_sizes/{id}", tags={"products"},
 *     @OA\Parameter(type="integer", in="path", name="id", default=1, description="Id of product"),
 *     @OA\Response(response="200", description="Fetch avaliable product sizes and quantity their quantity in stock")
 * )
 */
Flight::route('GET /product/avaliable_sizes/@id', function($id){
    Flight::json(Flight::productsService()->get_avaliable_sizes($id));  
});

/**
*  @OA\Post(path="/admin/products",tags={"products","admin"}, security={{"ApiKeyAuth": {}}},
*  @OA\RequestBody(description ="Body for product", required = true,
*          @OA\MediaType(mediaType="application/json",
*                 @OA\Schema(
*                     @OA\Property(property="name", 
*                                      type="string",
*                                      example="Adidas Hoodie",
*                                      description="Product name"),           
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
*                                      description="Sub category of product"),           
*            ) 
*        )
*   ),
*  @OA\Response(response="200", description="Register account")
* )     
*/ 
Flight::route('POST /admin/products', function(){
    $data = Flight::request()->data->getdata();
    Flight::json(Flight::productsService()->add_product($data));
});


?>