<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    protected $table = 'user_subscriptions';

    protected $fillable = [
        'user_id',
        'signal_id',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];
}