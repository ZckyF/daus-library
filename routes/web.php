<?php

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Logout;
use App\Livewire\Auth\ResetPassword;


use App\Livewire\Books\Index as BookIndex;
use App\Livewire\Books\Create as BookCreate;
use App\Livewire\Books\Update as BookUpdate;

use App\Livewire\BookCategories\Index as BookCategoriesIndex;
use App\Livewire\BookCategories\Create as BookCategoriesCreate;
use App\Livewire\BookCategories\Update as BookCategoriesUpdate;


use App\Livewire\Dashboard;
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
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::get('/books', BookIndex::class)->name('books');
    Route::get('/books/create', BookCreate::class)->name('books.create');
    Route::get('/books/{title}/{author}', BookUpdate::class)->name('books.update');

    Route::get('/book-categories',BookCategoriesIndex::class)->name('book-categories');
    Route::get('/book-categories/create',BookCategoriesCreate::class)->name('book-categories.create');
    Route::get('/book-categories/{category_name}',BookCategoriesUpdate::class)->name('book-categories.update');
    
    Route::post('/logout', [Logout::class,'logout'])->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('/login',Login::class)->name('login');

    Route::get('/forgot-password',ForgotPassword::class)->name('forgot-password');
    Route::get('/reset-password/{email}/{token}', ResetPassword::class)->middleware('check.reset.password.token')->name('reset-password');
});



