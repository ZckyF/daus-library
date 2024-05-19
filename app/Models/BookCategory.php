<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'name_category',
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
        return $this->belongsToMany(Book::class)->using(BookCategoryPivot::class);
    }

}
