<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;


class BookshelfPivot extends Pivot
{
    use SoftDeletes;

    public $incrementing = true;


}
