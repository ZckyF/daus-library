<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'number_card',
        'full_name',
        'email',
        'phone_number',
        'address',
        'image_name',
        'user_id',
    ];

    public function user() : BelongsTo 
    {
        return $this->belongsTo(User::class);
    }
}
