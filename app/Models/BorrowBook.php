<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BorrowBook extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'member_id',
        'borrow_number',
        'borrow_date',
        'return_date',
        'status',
        'returned_date',
        'quantity',
    ];

    /**
     * Retrieve the associated member for this model.
     *
     * @return BelongsTo The associated member.
     */
    public function member() : BelongsTo 
    {
        return $this->belongsTo(Member::class);    
    }

    /**
     * Retrieve the associated user for this model.
     *
     * @return BelongsTo The associated user.
     */
    public function user() : BelongsTo 
    {
        return $this->belongsTo(User::class);    
    }
    /**
     * Retrieve the books associated with this model through the borrow_book_pivot table.
     *
     * @return BelongsToMany The books associated with this model.
     */
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'borrow_book_pivot')
            ->using(BorrowBookPivot::class)->withTimestamps();
    }
    
}
