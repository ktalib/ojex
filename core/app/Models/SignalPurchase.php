<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignalPurchase extends Model
{
    protected $fillable = [
        'signal_id',
        'user_id',
        'amount',
        'price_at_purchase',
        'strength_at_purchase',
        'currency',
        'total_cost',
        'status',
        'transaction_id',
        'is_active'
    ];

    protected $casts = [
        'amount' => 'decimal:3',
        'price_at_purchase' => 'decimal:2',
        'strength_at_purchase' => 'decimal:2',
        'total_cost' => 'decimal:2'
    ];

    // Relationship with Signal
    public function signal()
    {
        return $this->belongsTo(Signal::class);
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper method to calculate total cost
    public static function calculateTotalCost($amount, $price)
    {
        return $amount * $price;
    }

    // Scope for filtering completed purchases
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Scope for filtering purchases by date range
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
