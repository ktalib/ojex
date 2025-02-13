<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class  AssetTrade  extends Model
{
    protected $table = 'asset_trades';
    protected $fillable = [
        
        'user_id',
        'action', // buy, sell, convert
        'trade_type', // Crypto, Stocks, Forex
        'amount',
        'assets',
        'loss',
        'profit',
        'duration',
        'status'
    ]; 
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assetTrade(): BelongsTo
    {
        return $this->belongsTo(AssetTrade::class, 'symbol');
    }
}