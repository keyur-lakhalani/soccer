<?php

namespace App\Services;
use Illuminate\Http\Request;
use File, Image;

Class UploadImageService {

    public static function storeTeamLogo(Request $request){
        if ($request->file('logo')->isValid())
        {
            $returnObj = (object)[];
            $image = $request->file('logo');
            
            $returnObj->name = time().'.'.$image->extension();
            $returnObj->path = env('TEAM_LOGO_PATH');
            
            File::ensureDirectoryExists($returnObj->path);
            
            $img = Image::make($image->path());
            $img->resize(100, 100, function ($const) {
                $const->aspectRatio();
            })->save($returnObj->path.'/'.$returnObj->name);
            
            if(File::exists($returnObj->path)) {
                return $returnObj;
            }    
            return false;
        }
        return false;
    }

    public static function removeTeamLogo($logoName){
        $logo = env('TEAM_LOGO_PATH').'/'.$logoName;
        return self::removeFile($logo);
    }

    public static function removeFile($path)
    {
        if(File::exists($path)) {
            File::delete($path);
            return true;
        }    
        return false;
    }

    public static function storePlayerLogo(Request $request){
        if ($request->file('image')->isValid())
        {
            $returnObj = (object)[];
            $image = $request->file('image');
            
            $returnObj->name = time().'.'.$image->extension();
            $returnObj->path = env('TEAM_PLAYER_IMAGE_PATH');
            
            File::ensureDirectoryExists($returnObj->path);
            
            $img = Image::make($image->path());
            $img->resize(150, 150, function ($const) {
                $const->aspectRatio();
            })->save($returnObj->path.'/'.$returnObj->name);
            
            if(File::exists($returnObj->path)) {
                return $returnObj;
            }    
            return false;
        }
        return false;
    }

    public static function removeTeamPlayerImage($imageName){
        $imagePath = env('TEAM_PLAYER_IMAGE_PATH').'/'.$imageName;
        return self::removeFile($imagePath);
    }
}