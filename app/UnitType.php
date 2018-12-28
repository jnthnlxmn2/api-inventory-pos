<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'created_by',
        'updated_by'
        ];
}
