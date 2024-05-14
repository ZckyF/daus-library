<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bookshelf extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'bookshelve_number',
        'location',
        'user_id'
    ];

    
}
