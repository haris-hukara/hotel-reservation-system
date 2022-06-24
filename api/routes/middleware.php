<?php
Flight::route('/user/*', function(){
    $token = Flight::header('Authentication');
    
    try {
        $user = (array)\Firebase\JWT\JWT::decode($token, Config::JWT_SECRET,['HS256']);
        Flight::set("user", $user);

        if(Flight::request()->method != 'GET' && $user['rl']=="USER_READ_ONLY"){
            throw new Exception ("Read only user can't change anything.", 403);
        }

        return TRUE; 
    } catch (\Exception $e) {
        Flight::json(["message" => $e->getMessage()], 401);
        die;
    }
   // Flight::middleware(); 
 });

 Flight::route('/admin/*', function(){
    $token = Flight::header('Authentication');

    try {
        $user = (array)\Firebase\JWT\JWT::decode($token, Config::JWT_SECRET,['HS256']);
        if($user['rl'] !="ADMIN"){
            throw new Exception ("Admin access required.", 403);
        }
        Flight::set("user", $user);
        return TRUE; 
    } catch (\Exception $e) {
        Flight::json(["message" => $e->getMessage()], 401);
        die;
    }
    
 });
?>