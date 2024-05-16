<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'full_name',
        'email',
        'number_phone',
        'address',
        'nik',
        'user_id'
    ];

    public function users() : BelongsTo 
    {
        return $this->users(User::class);
    }

    
}
