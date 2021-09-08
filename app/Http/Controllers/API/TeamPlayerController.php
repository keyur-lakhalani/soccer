<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Services\TeamPlayerService;
use Exception;

class TeamPlayerController extends Controller
{
    /**
    * Display a listing of the player.
    * use App\Services\TeamPlayerService;

    * @return \Illuminate\Http\Response
    */
    public function index($idOrName)
    {
        try{
            $teamsPlayer = TeamPlayerService::getTeamPlayerListByTeamID($idOrName);
            return ResponseService::onSuccess($teamsPlayer);    
        }catch(Exception $e){
            return ResponseService::renderException($e);
        }
    }

    /**
    * Display a listing of the player.
    * use App\Services\TeamPlayerService;

    * @return \Illuminate\Http\Response
    */
    public function info($idOrName)
    {
        try{
            $teams = TeamPlayerService::getTeamPlayerByIDOrName($idOrName);
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
        return TeamPlayerService::add($request);
    }
    
    /**
    * Update team 
    *
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        return TeamPlayerService::update($request, $id);  
    }

    /**
    * delete team 
    *
    * @return \Illuminate\Http\Response
    */
    public function delete($id)
    {
        return TeamPlayerService::deleteTeamPlayer($id);
    }

}
