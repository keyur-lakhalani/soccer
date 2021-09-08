<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TeamController;
use App\Http\Controllers\API\TeamPlayerController;
use App\Services\ResponseService;

/*header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  GET, POST, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');*/

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('team',  [TeamController::class, 'index']);

Route::post('team/update/{id}',  [TeamController::class, 'update']);
Route::post('team/delete/{id}',  [TeamController::class, 'delete']);
Route::get('team-player/{idOrName}',  [TeamPlayerController::class, 'index']);
Route::get('team-player/info/{idOrName}',  [TeamPlayerController::class, 'info']);
Route::post('team-player/store',  [TeamPlayerController::class, 'store']);
Route::post('team-player/update/{id}',  [TeamPlayerController::class, 'update']);
Route::post('team-player/delete/{id}',  [TeamPlayerController::class, 'delete']);
Route::group(['middleware' => 'auth:api'], function(){
	Route::post('team/store',  [TeamController::class, 'store']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});