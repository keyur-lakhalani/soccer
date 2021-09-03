<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Models\Team;
use App\Services\TeamService;

class TeamController extends Controller
{
    /**
    * Display a listing of the team.
    *use App\Services\TeamService;

    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $teams = Team::all();
        
    }

    /**
    * Add team 
    *
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $validator = TeamService::validateData($request);
        
        if ($validator->fails()) {          
            return ResponseService::onError($validator->errors());
        }
        
        //store the record in database
        $teams = TeamService::add($request);

        if(!empty($teams)){
            return ResponseService::onSuccess(['message' => 'Team Created', 'id' => $teams->id]);
        }
        return ResponseService::onError(['message' => 'Something went wrong']);
    }    

}
