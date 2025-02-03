<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAsset extends Model
{
    protected $table = 'user_assets';
    
    protected $fillable = [
        'user_id',
        'asset_name',
        'amount',
        'value'
    ];
}