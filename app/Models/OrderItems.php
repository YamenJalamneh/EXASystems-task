<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderItems extends Pivot
{
    public $timestamps = false;

    protected $table = 'order_items';
}
