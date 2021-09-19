<?php

namespace App\Services;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;

Class TeamPlayerService {
    /*
    * Success json response
    * $data = array()
    * return @json
    */
    public function validateData(Request $request, $playerID = ''){
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

    public function uploadTeamPlayerImage (Request $request)
    {
        if ($request->file('image')->isValid())
        {
            $returnObj = (object)[];
            $image = $request->file('image');
            
            $returnObj->name = time().'.'.$image->extension();
            $returnObj->path = env('TEAM_PLAYER_IMAGE_PATH');
            
            $isImageUpload = UploadImageService::storeFile($image->path(), $returnObj->path, $returnObj->name, 150, 150);
            
            if($isImageUpload){
                return $returnObj;
            }
        }
        return false;
    }
    
    public function removeTeamPlayerImage($imageName){
        $imagePath = env('TEAM_PLAYER_IMAGE_PATH').'/'.$imageName;
        return UploadImageService::removeFile($imagePath);
    }
}