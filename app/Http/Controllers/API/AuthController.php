<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ResponseService;

class AuthController extends Controller
{
    /* 
    * Response service object
    */
    var $response;
    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    public function login(Request $request)
    {
        $login = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (auth()->attempt($login)) {
            $token = auth()->user()->createToken('SoccerAPP')->accessToken;
            return $this->response->onSuccess(['token' => $token->token]);
        } else {
            return $this->response->onAuthorizationError();
        }
    }
}
