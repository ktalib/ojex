<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoDeposit extends Model
{
    protected $fillable = [
        'amount',
        'currency',
        'proof',
        'user_id',
        'reference',
        'type',
        'status',

    ];

    public function saveDeposit(array $data)
    {
        return $this->create($data);
    }
}