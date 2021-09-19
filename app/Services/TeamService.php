<?php

namespace App\Services;
use Illuminate\Http\Request;
use Validator;

Class TeamService {
    /*
    * Success json response
    * $data = array()
    * return @json
    */
    public function validateData(Request $request, $teamID = ''){
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
    
    public function uploadTeamLogo (Request $request)
    {
        if ($request->file('logo')->isValid())
        {
            $returnObj = (object)[];
            $image = $request->file('logo');
            
            $returnObj->name = time().'.'.$image->extension();
            $returnObj->path = env('TEAM_LOGO_PATH');
            
            $isImageUpload = UploadImageService::storeFile($image->path(), $returnObj->path, $returnObj->name);
            
            if($isImageUpload){
                return $returnObj;
            }
        }
        return false;
    }

    public function removeTeamLogo($logoName){
        $logo = env('TEAM_LOGO_PATH').'/'.$logoName;
        return UploadImageService::removeFile($logo);
    }
}