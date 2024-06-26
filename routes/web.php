<?php

use App\Http\Controllers\LocaleController;
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

Route::get('/', function () {
    return redirect()->route('register'); // Редирект на маршрут регистрации
});

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
});

require __DIR__.'/auth.php';
