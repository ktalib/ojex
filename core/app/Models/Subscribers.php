<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscribers extends Model
{
   
    protected $fillable = [
        'name',
        'email',
        'phone',
        'country',
        'city',
        'address',
        'zip',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];
}
