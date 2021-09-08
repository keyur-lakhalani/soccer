<?php

namespace App\Services;
use Illuminate\Http\Request;
use Validator, Exception;
use App\models\TeamPlayer;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

Class TeamPlayerService {
    /*
    * Success json response
    * $data = array()
    * return @json
    */
    public static function validateData(Request $request, $playerID = ''){
        $validator = Validator::make($request->all(), 
            [ 
                'team_id' => 'required|regex:/^[0-9]+$/|exists:team,id',
                'first_name' => ['required','max:64','regex:/^[a-zA-Z0-9\s]+$/',
                                    Rule::unique('team_player')->where(function ($query) use ($request, $playerID) {
                                        $query
                                            ->where('first_name', $request->first_name)
                                            ->where('last_name', $request->last_name);
                                        if($playerID){
                                            $query->whereNotIn('id', [$playerID]);
                                        }    
                                        return $query;    
                                    }),
                                ],
                'last_name' => ['required','max:64','regex:/^[a-zA-Z0-9\s]+$/'],
                'image' => 'required|mimes:jpg,jpeg,png|max:2048',
            ],
            [
                'team_id.required' => 'Team Identifier is required',
                'first_name.required' => 'First Name is required',
                'last_name.required' => 'First Name is required',
                'first_name.unique' => 'Player Name is already exist',
                'image.required' => 'Player Image is required',
                'image.max' => 'Player Image size should not be more than 2MB',
                'image.mimes' => 'Player Image must be in JPG,JPEG or PNG format'  
            ]
        );   
        return $validator;
    }
    
    public static function getTeamPlayerByID($id)
    {
        return TeamPlayer::find($id);
    }

    public static function getTeamPlayerByIDOrName($idOrName)
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
            return ResponseService::renderException($e);
        }
    }

    public static function add(Request $request){
        $validator = self::validateData($request);
        
        if ($validator->fails()) {          
            return ResponseService::onError($validator->errors());
        }
        
        //store the record in database
        $teamPlayer = new TeamPlayer();
        $imgObj = UploadImageService::storePlayerLogo($request);
        if(isset($imgObj->name) && !empty($imgObj->name)){
            try{
                $teamPlayer->team_id = $request->team_id;
                $teamPlayer->first_name = $request->first_name; 
                $teamPlayer->last_name = $request->last_name;
                $teamPlayer->image_name = $imgObj->name;
                $teamPlayer->save();   
            }catch(Exception $e){
                return ResponseService::renderException($e);
            }
        }

        if(isset($teamPlayer->id)){
            return ResponseService::onSuccess(['message' => 'Player is added to team', 'id' => $teamPlayer->id]);
        }
        return ResponseService::onError(['message' => 'Something went wrong']);
    }

    public static function update(Request $request, $id){
        $teamPlayer = self::getTeamPlayerByID($id);
        if(!$teamPlayer){
            return ResponseService::onNotFoundError();
        }

        $validator = self::validateData($request, $id);
        
        if ($validator->fails()) {          
            return ResponseService::onError($validator->errors());
        }
                
        $imgObj = UploadImageService::storePlayerLogo($request);
        if(isset($imgObj->name) && !empty($imgObj->name)){
            //remove old logo file
            $old_image = $teamPlayer->image_name;
            try{
                $teamPlayer->team_id = $request->team_id;
                $teamPlayer->first_name = $request->first_name; 
                $teamPlayer->last_name = $request->last_name;
                $teamPlayer->image_name = $imgObj->name;
                $teamPlayer->save();   
            }catch(Exception $e){
                return ResponseService::renderException($e);
            }
            UploadImageService::removeTeamPlayerImage($old_image);
        }
        if(isset($teamPlayer->id)){
            return ResponseService::onSuccess(['message' => 'Team Player is updated']);
        }
        return ResponseService::onError(['message' => 'Something went wrong']);
    }

    public static function deleteTeamPlayer($id)
    {
        $teamPlayer = self::getTeamPlayerByID($id);
        if(!$teamPlayer){
            return ResponseService::onNotFoundError();
        }
        $image = $teamPlayer->image_name;
        try{
            $teamPlayer->delete();
        }catch(Exception $e){
            return ResponseService::renderException($e);
        }
        UploadImageService::removeTeamPlayerImage($image);
        return ResponseService::onSuccess(['message' => 'Team Player is deleted']);
    }

    public static function getTeamPlayerListByTeamID($idOrName, $per_page = 50)
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
            return ResponseService::renderException($e);
        }
    }
}