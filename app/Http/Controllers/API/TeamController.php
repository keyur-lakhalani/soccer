<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Services\TeamService;
use App\Repository\TeamRepositoryInterface;
use App\Services\TeamPlayerService;
use Exception;

class TeamController extends Controller
{
    private $teamRepository;

    private $teamService;
  
    public function __construct(TeamRepositoryInterface $teamRepository, TeamService $teamService)
    {
        $this->teamRepository = $teamRepository;
        $this->teamService = $teamService;
    }
    /**
    * Display a listing of the team.
    * use App\Services\TeamService;

    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        try{
            $teams = $this->teamRepository->getTeamList();
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
        return $this->_createOrUpdae($request, '');
    }
    
    /**
    * Update team 
    *
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        return $this->_createOrUpdae($request, $id);
    }

    private function _createOrUpdae(Request $request, $id = '')
    {
        $successMessage = 'Team Created';
        if(!empty($id)){
            $teamsObj = $this->teamRepository->findByID($id);
            if(!$teamsObj){
                return ResponseService::onNotFoundError();
            }
            $old_logo = $teamsObj->logo;
            $successMessage = 'Team updated';
        }
                
        $validator = $this->teamService->validateData($request, $id);
        
        if ($validator->fails()) {          
            return ResponseService::onError($validator->errors());
        }

        $logoObj = $this->teamService->uploadTeamLogo($request);
        
        if(isset($logoObj->name) && !empty($logoObj->name)){
            $team['name'] = $request->name;
            $team['logo'] = $logoObj->name;
            $teams = $this->teamRepository->createorUpdate($team, $id);
            if(isset($teams->id)){
                if(!empty($id) && !empty($old_logo)){
                    $this->teamService->removeTeamLogo($old_logo);
                }
                return ResponseService::onSuccess(['message' => $successMessage, 'id' => $teams->id]);
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
        $teamPlayerSerview = new TeamPlayerService();
        $teams = $this->teamRepository->findByID($id);
        if(!$teams){
            return ResponseService::onNotFoundError();
        }
        $logo = $teams->logo;
        $players = $teams->player()->get();
                
        $deleteID = $this->teamRepository->delete($id);

        if($deleteID == $id){
            foreach($players as $p){
                $teamPlayerSerview->removeTeamPlayerImage($p->image_name);
            }
            $this->teamService->removeTeamLogo($logo);
            return ResponseService::onSuccess(['message' => 'Team deleted']);
        }
        return ResponseService::onError(['message' => 'Something went wrong']);
    }
}
