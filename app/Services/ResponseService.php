<?php

namespace App\Services;

Class ResponseService {
    /*
    * $data = array()
    * return @json
    */
    public function onSuccess($data = array()){
        return response()->json([
            "success" => true,
            "data" => $data 
        ], 200);
    } 
    
    /*
    * 
    * return @json
    */
    public function onAuthorizationError(){
        return response()->json([
            "error" => true,
            "data" => "Unauthorize request" 
        ], 401);
    }
}