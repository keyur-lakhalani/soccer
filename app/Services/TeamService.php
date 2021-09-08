<?php

namespace App\Services;
use Illuminate\Http\Request;
use Validator, Exception;
use App\models\Team;
use Illuminate\Support\Facades\DB;

Class TeamService {
    /*
    * Success json response
    * $data = array()
    * return @json
    */
    public static function validateData(Request $request, $teamID = ''){
        $validator = Validator::make($request->all(), 
            [ 
                'name' => 'required|max:64|regex:/^[a-zA-Z0-9\s]+$/|unique:team,name,'.$teamID,
                'logo' => 'required|mimes:jpg,jpeg,png|max:2048',
            ],
            [
                'name.required' => 'Team Name is required',
                'name.unique' => 'Name is already exist',
                'logo.required' => 'Team logo is required',
                'logo.max' => 'Team logo file size should not be more than 2MB',
                'logo.mimes' => 'Team logo must be in JPG,JPEG or PNG format'  
            ]
        );   
 
        return $validator;
    }
    
    public static function getTeamByID($id)
    {
        return Team::find($id);
    }

    public static function add(Request $request){
        $validator = self::validateData($request);
        
        if ($validator->fails()) {          
            return ResponseService::onError($validator->errors());
        }
        
        //store the record in database
        $team = new Team();
        $logoObj = UploadImageService::storeTeamLogo($request);
        if(isset($logoObj->name) && !empty($logoObj->name)){
            try{
                $team->logo = $logoObj->name;
                $team->name = $request->name;
                $team->save();   
            }catch(Exception $e){
                return ResponseService::renderException($e);
            }
        }

        if(isset($team->id)){
            return ResponseService::onSuccess(['message' => 'Team Created', 'id' => $team->id]);
        }
        return ResponseService::onError(['message' => 'Something went wrong']);
    }
    
    public static function update(Request $request, $id){
        $team = self::getTeamByID($id);
        if(!$team){
            return ResponseService::onNotFoundError();
        }

        $validator = self::validateData($request, $id);
        
        if ($validator->fails()) {          
            return ResponseService::onError($validator->errors());
        }
                
        $logoObj = UploadImageService::storeTeamLogo($request);
        if(isset($logoObj->name) && !empty($logoObj->name)){
            //remove old logo file
            $old_logo = $team->logo;
            try{
                $team->logo = $logoObj->name;
                $team->name = $request->name;
                $team->save();
            }catch(Exception $e){
                return ResponseService::renderException($e);
            }
            UploadImageService::removeTeamLogo($old_logo);
        }
        if(isset($team->id)){
            return ResponseService::onSuccess(['message' => 'Team updated']);
        }
        return ResponseService::onError(['message' => 'Something went wrong']);
    }

    public static function deleteTeam($id)
    {
        $team = self::getTeamByID($id);
        if(!$team){
            return ResponseService::onNotFoundError();
        }
        $logo = $team->logo;
        try{
            //delete all the player images from file store
            foreach($team->player()->get() as $p){
                UploadImageService::removeTeamPlayerImage($p->image_name);
            }
            $team->player()->delete();
            $team->delete();
        }catch(Exception $e){
            return ResponseService::renderException($e);
        }
        UploadImageService::removeTeamLogo($logo);
        return ResponseService::onSuccess(['message' => 'Team deleted']);
    }
    
    public static function getTeamList($per_page = 50)
    {
        $logo_host = env('TEAM_LOGO_URL', '/');
        try{
            return Team::select(['id AS identifier', 'name', DB::raw('CONCAT("'.$logo_host.'/",  logo) AS logo')])
                 ->orderBy('name')
                 ->paginate($per_page);
        }catch(Exception $e){
            return ResponseService::renderException($e);
        }
    }
}