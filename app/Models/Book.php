<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        return $this->belongsToMany(BookCategory::class)->using(BookCategoryPivot::class);
    }

    /**
     * Returns a belongs to many relationship between Book model and Bookshelf model
     * using BookshelfPivot as the pivot table.
     *
     * @return BelongsToMany
     */
    public function bookshelves() :BelongsToMany
    {
        return $this->belongsToMany(Bookshelf::class)->using(BookshelfPivot::class);
    }
}
