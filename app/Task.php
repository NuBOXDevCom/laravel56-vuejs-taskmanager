<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @property array|Request|string description
 * @property array|Request|string name
 * @property array|Request|string user_id
 */
class Task extends Model
{
    protected $fillable = [
        'name',
        'description',
        'user_id'
    ];
}
