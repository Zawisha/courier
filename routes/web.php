<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\YandexApiController;
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

Route::get('/',[MainController::class, 'index']);

Route::get('locale/{lang}',[LocaleController::class, 'setLocale']);
Route::get('createWalkingCourier',[YandexApiController::class, 'createWalkingCourier']);
Route::get('getWorkRules',[YandexApiController::class, 'getWorkRules']);
Route::get('getCars',[YandexApiController::class, 'getCars']);
Route::get('newCar',[YandexApiController::class, 'newCar']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['auth', 'permission:view page'])->group(function () {
        Route::get('users-list',[AdminController::class, 'usersList']);
        Route::get('edit-user/{id}',[AdminController::class, 'showUser']);
        Route::post('sendToYandex/{id}',[RegisteredUserController::class, 'sendToYandex'])->name('sendToYandex');
        Route::middleware(['permission:admin perm'])->group(function () {
            Route::post('send_to_yandex_change', [AdminController::class, 'send_to_yandex_change']);
        });
        Route::post('editCourier',[RegisteredUserController::class, 'editCourier'])->name('editCourier');

    });
    Route::post('/logout', [RegisteredUserController::class, 'logout'])->name('logout');

});




require __DIR__.'/auth.php';
