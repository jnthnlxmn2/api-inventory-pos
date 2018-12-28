<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $fillable = [
        'purchase_id',
        'product_id',
        'total_amount',
        'total_tax',
        'total_quantity',
        'sale_amount',
        'total_sale',
        'customer_id',
        'per_unit_id',
        'amount',
        'tax',
        'net',
        'total_retail_price'
    ];
}
