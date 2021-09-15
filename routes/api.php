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

Route::get('team-player/{idOrName}',  [TeamPlayerController::class, 'index']);
Route::get('team-player/info/{idOrName}',  [TeamPlayerController::class, 'info']);
Route::group(['middleware' => 'auth:api'], function(){
	Route::post('team',  [TeamController::class, 'store']);
	Route::post('team/{id}',  [TeamController::class, 'update']);
	Route::delete('team/{id}',  [TeamController::class, 'delete']);
	Route::post('team-player',  [TeamPlayerController::class, 'store']);
	Route::post('team-player/{id}',  [TeamPlayerController::class, 'update']);
	Route::delete('team-player/{id}',  [TeamPlayerController::class, 'delete']);
});
