<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BookCategory extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_name',
        'description',
        'user_id'
    ];


    /**
     * Get all books belongs to this category.
     *
     * @return BelongsToMany
     */
    public function books() :BelongsToMany
    {
        return $this->belongsToMany(Book::class,'book_category_pivot')->using(BookCategoryPivot::class);
    }

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
