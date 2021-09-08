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
            "results" => $data 
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
            "message" => $data 
        ], 400);
    }
    
    /*
    * Unauthorize request
    * return @json
    */
    public static function onAuthorizationError(){
        return response()->json([
            "error" => true,
            "message" => "Unauthorize request" 
        ], 401);
    }

    /*
    * 404 not found
    * return @json
    */
    public static function onNotFoundError(){
        return response()->json([
            "error" => true,
            "message" => "Page Not Found. If error persists, contact site admin" 
        ], 404);
    }

    public static function renderException($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }

        $response = [];
        $response['error'] = true;
        switch ($statusCode) {
            case 401:
                $response['message'] = 'The request is Unauthorized. Please check the valid credentail.';
                break;
            case 403:
                $response['message'] = 'The request is Forbidden.';
                break;
            case 404:
                $response['message'] = 'Page Not Found. If error persists, contact site admin';
                break;
            case 405:
                $response['message'] = 'Method Not Allowed';
                break;
            case 422:
                $response['message'] = $exception->original['message'];
                $response['errors'] = $exception->original['errors'];
                break;
            default:
                $response['message'] = ($statusCode == 500) ? 'Oops, looks like something went wrong' : $exception->getMessage();
                break;
        }
        //if (config('app.debug')) {
            $response['trace'] = $exception->getTrace();
            $response['code'] = $exception->getCode();
        //}
        
        return response()->json($response, $statusCode);
    }
}