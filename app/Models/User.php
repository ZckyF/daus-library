<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    use  HasFactory, Notifiable,SoftDeletes,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $guarded  = ['id'];
    protected $fillable = [
        'username',
        'password',
        'avatar_name',
        'is_active',
        'employee_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Retrieve the associated employee for the current user.
     *
     * @return BelongsTo
     */
    public function employee() : BelongsTo 
    {
        return $this->belongsTo(Employee::class);
    }
    /**
     * Retrieve the associated roles for the current user.
     * 
     * @return bool
     */
    public function isOnline(): bool
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    
}
