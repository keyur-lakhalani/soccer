<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Services\TeamService;
use Exception;

class TeamController extends Controller
{
    /**
    * Display a listing of the team.
    * use App\Services\TeamService;

    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        try{
            $teams = TeamService::getTeamList();
            return ResponseService::onSuccess($teams);    
        }catch(Exception $e){
            return ResponseService::renderException($e);
        }
    }

    /**
    * Add team 
    *
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        return TeamService::add($request);
    }
    
    /**
    * Update team 
    *
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        return TeamService::update($request, $id);  
    }

    /**
    * delete team 
    *
    * @return \Illuminate\Http\Response
    */
    public function delete($id)
    {
        return TeamService::deleteTeam($id);
    }

}
