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

use App\Livewire\Employees\Index as EmployeeIndex;
use App\Livewire\Employees\Create as EmployeeCreate;
use App\Livewire\Employees\Edit as EmployeeEdit;

use App\Livewire\Carts\Index as CartIndex;

use App\Livewire\BorrowBooks\Index as BorrowBookIndex;
use App\Livewire\BorrowBooks\Edit as BorrowBookEdit;

use App\Livewire\Fines\Index as FinesIndex;
use App\Livewire\Fines\Create as FinesCreate;
use App\Livewire\Fines\Edit as FinesEdit;

use App\Livewire\Settings\Index as SettingIndex;
use App\Livewire\Settings\Profile as SettingProfile;
use App\Livewire\Settings\ChangePassword as SettingChangePassword;
use App\Livewire\Settings\Language as SettingLanguage;


use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Bookshelf;
use App\Models\Employee;
use App\Models\Member;
use App\Models\User;
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

    /**
     * Master Data
     * 
     */
    Route::get('/books', BookIndex::class)->can('viewAny', Book::class)->name('books');
    Route::get('/books/create', BookCreate::class)->can('create', Book::class)->name('books.create');
    Route::get('/books/{isbn}', BookEdit::class)->name('books.edit');

    Route::get('/book-categories',BookCategoryIndex::class)->can('viewAny', BookCategory::class)->name('book-categories');
    Route::get('/book-categories/create',BookCategoryCreate::class)->can('create', BookCategory::class)->name('book-categories.create');
    Route::get('/book-categories/{category_name}',BookCategoryEdit::class)->name('book-categories.edit');

    Route::get('/bookshelves', BookshelfIndex::class)->can('viewAny', Bookshelf::class)->name('bookshelves');
    Route::get('/bookshelves/create', BookshelfCreate::class)->can('create', Bookshelf::class)->name('bookshelves.create');
    Route::get('/bookshelves/{bookshelf_number}', BookshelfEdit::class)->name('bookshelves.edit');

    Route::get('/members', MemberIndex::class)->can('viewAny', Member::class)->name('members');
    Route::get('/members/create', MemberCreate::class)->can('create', Member::class)->name('members.create');
    Route::get('/members/{number_card}', MemberEdit::class)->name('members.edit');
    
    Route::get('/users', UserIndex::class)->can('viewAny', User::class)->name('users');
    Route::get('/users/create', UserCreate::class)->can('create', User::class)->name('users.create');
    Route::get('/users/{username}', UserEdit::class)->name('users.edit');

    Route::get('/employees', EmployeeIndex::class)->can('viewAny', Employee::class)->name('employees');
    Route::get('/employees/create', EmployeeCreate::class)->can('create', Employee::class)->name('employees.create');
    Route::get('/employees/{nik}', EmployeeEdit::class)->name('employees.edit');
    /**
     * Transaction
     * 
     * 
     */
    Route::get('/carts',CartIndex::class)->can('viewAny', App\Models\Fine::class)->name('carts');

    Route::get('/borrow-books', BorrowBookIndex::class)->can('viewAny', App\Models\BorrowBook::class)->name('borrow-books');
    Route::get('/borrow-books/{borrow_number}', BorrowBookEdit::class)->name('borrow-books.edit');

    Route::get('/fines', FinesIndex::class)->can('viewAny', App\Models\Fine::class)->name('fines');
    Route::get('/fines/create', FinesCreate::class)->can('create', App\Models\Fine::class)->name('fines.create');
    Route::get('/fines/{fine_number}', FinesEdit::class)->name('fines.edit');
     /**
      * Other Options
      */
    Route::get('/settings',SettingIndex::class)->name('settings');
    Route::get('/settings/profile',SettingProfile::class)->name('settings.profile');
    Route::get('/settings/change-password',SettingChangePassword::class)->name('settings.change-password');
    Route::get('/settings/language',SettingLanguage::class)->name('settings.language');
    Route::post('/logout', [Logout::class,'logout'])->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('/login',Login::class)->name('login');

    Route::get('/forgot-password',ForgotPassword::class)->name('forgot-password');
    Route::get('/reset-password/{email}/{token}', ResetPassword::class)->middleware('check.reset.password.token')->name('reset-password');
});



