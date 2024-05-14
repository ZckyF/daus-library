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

    // i am use guarded,because the field of books more than 10
    // protected $guarded = ['id'];

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

    public function bookCategories() :BelongsToMany
    {
        return $this->belongsToMany(BookCategory::class)->using(BookCategoryPivot::class);
    }
}
