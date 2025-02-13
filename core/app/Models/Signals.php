<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signals extends Model
{
    protected $fillable = [
        'name',
        'signal_price',
        'signal_strength',
        'amount',
        'currency',
        'current_balance',
        'is_active'
    ];

    protected $casts = [
        'signal_price' => 'decimal:2',
        'signal_strength' => 'decimal:2',
        'amount' => 'decimal:3',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Helper method to format signal strength as percentage
    public function getFormattedSignalStrengthAttribute()
    {
        return $this->signal_strength . '%';
    }

    
}