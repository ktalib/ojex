<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignalPurchase extends Model
{
    protected $fillable = [
        'signal_id',
        'user_id',
        'amount',
        'strength_at_purchase',
        'currency',
        'status',
        'is_active'
    ];

    protected $casts = [
        'amount' => 'decimal:3',
        'price_at_purchase' => 'decimal:2',
        'strength_at_purchase' => 'decimal:2',
        'total_cost' => 'decimal:2'
    ];

    // Relationship with Signal
   
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
