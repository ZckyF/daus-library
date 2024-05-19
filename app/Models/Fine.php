<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fine extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable =[
        'member_id',
        'non_member_name',
        'amount',
        'amount_paid',
        'change_amount',
        'reason',
        'charged_at',
        'is_paid',
        'user_id'
    ];


    /**
     * Retrieve the associated member for this fine.
     *
     * @return BelongsTo The associated member.
     */
    public function member() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    

    /**
     * Retrieve the associated user for this model.
     *
     * @return BelongsTo The associated user.
     */
    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    
}
