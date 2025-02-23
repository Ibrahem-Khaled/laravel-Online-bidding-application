<?php

use App\Http\Controllers\dashboard\AuctionsController;
use App\Http\Controllers\dashboard\CategoriesController;
use App\Http\Controllers\dashboard\HomeController;
use App\Http\Controllers\dashboard\LiveChatController;
use App\Http\Controllers\dashboard\LiveStreamingsController;
use App\Http\Controllers\dashboard\SliderController;
use App\Http\Controllers\dashboard\usersController;
use Illuminate\Support\Facades\Route;

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
    return redirect()->route('home.dashboard');
})->name('home');


Route::group(['middleware' => ['auth', 'checkRole:admin'], 'prefix' => 'dashboard'], function () {

    Route::get('/', [HomeController::class, 'index'])->name('home.dashboard');

    Route::resource('users', usersController::class);
    Route::resource('categories', CategoriesController::class);
    Route::resource('live-streamings', LiveStreamingsController::class);
    Route::resource('auctions', AuctionsController::class);
    Route::get('/auctions/{id}/show', [AuctionsController::class, 'show'])->name('auctions.show');
    Route::resource('sliders', SliderController::class);

    // صفحة اختيار المستخدم لبدء الدردشة
    Route::get('live-chat', [LiveChatController::class, 'index'])->name('live-chat.index');

    // عرض دردشة المستخدم المحدد
    Route::get('live-chat/user/{userId}', [LiveChatController::class, 'showUserChat'])->name('dashboard.live-chat.user');

    // جلب الرسائل الخاصة بالمستخدم المحدد (AJAX)
    Route::get('live-chat/fetch/{userId}', [LiveChatController::class, 'fetchUserChatMessages'])->name('dashboard.live-chat.fetch.user');

    // إرسال رسالة للمستخدم المحدد (AJAX)
    Route::post('live-chat/send/{userId}', [LiveChatController::class, 'sendMessage'])->name('dashboard.live-chat.send');
});


require __DIR__ . '/web/auth.php';