<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\ResponseService;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $login = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);
        
        if (auth()->attempt($login)) {
            $token = auth()->user()->createToken('SoccerAPP')->accessToken;
            return ResponseService::onSuccess(['token' => $token]);
        } else {
            return ResponseService::onAuthorizationError();
        }
    }
}
