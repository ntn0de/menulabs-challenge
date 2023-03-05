<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::get('/',[UserController::class,'index']);
Route::get('/{id}', [UserController::class, 'show']);
Route::put('/forceUpdate/{id}',[UserController::class,'forceUpdate']);
// To Flush cache at Testing
Route::get('/flush',[UserController::class,'flush']);



