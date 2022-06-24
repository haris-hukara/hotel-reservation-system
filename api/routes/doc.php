<?php 
Flight::route('GET /swagger', function(){  
    $openapi = @\OpenApi\scan(dirname(__FILE__)."/../routes");
    header('Content-Type: application/json');
    echo $openapi->toJson();
});
?>