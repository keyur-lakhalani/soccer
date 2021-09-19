<?php

namespace App\Repository\Model;
use Exception;
use App\models\TeamPlayer;
use App\Repository\TeamPlayerRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TeamPlayerRepository implements TeamPlayerRepositoryInterface
{
   /**
    * @return Team Payer List
    */
    public function findByID(int $id)
    {
        return TeamPlayer::find($id);
    }

      /**
     * Add or Update Team Player
    * @return Team Player
    */
    public function createorUpdate(array $teamPlayerArray, $id = '')
    {
        if(!empty($id)){
            $teamPlayer = $this->findByID($id);
        }else{
            $teamPlayer = new TeamPlayer();
        }

        if(!$teamPlayer){
            return false;
        }
        
        try{
            $teamPlayer->team_id = $teamPlayerArray['team_id'];
            $teamPlayer->first_name = $teamPlayerArray['first_name']; 
            $teamPlayer->last_name = $teamPlayerArray['last_name'];
            $teamPlayer->image_name = $teamPlayerArray['image_name'];
            $teamPlayer->save();   
            return $teamPlayer;   
        }catch(Exception $e){
            return $e;
        }
    }

    public function delete($id)
    {
        $teamPlayer = $this->findByID($id);
        if(!$teamPlayer){
            return false;
        }
        try{
            $teamPlayer->delete();
            return $id;
        }catch(Exception $e){
            return $e;
        }
        return false;
    }

    public function getTeamPlayerByIDOrName($idOrName)
    {
        $image_host = env('TEAM_PLAYER_IMAGE_URL', '/');
        try{
            return TeamPlayer::select(['team_player.id AS identifier', 'first_name', 'last_name', 
                            DB::raw('CONCAT("'.$image_host.'/",  image_name) AS logo'), 't.name as team_name'
                            ])
                    ->join('team as t', 't.id', '=', 'team_player.team_id')
                   ->where('team_player.id', $idOrName)
                   ->orWhere(DB::raw("CONCAT(first_name,' ',last_name)"), '=', $idOrName)
                   ->get();
        }catch(Exception $e){
            return $e;
        }
    }

    public function getTeamPlayerListByTeamID($idOrName, $per_page = 50)
    {
        $image_host = env('TEAM_PLAYER_IMAGE_URL', '/');
        try{
            return TeamPlayer::select(['team_player.id AS identifier', 'first_name', 'last_name', DB::raw('CONCAT("'.$image_host.'/",  image_name) AS logo')])
                    ->whereHas('team', function ($query) use($idOrName ){
                        $query->where('team.id', '=', $idOrName)
                                ->orWhere('team.name', '=', $idOrName)  ;
                    })
                    ->orderBy('first_name')
                    ->paginate($per_page);       
        }catch(Exception $e){
            return $e;
        }
    }
}