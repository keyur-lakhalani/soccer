<?php

namespace App\Services;
use Illuminate\Http\Request;
use File, Image;

Class UploadImageService {

    public static function storeFile($uploadPath, $savePath, $imageName, $width = 100, $height = 100){
        File::ensureDirectoryExists($savePath);
        
        $saveToDir = $savePath.'/'.$imageName;
        $img = Image::make($uploadPath);
        $img->resize($width, $height, function ($const) {
            $const->aspectRatio();
        })->save($saveToDir);
        
        if(File::exists($saveToDir)) {
            return true;
        }    
        return false;
    }
    public static function removeFile($path)
    {
        if(empty($path)) return false;
        if(File::exists($path)) {
            File::delete($path);
            return true;
        }    
        return false;
    }
}