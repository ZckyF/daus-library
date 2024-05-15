<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class BorrowingBookPivot extends Pivot
{
    use SoftDeletes;

    public $incrementing = true;
}
