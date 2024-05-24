<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bookshelf extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'bookshelve_number',
        'location',
        'user_id'
    ];

    
    /**
     * Returns a belongs to many relationship between Book model and Bookshelf model
     * using BookshelfPivot as the pivot table.
     *
     * @return BelongsToMany
     */
    public function books() :BelongsToMany
    {
        return $this->belongsToMany(Book::class,'bookshelf_pivot')->using(BookshelfPivot::class);
    }

     /**
     * Retrieve the user that owns the current book category.
     *
     * @return BelongsTo 
     */

     public function user() :BelongsTo
     {
         return $this->belongsTo(User::class);
     }
    
}
