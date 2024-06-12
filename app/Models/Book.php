<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory,SoftDeletes;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'isbn',
        'title',
        'cover_image_name',
        'author',
        'published_year',
        'price_per_book',
        'quantity',
        'quantity_now',
        'description',
        'user_id',
    ];



    /**
     * Returns a belongs to many relationship between Book model and BookCategory model
     * using BookCategoryPivot as the pivot table.
     *
     * @return BelongsToMany
     */
    public function bookCategories() :BelongsToMany
    {
        return $this->belongsToMany(BookCategory::class,'book_category_pivot')->using(BookCategoryPivot::class);
    }

    /**
     * Returns a belongs to many relationship between Book model and Bookshelf model
     * using BookshelfPivot as the pivot table.
     *
     * @return BelongsToMany
     */
    public function bookshelves() :BelongsToMany
    {
        return $this->belongsToMany(Bookshelf::class,'bookshelf_pivot')->using(BookshelfPivot::class);
    }

    /**
     * Returns a belongs to many relationship between the current model and 
     * the BorrowBook model using the BorrowBookPivot model as the pivot table.
     *
     * @return BelongsToMany
     */
    public function borrowBooks(): BelongsToMany
    {
        return $this->belongsToMany(BorrowBook::class, 'borrow_book_pivot')
            ->using(BorrowBookPivot::class);
    }

    /**
     * Retrieve the user that owns the current book.
     *
     * @return BelongsTo 
     */
    public function user() : BelongsTo 
    {
        return $this->belongsTo(User::class);
    }
}
