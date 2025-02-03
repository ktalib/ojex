<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\Plan;
use App\Models\PlanHistory;
use App\Models\Transaction;
use App\Models\UserProfitLoss;
use App\Models\Wallet;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function list()
    {
        $pageTitle = "Plan List";
        $plans     = Plan::active()->with('planPhases.phaseLogics.logicBox')->paginate(getPaginate());
        return view('Template::user.plan.list', compact('pageTitle', 'plans'));
    }

    public function buy($planId)
    {
        $pageTitle = 'Payment Methods';
        $user      = auth()->user();
        $renew     = $planId ? false : true;

        if ($user->hasSubscription && !$renew) {
            return returnBack("You already have a plan");
        } elseif (!$user->hasSubscription && $renew) {
            return returnBack("Plan already expired");
        }

        if ($renew) {
            $plan = Plan::active()->find($user->planHistory->plan_id);

            if (!$plan) {
                return returnBack("The requested plan not found.");
            }
        } else {
            $plan = Plan::active()->findOrFail($planId);
        }

        $planPrice = $plan->price;

        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->whereHas('currency_data', function ($q) {
            $q->active();
        })->with(['method:id,code,crypto,image', 'currency_data'])->orderBy('name')->get();

        return view('Template::user.payment.deposit', compact('pageTitle', 'planId', 'planPrice', 'gatewayCurrency'));
    }

    public function savePlan(Request $request)
    {
        $request->validate([
            'plan_id'  => 'required|integer',
            'currency' => 'required|string',
            'gateway'  => 'required',
        ]);

        $user  = auth()->user();
        $renew = $request->plan_id ? false : true;

        if ($user->hasSubscription && !$renew) {
            return returnBack("You already have a plan");
        } elseif (!$user->hasSubscription && $renew) {
            return returnBack("Plan already expired");
        }

        $planId = $renew ? $user->planHistory->plan_id : $request->plan_id;
        $plan   = Plan::active()->findOrFail($planId);

        if (!$plan) {
            return returnBack("The requested plan not found.");
        }

        $currencySymbol = $request->gateway == 'main' ? gs('cur_text') : $request->currency;
        $currency       = Currency::active()->where('symbol', $currencySymbol)->first();

        if (!$currency) {
            return returnBack("The requested payment currency not found.");
        }

        $request->merge([
            'amount' => $plan->price / $currency->rate,
        ]);

        if ($request->gateway == 'main') {
            if ($request->amount > $user->balance) {
                return returnBack("Insufficient balance");
            }

            $user->balance -= $request->amount;
            $user->save();

            $trx = getTrx();

            if ($renew) {
                $planHistory = PlanHistory::find($user->plan_history_id);
                $planHistory->renew($user);
                $remark  = 'plan_renewed';
                $message = "renewed";
            } else {
                $plan->subscribe($user, $trx);
                $remark  = 'plan_purchased';
                $message = "purchased";
            }

            $transaction               = new Transaction();
            $transaction->user_id      = $user->id;
            $transaction->amount       = $request->amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge       = 0;
            $transaction->trx_type     = '-';
            $transaction->details      = "Plan $message via main balance";
            $transaction->remark       = $remark;
            $transaction->trx          = $trx;
            $transaction->save();

            $notify[] = ['success', 'Plan purchased successfully'];
            return to_route('user.plan.history')->withNotify($notify);
        } else {
            $gate = GatewayCurrency::where('currency', $currency->symbol)->whereHas('method', function ($gate) {
                $gate->active();
            })->where('method_code', $request->gateway)->whereHas('currency_data', function ($q) {
                $q->active();
            })->first();

            if (!$gate) {
                return returnBack("Invalid gateway");
            }

            if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
                return returnBack("Please follow deposit limit");
            }
            $charge  = ($gate->fixed_charge / $currency->rate) + ($request->amount * $gate->percent_charge / 100);
            $payable = $request->amount + $charge;

            $finalAmount = $payable;
        }

        $wallet = Wallet::where('currency_id', $currency->id)->where('user_id', $user->id)->first();

        $data                  = new Deposit();
        $data->wallet_id       = $wallet->id;
        $data->plan_id         = $plan->id;
        $data->currency_id     = $wallet->currency_id;
        $data->user_id         = $user->id;
        $data->plan_history_id = $renew ? $user->plan_history_id : 0;
        $data->method_code     = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount          = $request->amount;
        $data->charge          = $charge;
        $data->rate            = 1;
        $data->final_amount    = $finalAmount;
        $data->btc_amount      = 0;
        $data->btc_wallet      = "";
        $data->trx             = getTrx();
        $data->success_url     = urlPath('user.deposit.history');
        $data->failed_url      = urlPath('user.deposit.history');
        $data->save();
        session()->put('Track', $data->trx);
        return to_route('user.deposit.confirm');
    }

    public function history()
    {
        $pageTitle = "Plan History";
        $histories = PlanHistory::where('user_id', auth()->id())->with('plan')->latest()->paginate(getPaginate());
        $gateways  = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })
            ->whereHas('currency_data', function ($q) {
                $q->active();
            })
            ->with(['method:id,code,crypto', 'currency_data'])->get();
        return view('Template::user.plan.histories', compact('pageTitle', 'histories', 'gateways'));
    }

    public function renewPlan(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|integer',
            'gateway' => 'required|integer',
        ]);

        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->active();
        })->where('method_code', $request->gateway)->first();

        if (!$gate) {
            return returnBack("Invalid gateway");
        }

        $user = auth()->user();

        $planHistory = PlanHistory::where('user_id', $user->id)->where('plan_id', $request->plan_id)->first();

        if (!$planHistory) {
            return returnBack("Invalid plan");
        }

        if ($planHistory->expires_at < today()) {
            return returnBack("Plan already expired");
        }

        return $request;
    }

    public function progress()
    {
        $pageTitle = 'My Plan Progress';
        $user      = auth()->user();

        $planHistory = PlanHistory::with('userPhases', 'plan.planPhases.phaseLogics.logicBox')->where('user_id', $user->id);
        $planHistory = request()->plan_history ? $planHistory->find(request()->plan_history) : $planHistory->orderBy('id', 'desc')->first();

        $planHistories = PlanHistory::with('plan')->where('user_id', $user->id)->orderBy('id', 'desc')->get();

        $chart = [];

        if ($planHistory) {
            $userProfitLosses = UserProfitLoss::where('plan_history_id', $planHistory->id)->get()->skip(1)->values();
            $chart['date']    = $userProfitLosses->pluck('created_at')->map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('d-M-y');
            });

            $chart['amount'] = $userProfitLosses->pluck('balance_change');
        }

        return view('Template::user.plan.progress', compact('pageTitle', 'planHistory', 'planHistories', 'chart'));
    }

}
