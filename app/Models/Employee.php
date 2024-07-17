<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'number_phone',
        'address',
        'nik',
        'user_id'
    ];

    /**
     * Retrieve the associated user for this employee.
     *
     * @return BelongsTo The associated user.
     */
    public function user() : BelongsTo 
    {
        return $this->user(User::class);
    }

    
}
