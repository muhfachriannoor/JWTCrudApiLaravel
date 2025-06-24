<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Web\UserController as WebUserController;
use App\Http\Controllers\Web\HobbyController as WebHobbyController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route untuk CRUD User via Blade (dilindungi middleware 'auth')
Route::middleware(['auth'])->group(function () {
    Route::resource('users', WebUserController::class);
    Route::post('/hobis', [WebHobbyController::class, 'store'])->name('hobis.store');
    Route::delete('/hobis/{hobi}', [WebHobbyController::class, 'destroy'])->name('hobis.destroy');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});