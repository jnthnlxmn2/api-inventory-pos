<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'table',
        'action',
        'field',
        'old_value',
        'new_value',
        'user_id',
    ];
}
