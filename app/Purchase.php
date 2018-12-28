<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'tracking_number',
        'total_quantity',
        'total_amount',
        'tax_id',
        'amount_paid',
        'change',
        'vat',
        'vatable',
        'sale_total_amount',
        'discounted_amount',
        'total_discounted_amount',
        'discount_id',
        'created_by',
        'updated_by',
    ];
}
