<?php

namespace App\Services;

Class ResponseService {
    /*
    * Success json response
    * $data = array()
    * return @json
    */
    public static function onSuccess($data = array()){
        return response()->json([
            "success" => true,
            "data" => $data 
        ], 200);
    }
    
    /*
    * Success json response
    * $data = array()
    * return @json
    */
    public static function onError($data = array()){
        return response()->json([
            "error" => true,
            "data" => $data 
        ], 400);
    }
    
    /*
    * Unauthorize request
    * return @json
    */
    public static function onAuthorizationError(){
        return response()->json([
            "error" => true,
            "data" => "Unauthorize request" 
        ], 401);
    }
}