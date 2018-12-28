<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemQuantity extends Model
{
    protected $fillable = [
        'item_id',
        'quantity',
        'price',
        'created_by'
    ];
}
