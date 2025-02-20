<?php

use App\Http\Controllers\api\auctionController;
use App\Http\Controllers\api\authController;
use App\Http\Controllers\api\homeController;
use App\Http\Controllers\api\LiveChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [authController::class, 'register']);
Route::post('login', [authController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('update-profile', [authController::class, 'update']);
    Route::get('me', [authController::class, 'user']);
    Route::post('logout', [authController::class, 'logout']);
    Route::post('change-password', [authController::class, 'changePassword']);
    Route::post('delete-account', [authController::class, 'delete']);
});


Route::get('/categories', [homeController::class, 'getCategories']);
Route::get('/sliderPublic', [homeController::class, 'publicSlider']);
Route::get('/offers/{id}', [homeController::class, 'getOffersByCategory']);
Route::get('/liveStreamings', [homeController::class, 'getLiveStreamings']);
Route::get('/notifications', [homeController::class, 'getNotifications']);

Route::post('/create/auction/{id}', [auctionController::class, 'create']);
Route::get('/auction/{id}', [auctionController::class, 'getAuctionByCategory']);
Route::post('/addPriceOffer/{id}', [auctionController::class, 'addPriceOffer']);


Route::get('/live-chat', [LiveChatController::class, 'index']);
Route::post('/live-chat', [LiveChatController::class, 'store']);
