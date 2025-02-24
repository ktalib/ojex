<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\GatewayCurrency;
use App\Models\Order;
use App\Models\Trade;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\WithdrawMethod;
use App\Models\UserWallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function list()
    {
        $pageTitle = 'My Wallets';
        $wallets   = UserWallet::where('user_id', auth()->id())->get();
        return view('Template::user.wallet.list', compact('pageTitle', 'wallets'));
    }

    public function view($curSymbol)
    {
        $currency  = Currency::where('symbol', $curSymbol)->firstOrFail();
        $pageTitle =  $currency->symbol.' Wallets';
        $wallet    = $this->walletQuery()->where('wallets.currency_id', $currency->id)->first();
        $user      = auth()->user();

        abort_if(!$wallet->id, 404);

        $trxQuery     = Transaction::where('wallet_id', $wallet->id)->with('wallet.currency');
        $transactions = (clone $trxQuery)->latest('id')->paginate(getPaginate());
        $orderQuery   = Order::where('user_id', $user->id);
        $orderQuery   = currencyWiseOrderQuery($orderQuery, $currency);

        $widget['total_order']       = (clone $orderQuery)->count();
        $widget['open_order']        = (clone $orderQuery)->open()->count();
        $widget['completed_order']   = (clone $orderQuery)->completed()->count();
        $widget['canceled_order']    = (clone $orderQuery)->canceled()->count();
        $widget['total_transaction'] = (clone $trxQuery)->count();

        $gateways = GatewayCurrency::where('currency', $curSymbol)->whereHas('method', function ($gate) {
            $gate->active();
        })->with('method')->get();

        $withdrawMethods       = WithdrawMethod::active()->where('currency', $curSymbol)->get();
        $widget['total_trade'] = Trade::where('trader_id', $user->id)->whereHas('order', function ($q) use ($currency) {
            $q = currencyWiseOrderQuery($q, $currency);
        })->count();

        $currencies = Currency::active()->where('id', '!=', $currency->id)->get();
        $user = auth()->user();

        return view('Template::user.wallet.view', compact('pageTitle', 'wallet', 'widget', 'transactions', 'gateways', 'withdrawMethods', 'currency', 'currencies', 'user'));
    }

    private function walletQuery()
    {
        return Wallet::with('currency')
            ->where('wallets.user_id', auth()->id())
            ->select('wallets.*')
            ->leftJoin('orders', function ($join) {
                $join->on('wallets.currency_id', '=', \DB::raw('CASE WHEN orders.order_side = ' . Status::BUY_SIDE_ORDER . ' THEN orders.market_currency_id ELSE orders.coin_id END'))
                    ->where('orders.user_id', auth()->id())->where('orders.Status', Status::ORDER_OPEN);
            })
            ->selectRaw('SUM(CASE WHEN orders.order_side = ? THEN ((orders.amount - orders.filled_amount) * orders.rate) ELSE (orders.amount - orders.filled_amount) END) as in_order', [Status::BUY_SIDE_ORDER]);

    }

    public function convert(Request $request)
    {
        $request->validate([
            'amount'              => 'required|numeric|gt:0',
            'currency_id'         => 'required|integer',
            'convert_currency_id' => 'required|integer',
        ]);

        $user        = auth()->user();
        $planHistory = $user->planHistory;

        if ($planHistory->rem_conversion <= 0) {
            return returnBack('You have reached the maximum conversion limit for your plan');
        }

        $currency        = Currency::active()->findOrFail($request->currency_id);
        $convertCurrency = Currency::active()->findOrFail($request->convert_currency_id);

        $wallet = Wallet::where('currency_id', $currency->id)->where('user_id', $user->id)->firstOrFail();

        if ($wallet->balance < $request->amount) {
            return returnBack('Insufficient wallet balance');
        }

        $planHistory->decrement('rem_conversion');

        $currencyIdUsd = $request->amount * $currency->rate;
        $totalAmount   = $currencyIdUsd / $convertCurrency->rate;
        $charge        = $totalAmount * gs('conversion_charge') / 100;
        $finalAmount   = $totalAmount - $charge;

        $wallet->balance -= $request->amount;
        $wallet->save();

        $trx = getTrx();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->wallet_id    = $wallet->id;
        $transaction->amount       = $request->amount;
        $transaction->post_balance = $wallet->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '-';
        $transaction->details      = 'Convert to ' . $convertCurrency->symbol;
        $transaction->trx          = $trx;
        $transaction->remark       = 'currency_convert';
        $transaction->save();

        $convertWallet = Wallet::where('currency_id', $convertCurrency->id)->where('user_id', $user->id)->first();

        if (!$convertWallet) {
            $convertWallet              = new Wallet();
            $convertWallet->user_id     = $user->id;
            $convertWallet->currency_id = $convertCurrency->id;
            $convertWallet->balance     = 0;
            $convertWallet->save();
        }

        $convertWallet->balance += $finalAmount;
        $convertWallet->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->wallet_id    = $convertWallet->id;
        $transaction->amount       = $finalAmount;
        $transaction->post_balance = $convertWallet->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->details      = 'Convert from ' . $currency->symbol;
        $transaction->trx          = $trx;
        $transaction->remark       = 'currency_convert';
        $transaction->save();

        $notify[] = ['success', 'Currency converted successfully'];
        return back()->withNotify($notify);
    }

}
