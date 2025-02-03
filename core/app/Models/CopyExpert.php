<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CopyExpert extends Model
{
    protected $fillable = [
        'amount',
        'currency',
        'user_id',
    ];

    public function saveDeposit(array $data)
    {
        return $this->create($data);
    }

    //hasExpertFeeDeposit

    public function hasExpertFeeDeposit($user_id)
    {
        return $this->where('user_id', $user_id)->where('type', 'expert_fee')->exists();
    }
}