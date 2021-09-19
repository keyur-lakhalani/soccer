<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Services\TeamPlayerService;
use App\Repository\TeamPlayerRepositoryInterface;

use Exception;

class TeamPlayerController extends Controller
{
    private $teamPlayerRepository;

    private $teamPlayerService;
  
    public function __construct(TeamPlayerRepositoryInterface $teamPlayerRepository, TeamPlayerService $teamPlayerService)
    {
        $this->teamPlayerRepository = $teamPlayerRepository;
        $this->teamPlayerService = $teamPlayerService;
    }

    /**
    * Display a listing of the player.
    * use App\Services\TeamPlayerService;

    * @return \Illuminate\Http\Response
    */
    public function index($idOrName)
    {
        try{
            $teamsPlayer = $this->teamPlayerRepository->getTeamPlayerListByTeamID($idOrName);
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
            $teams = $this->teamPlayerRepository->getTeamPlayerByIDOrName($idOrName);
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
        return $this->_createOrUpdate($request, '');
    }
    
    /**
    * Update team 
    *
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        return $this->_createOrUpdate($request, $id);
    }

    private function _createOrUpdate(Request $request, $id)
    {
        $successMessage = 'Player is added to team';
        if(!empty($id)){
            $teamPlayersObj = $this->teamPlayerRepository->findByID($id);
            if(!$teamPlayersObj){
                return ResponseService::onNotFoundError();
            }
            $old_image = $teamPlayersObj->image_name;
            $successMessage = 'Team Player is updated';
        }
                
        $validator = $this->teamPlayerService->validateData($request, $id);
        
        if ($validator->fails()) {          
            return ResponseService::onError($validator->errors());
        }

        $logoObj = $this->teamPlayerService->uploadTeamPlayerImage($request);

        if(isset($logoObj->name) && !empty($logoObj->name)){
            $teamPlayer['team_id'] = $request->team_id;
            $teamPlayer['first_name'] = $request->first_name;
            $teamPlayer['last_name'] = $request->last_name;
            $teamPlayer['image_name'] = $logoObj->name;
            $teamPlayers = $this->teamPlayerRepository->createorUpdate($teamPlayer, $id);
            if(isset($teamPlayers->id)){
                if(!empty($id) && !empty($old_image)){
                    $this->teamPlayerService->removeTeamPlayerImage($old_image);
                }
                return ResponseService::onSuccess(['message' => $successMessage, 'id' => $teamPlayers->id]);
            }
        }
        return ResponseService::onError(['message' => 'Something went wrong']);
    }

    /**
    * delete team 
    *
    * @return \Illuminate\Http\Response
    */
    public function delete($id)
    {
        $teamPlayer = $this->teamPlayerRepository->findByID($id);
        if(!$teamPlayer){
            return ResponseService::onNotFoundError();
        }
        $image_name = $teamPlayer->image_name;
                        
        $deleteID = $this->teamPlayerRepository->delete($id);

        if($deleteID == $id){
            $this->teamPlayerService->removeTeamPlayerImage($image_name);
            return ResponseService::onSuccess(['message' => 'Team Player is deleted']);
        }
        return ResponseService::onError(['message' => 'Something went wrong']);
    }

}
