<?php

namespace App\Services;
use Illuminate\Http\Request;
use Validator,File;
use App\models\Team;

Class TeamService {
    /*
    * Success json response
    * $data = array()
    * return @json
    */
    public static function validateData(Request $request, $teamID = ''){
        $validator = Validator::make($request->all(), 
            [ 
            'name' => 'required|unique:team,name,'.$teamID,
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

    public static function add(Request $request){
        if ($files = $request->file('logo')) {
            $storeFileName = time().'.'.$files->getClientOriginalExtension();
            if($files->storeAs('public', $storeFileName)){
                $team = new Team();
                $team->logo = $storeFileName;
                $team->name = $request->name;
                $team->save();  
                return $team;  
            }
        }
        return null;
    }
}