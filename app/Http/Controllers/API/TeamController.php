<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Models\Team;

class AuthController extends Controller
{
    /* 
    * Response service object
    */
    var $response;

    var $team;
    public function __construct(ResponseService $response, Team $team)
    {
        $this->response = $response;
        $this->team = $team;
    }
    
    
}
