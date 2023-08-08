<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mappers\AirSial\AirSialController;
use App\Http\Controllers\Mappers\Aljazeera\AljazeeraController;
use App\Http\Controllers\Mappers\MapperController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', function () {
    return view('home');
});

Route::get('/airsial', [AirSialController::class, 'getAirlineData']);
Route::get('/aljazeera', [AljazeeraController::class, 'getAirlineData']);
Route::get('/getflights',[MapperController::class, 'getData']);