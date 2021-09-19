<?php

namespace App\Repository\Model;
use Exception;
use App\models\Team;
use App\Repository\TeamRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TeamRepository implements TeamRepositoryInterface
{
   /**
    * @return Team List
    */
    public function findByID(int $id)
    {
        return Team::find($id);
    }

   /**
    * @return Team List
    */
   public function getTeamList($per_page = 25)
   {
        $logo_host = env('TEAM_LOGO_URL', '/');
        try{
            return Team::select(['id AS identifier', 'name', DB::raw('CONCAT("'.$logo_host.'/",  logo) AS logo')])
                ->orderBy('name')
                ->paginate($per_page);
        }catch(Exception $e){
            return $e;
        }
   }

    /**
     * Add Team
    * @return Team 
    */
    public function createorUpdate(array $teamArray, $id = '')
    {
        if(!empty($id)){
            $team = $this->findByID($id);
        }else{
            $team = new Team();
        }

        if(!$team){
            return false;
        }
        
        try{
            $team->logo = $teamArray['logo'];
            $team->name = $teamArray['name'];
            $team->save(); 
            return $team;   
        }catch(Exception $e){
            return $e;
        }
    }

    public function delete($id)
    {
        $teams = $this->findByID($id);
        if(!$teams){
            return false;
        }
        try{
            $teams->player()->delete();
            $teams->delete();
            return $id;
        }catch(Exception $e){
            return $e;
        }
        return false;
    }
    
}