<?php

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Logout;
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

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('index');
    })->name('dashboard');
    Route::post('/logout', [Logout::class,'logout'])->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('/login',Login::class)->name('login');
    Route::get('/forgot-password',ForgotPassword::class)->name('forgot-password');
});



