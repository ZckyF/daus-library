<?php

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Logout;
use App\Livewire\Auth\ResetPassword;

use App\Livewire\Dashboard;

use App\Livewire\Books\Index as BookIndex;
use App\Livewire\Books\Create as BookCreate;
use App\Livewire\Books\Edit as BookEdit;

use App\Livewire\BookCategories\Index as BookCategoryIndex;
use App\Livewire\BookCategories\Create as BookCategoryCreate;
use App\Livewire\BookCategories\Edit as BookCategoryEdit;

use App\Livewire\Bookshelves\Index as BookshelfIndex;
use App\Livewire\Bookshelves\Create as BookshelfCreate;
use App\Livewire\Bookshelves\Edit as BookshelfEdit;

use App\Livewire\Members\Index as MemberIndex;
use App\Livewire\Members\Create as MemberCreate;
use App\Livewire\Members\Edit as MemberEdit;

use App\Livewire\Users\Index as UserIndex;
use App\Livewire\Users\Create as UserCreate;
use App\Livewire\Users\Edit as UserEdit;


use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Bookshelf;
use App\Models\Member;
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

    Route::get('/books', BookIndex::class)->can('viewAny', Book::class)->name('books');
    Route::get('/books/create', BookCreate::class)->can('create', Book::class)->name('books.create');
    Route::get('/books/{title}/{author}', BookEdit::class)->name('books.edit');

    Route::get('/book-categories',BookCategoryIndex::class)->can('viewAny', BookCategory::class)->name('book-categories');
    Route::get('/book-categories/create',BookCategoryCreate::class)->can('create', BookCategory::class)->name('book-categories.create');
    Route::get('/book-categories/{category_name}',BookCategoryEdit::class)->name('book-categories.edit');

    Route::get('/bookshelves', BookshelfIndex::class)->can('viewAny', Bookshelf::class)->name('bookshelves');
    Route::get('/bookshelves/create', BookshelfCreate::class)->can('create', Bookshelf::class)->name('bookshelves.create');
    Route::get('/bookshelves/{bookshelf_number}', BookshelfEdit::class)->name('bookshelves.edit');

    Route::get('/members', MemberIndex::class)->can('viewAny', Member::class)->name('members');
    Route::get('/members/create', MemberCreate::class)->can('create', Member::class)->name('members.create');
    Route::get('/members/{number_card}', MemberEdit::class)->name('members.edit');
    
    Route::get('/users', UserIndex::class)->name('users');
    Route::get('/users/create', UserCreate::class)->name('users.create');
    Route::get('/users/{username}', UserEdit::class)->name('users.edit');

    
    Route::post('/logout', [Logout::class,'logout'])->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('/login',Login::class)->name('login');

    Route::get('/forgot-password',ForgotPassword::class)->name('forgot-password');
    Route::get('/reset-password/{email}/{token}', ResetPassword::class)->middleware('check.reset.password.token')->name('reset-password');
});



