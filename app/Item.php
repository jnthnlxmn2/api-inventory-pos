<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'item_code',
        'category_id',
        'sub_category_id',
        'name',
        'description',
        'sale_price',
        'stock',
        'manufacturer_id',
        'photos',
        'price',
        'current_stock',
        'total_stock',
        'created_by',
        'updated_by',
    ];
}
