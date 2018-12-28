<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
        'created_by',
        'updated_by'
        ];
}
