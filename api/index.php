<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once dirname(__FILE__).'/../vendor/autoload.php';

/* include service classes */
require_once dirname(__FILE__).'/services/ReservationDetailsService.class.php';
require_once dirname(__FILE__).'/services/ReservationsService.class.php';
require_once dirname(__FILE__).'/services/PaymentMethodService.class.php';
require_once dirname(__FILE__).'/services/RoomsService.class.php';
require_once dirname(__FILE__).'/services/UserAccountService.class.php';
require_once dirname(__FILE__).'/services/UserDetailsService.class.php';

// log errors into apache log on bitnami server
// Flight::set('flight.log:errors',TRUE);

/*   error handling for API 
Flight::map('error', function(Exception $ex){
    Flight::json(['message' => $ex->getMessage()] , $ex->getCode());
}); 
*/
Flight::route('GET /', function(){  
    Flight::redirect('/docs');
});


/* utility function for reading params from URL */
Flight::map('query', function($name, $default_value = NULL){
    $request = Flight::request();
    $query_param = @$request->query->getData()[$name];
    $query_param = $query_param ? $query_param : $default_value;
    return $query_param;
});
/*  Delete later
    trying out things  middleware didn't work  

    Flight::map('middleware', function(){
    if(Flight::request()->url == '/swagger') return TRUE;

    $headers = getallheaders();
    $token = @$headers['Authentication'];
    
    try {
        $decoded = \Firebase\JWT\JWT::decode($token, "JWT SECRET",['HS256']);
        Flight::set("decoded", $decoded);
        return TRUE; 
    } catch (\Exception $e) {
        Flight::json(["message" => $e->getMessage()], 401);
        die;
    }
});
*/ 
Flight::map('header', function($name){
    $headers = getallheaders();
    return @$headers[$name];
  });

  /* utility function for generating JWT Token */
  Flight::map('jwt', function($user){
    $jwt = Firebase\JWT\JWT::encode( 
        [ "exp"=>(time() + Config::JWT_TOKEN_TIME()), 
          "id"=> $user["id"], 
          "rl"=> $user["role"]
        ], Config::JWT_SECRET(),'HS256');

    return ["token" => $jwt];
  });


/* register Bussiness Logic layer services */
Flight::register('reservationDetailsService', 'ReservationDetailsService');
Flight::register('reservationsService', 'ReservationsService');
Flight::register('paymentMethodService', 'PaymentMethodService');
Flight::register('roomsService', 'RoomsService');
Flight::register('userAccountService', 'UserAccountService');
Flight::register('userDetailsService', 'UserDetailsService');


/* include routes */
require_once dirname(__FILE__).'/routes/middleware.php';
require_once dirname(__FILE__).'/routes/reservations.php';
require_once dirname(__FILE__).'/routes/reservationDetails.php';
require_once dirname(__FILE__).'/routes/paymentMethod.php';
require_once dirname(__FILE__).'/routes/rooms.php';
require_once dirname(__FILE__).'/routes/userAccount.php';
require_once dirname(__FILE__).'/routes/userDetails.php';

/* get swagger route */
require_once dirname(__FILE__).'/routes/doc.php';


Flight::start();
?>