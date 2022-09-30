<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MonitoringController;
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
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/processing', [MonitoringController::class, 'processing']);
    Route::get('/user/{user}/purchases', [MonitoringController::class, 'userPurchasesIndex']);
    Route::get('/user/purchases/{purchase}', [MonitoringController::class, 'userPurchasesShow']);
    Route::post('/user/purchases/{purchase}', [MonitoringController::class, 'userPurchasesUpdate']);
});
