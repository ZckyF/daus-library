<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BorrowingBook extends Model
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
        'borrowing_number',
        'borrowing_date',
        'return_date',
        'status',
        'returned_date',
        'quantity',
    ];

    /**
     * Retrieve the users associated with the current model.
     *
     * @return BelongsToMany
     */
    public function users() :BelongsToMany
    {
        return $this->belongsToMany(User::class)->using(BorrowingBookPivot::class);
    }
    
}
