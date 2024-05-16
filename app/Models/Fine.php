<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fine extends Model
{
    use HasFactory,SoftDeletes;

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

    public function member() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    
}
