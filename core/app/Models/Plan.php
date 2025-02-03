<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use GlobalStatus;

    public function planPhases()
    {
        return $this->hasMany(PlanPhase::class);
    }

    public function subscribe($user, $trx = null)
    {
        $trx                          = $trx ? $trx : getTrx();
        $expiresAt                    = now()->addDays($this->duration);
        $subscription                 = new PlanHistory();
        $subscription->user_id        = $user->id;
        $subscription->plan_id        = $this->id;
        $subscription->price          = $this->price;
        $subscription->fund           = $this->fund;
        $subscription->duration       = $this->duration;
        $subscription->conversion     = $this->conversion;
        $subscription->rem_conversion = $this->conversion;
        $subscription->expires_at     = $expiresAt;
        $subscription->save();

        $user->plan_history_id = $subscription->id;
        $user->plan_expires_at = $expiresAt;
        $user->save();

        Wallet::where('user_id', auth()->id())->update(['balance' => 0]);

        if ($subscription->fund) {
            $currency = Currency::active()->where('symbol', strtoupper(gs('cur_text')))->first();
            $wallet   = Wallet::where('currency_id', $currency->id)->where('user_id', $user->id)->first();

            if (!$wallet) {
                $wallet              = new Wallet();
                $wallet->user_id     = $user->id;
                $wallet->currency_id = $currency->id;
                $wallet->save();
            }
            $wallet->balance = $wallet->balance + $subscription->fund;
            $wallet->save();

            $transaction               = new Transaction();
            $transaction->user_id      = $user->id;
            $transaction->wallet_id    = $wallet->id;
            $transaction->amount       = $subscription->fund;
            $transaction->post_balance = $wallet->balance;
            $transaction->charge       = 0;
            $transaction->trx_type     = '+';
            $transaction->details      = 'Fund received from ' . $this->name . ' plan';
            $transaction->trx          = $trx;
            $transaction->remark       = 'plan_fund';
            $transaction->save();
        }

        $userProfitLoss                  = new UserProfitLoss();
        $userProfitLoss->user_id         = $user->id;
        $userProfitLoss->plan_history_id = $subscription->id;
        $userProfitLoss->balance         = $subscription->fund;
        $userProfitLoss->save();

        notify($user, 'PLAN_PURCHASED', [
            'plan_name'  => $this->name,
            'price'      => showAmount($this->price),
            'fund'       => showAmount($this->fund),
            'duration'   => $this->duration,
            'expires_at' => $expiresAt,
        ]);

    }

}
