<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookCategoryPivot extends Pivot
{
    use SoftDeletes;

    public $incrementing = true;

}
