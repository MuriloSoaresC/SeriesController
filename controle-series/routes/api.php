<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\EpisodesController;
use App\Http\Controllers\Api\SeasonsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/series', ApiController::class);
    Route::get('/series/{series}/seasons', [SeasonsController::class, 'index']);
    Route::get('/series/{series}/episodes', [EpisodesController::class, 'index']);
    Route::patch('/episodes/{episode}', [EpisodesController::class, 'update']);

});

Route::post('/login', function (Request $request) {
    $credentials = $request->only(['email', 'password']);
    if (Auth::attempt($credentials) === false) {
        return response()->json('Unauthorized', 401);
    }

    $user = Auth::user();
    $token = $user->createToken('token');

    return response()->json($token->plainTextToken);
});