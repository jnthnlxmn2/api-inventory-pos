<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'code',
        'type',
        'date_used',
        'active',
        'value',
        ];
}
