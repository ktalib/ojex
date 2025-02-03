<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Lib\BotTrade;
use App\Lib\CurlRequest;
use App\Lib\TradeManager;
use App\Models\BotConfig;
use App\Models\CoinPair;
use App\Models\CronJob;
use App\Models\CronJobLog;
use App\Models\Order;
use App\Models\PhaseLog;
use App\Models\PlanHistory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserPhase;
use App\Models\UserProfitLoss;
use App\Models\Wallet;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class CronController extends Controller
{
    public function cron()
    {
        $general            = gs();
        $general->last_cron = now();
        $general->save();

        $crons = CronJob::with('schedule');

        if (request()->alias) {
            $crons->where('alias', request()->alias);
        } else {
            $crons->where('next_run', '<', now())->where('is_running', Status::YES);
        }
        $crons = $crons->get();
        foreach ($crons as $cron) {
            $cronLog              = new CronJobLog();
            $cronLog->cron_job_id = $cron->id;
            $cronLog->start_at    = now();
            if ($cron->is_default) {
                $controller = new $cron->action[0];
                try {
                    $method = $cron->action[1];
                    $controller->$method();
                } catch (\Exception $e) {
                    $cronLog->error = $e->getMessage();
                }
            } else {
                try {
                    CurlRequest::curlContent($cron->url);
                } catch (\Exception $e) {
                    $cronLog->error = $e->getMessage();
                }
            }
            $cron->last_run = now();
            $cron->next_run = now()->addSeconds($cron->schedule->interval);
            $cron->save();

            $cronLog->end_at = $cron->last_run;

            $startTime         = Carbon::parse($cronLog->start_at);
            $endTime           = Carbon::parse($cronLog->end_at);
            $diffInSeconds     = $startTime->diffInSeconds($endTime);
            $cronLog->duration = $diffInSeconds;
            $cronLog->save();
        }
        if (request()->target == 'all') {
            $notify[] = ['success', 'Cron executed successfully'];
            return back()->withNotify($notify);
        }
        if (request()->alias) {
            $notify[] = ['success', keyToTitle(request()->alias) . ' executed successfully'];
            return back()->withNotify($notify);
        }
    }

    public function crypto()
    {
        try {
            return defaultCurrencyDataProvider()->updateCryptoPrice();
        } catch (Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function market()
    {
        try {
            return defaultCurrencyDataProvider()->updateMarkets();
        } catch (Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function trade()
    {
        try {
            $trade = new TradeManager();
            return $trade->trade();
        } catch (Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function botOrder()
    {
        $botConfig = BotConfig::first();
        $coinPairs = CoinPair::with('coin', 'market', 'marketData')->active()->get();

        foreach ($coinPairs as $coinPair) {
            $buyOrders          = Order::open()->where('user_id', '>=', $botConfig->sell_matching_with_bot == Status::YES ? 1 : 2)->buySideOrder()->where('pair_id', $coinPair->id)->get();
            $sellOrders         = Order::open()->where('user_id', '>=', $botConfig->buy_matching_with_bot == Status::YES ? 1 : 2)->sellSideOrder()->where('pair_id', $coinPair->id)->get();
            $openBuyOrderRates  = $buyOrders->pluck('rate')->toArray();
            $openSellOrderRates = $sellOrders->pluck('rate')->toArray();
            $botTradeObj        = new BotTrade($botConfig);

            $botBuyOrderRates = $botTradeObj->getRandomOrderRate($coinPair, $openSellOrderRates, Status::BUY_SIDE_ORDER);
            $botTradeObj->placeOrder($botBuyOrderRates, $coinPair, Status::BUY_SIDE_ORDER);

            $botSellOrderRates = $botTradeObj->getRandomOrderRate($coinPair, $openBuyOrderRates, Status::SELL_SIDE_ORDER);
            $botTradeObj->placeOrder($botSellOrderRates, $coinPair, Status::SELL_SIDE_ORDER);
        }

    }

    public function cancelBotOrder()
    {
        $botConfig = BotConfig::first();
        $orders    = Order::open()->where('user_id', 1)->whereDoesntHave('trades');
        (clone $orders)->buySideOrder()->where('created_at', '<', now()->subHours($botConfig->buy_order_remain_hours))->delete();
        (clone $orders)->sellSideOrder()->where('created_at', '<', now()->subHours($botConfig->sell_order_remain_hours))->delete();
    }

    public function profitLoseCalculation()
    {
        $users = User::where('plan_history_id', '>', 0)->whereDate('plan_expires_at', '>=', today()->subDay(1))->whereDoesntHave('userProfitLoss', function ($profitLoss) {
            $profitLoss->whereDate('created_at', today());
        })->limit(100)->get();

        foreach ($users as $user) {
            $lastProfitLoss = UserProfitLoss::where('user_id', $user->id)->where('plan_history_id', $user->plan_history_id)->orderBy('id', 'desc')->first();

            $estimatedBalance = Wallet::where('user_id', $user->id)->join('currencies', 'wallets.currency_id', 'currencies.id')->sum(DB::raw('currencies.midnight_rate * wallets.balance'));

            $openBuyOrderValue = Order::where('orders.status', Status::ORDER_OPEN)->where('user_id', $user->id)->where('order_side', Status::BUY_SIDE_ORDER)
                ->join('currencies', 'orders.coin_id', 'currencies.id')
                ->join('currencies as marketCurrencies', 'orders.market_currency_id', 'marketCurrencies.id')
                ->sum(DB::raw('(orders.total -  (orders.filled_amount / orders.amount * orders.total)) * marketCurrencies.midnight_rate'));

            $openSellOrderValue = Order::where('orders.status', Status::ORDER_OPEN)->where('user_id', $user->id)->where('order_side', Status::SELL_SIDE_ORDER)
                ->join('currencies', 'orders.coin_id', 'currencies.id')
                ->sum(DB::raw('(orders.amount -  (orders.filled_amount / orders.amount * orders.amount )) * currencies.midnight_rate'));

            $totalAmount = $estimatedBalance + $openBuyOrderValue + $openSellOrderValue;

            $userProfitLoss                  = new UserProfitLoss();
            $userProfitLoss->user_id         = $user->id;
            $userProfitLoss->plan_history_id = $user->plan_history_id;
            $userProfitLoss->balance         = $totalAmount;
            $userProfitLoss->balance_change  = $totalAmount - $lastProfitLoss->balance;
            $userProfitLoss->save();
        }

    }

    public function checkPhaseCompletion()
    {
        $planHistories = PlanHistory::active()->with('user', 'plan.planPhases.phaseLogics.logicBox')->where('status', Status::PLAN_HISTORY_RUNNING)
            ->where(function ($query) {
                $query->where('last_update', '<', today())
                    ->orWhere('last_update', null);
            })
            ->limit(50)->get();

        foreach ($planHistories as $planHistory) {
            $userProfitLoss = UserProfitLoss::where('user_id', $planHistory->user_id)->where('plan_history_id', $planHistory->id)->whereDate('created_at', today())->exists();

            if (!$userProfitLoss) {
                continue;
            }

            $planHistory->last_update = today();
            $planHistory->save();
            $user = $planHistory->user;

            $plan = $planHistory->plan;

            $lastDayProfit = $this->getUserProfit($planHistory, 1);

            if ($lastDayProfit < 0 && abs($lastDayProfit) > $plan->max_daily_loss) {
                $this->failedPlan($planHistory, $user, 'Max daily loss');
            }

            $planHistoryDuration = now()->startOfDay()->diffInDays($planHistory->created_at);
            $overAllProfit       = $this->getUserProfit($planHistory, $planHistoryDuration);

            if ($overAllProfit < 0 && abs($overAllProfit) > $plan->max_overall_loss) {
                $this->failedPlan($planHistory, $user, 'Max over all loss');
            }

            $phaseIds = $planHistory->plan->planPhases->pluck('id')->toArray();

            $phaseIndex = array_search($planHistory->last_completed_phase, $phaseIds) ?? 0;
            $phaseIndex = $phaseIndex === false ? 0 : $phaseIndex + 1;

            $currentPhaseId     = $phaseIds[$phaseIndex];
            $currentPhase       = $planHistory->plan->planPhases[$phaseIndex];
            $currentPhaseLogics = $currentPhase->phaseLogics;

            foreach ($currentPhaseLogics as $currentLogic) {
                $userPhase = UserPhase::where('user_id', $user->id)->where('plan_history_id', $planHistory->id)->where('phase_logic_id', $currentLogic->id)->exists();

                if ($userPhase) {
                    continue;
                }

                $logicBox    = $currentLogic->logicBox;
                $userRevenue = $this->getUserProfit($planHistory, $logicBox->duration);

                $condition1 = $phaseIndex == 0 && now()->parse($planHistory->created_at)->addDays($logicBox->duration)->format('Y-m-d') == today()->format('Y-m-d');
                $condition2 = $phaseIndex > 0 && now()->parse($planHistory->phase_completed_at)->addDays($logicBox->duration)->format('Y-m-d') == today()->format('Y-m-d');

                if ($condition1 || $condition2) {
                    $userPhase                  = new UserPhase();
                    $userPhase->user_id         = $user->id;
                    $userPhase->plan_history_id = $planHistory->id;
                    $userPhase->plan_id         = $planHistory->plan_id;
                    $userPhase->plan_phase_id   = $currentPhaseId;
                    $userPhase->phase_logic_id  = $currentLogic->id;
                    $userPhase->action_time     = now();

                    if ($this->isMatchLogicBox($logicBox, $userRevenue)) {
                        $userPhase->status = Status::USER_PHASE_SUCCESS;
                        //Go to next phase
                        $phaseCompleted = UserPhase::where('plan_history_id', $planHistory->id)->where('plan_phase_id', $currentPhaseId)->where('status', Status::USER_PHASE_SUCCESS)->count();

                        if (count($currentPhaseLogics) == $phaseCompleted + 1) {

                            // Phase complete profit
                            $lastPhaseCompleteDuration = now()->parse($planHistory->phase_completed_at ?? $planHistory->created_at)->diffInDays(now());
                            $userProfit                = $this->getUserProfit($planHistory, $lastPhaseCompleteDuration, false);
                            $newProfit                 = $currentPhase->profit * $userProfit / 100;
                            $user->balance += $newProfit;
                            $user->save();

                            $phaseLog                  = new PhaseLog();
                            $phaseLog->user_id         = $user->id;
                            $phaseLog->plan_history_id = $planHistory->id;
                            $phaseLog->phase_id        = $currentPhaseId;
                            $phaseLog->profit          = $this->getUserProfit($planHistory, $lastPhaseCompleteDuration);
                            $phaseLog->save();

                            $transaction               = new Transaction();
                            $transaction->user_id      = $user->id;
                            $transaction->amount       = $newProfit;
                            $transaction->post_balance = $user->balance;
                            $transaction->charge       = 0;
                            $transaction->trx_type     = '+';
                            $transaction->details      = 'Phase profit share from ' . $currentPhase->name;
                            $transaction->trx          = getTrx();
                            $transaction->remark       = 'profit_share';
                            $transaction->save();

                            if ($phaseIndex == array_key_last($phaseIds)) {
                                $planHistory->status   = Status::PLAN_HISTORY_COMPLETED;
                                $user->plan_history_id = 0;
                                $user->plan_expires_at = now();
                                $user->save();

                                Order::where('user_id', $user->id)->where('status', Status::ORDER_OPEN)->update(['status' => Status::ORDER_CANCELED]);
                                Wallet::where('user_id', $user->id)->update(['balance' => 0]);
                            }

                            $planHistory->last_completed_phase = $currentPhaseId;
                            $planHistory->phase_completed_at   = today();
                            $planHistory->save();

                            notify($user, 'PHASE_PROFIT', [
                                'plan_name' => $planHistory->plan->name,
                                'profit'    => showAmount($newProfit),
                            ]);
                        }
                    } else {
                        $userPhase->status = Status::USER_PHASE_FAILED;
                        $this->failedPlan($planHistory, $user, 'Unable to complete phase');
                    }

                    $userPhase->save();
                }
            }
        }

        PlanHistory::active()->where('expires_at', '<', today())->update(['status' => Status::PLAN_HISTORY_EXPIRED]);
    }

    private function isMatchLogicBox($logicBox, $userRevenue)
    {
        $matchLogic = false;

        if ($logicBox->type == Status::LOGIC_BOX_TYPE_PROFIT) {
            if ($logicBox->logic == Status::LOGIC_EQUAL) {
                $matchLogic = $userRevenue == $logicBox->value_from ? true : false;
            } else if ($logicBox->logic == Status::LOGIC_GREATER_THAN) {
                $matchLogic = $userRevenue > $logicBox->value_from ? true : false;
            } else if ($logicBox->logic == Status::LOGIC_GREATER_THAN_EQUAL) {
                $matchLogic = $userRevenue >= $logicBox->value_from ? true : false;
            } else if ($logicBox->logic == Status::LOGIC_LESS_THAN) {
                $matchLogic = $userRevenue < $logicBox->value_from ? true : false;
            } else if ($logicBox->logic == Status::LOGIC_LESS_THAN_EQUAL) {
                $matchLogic = $userRevenue <= $logicBox->value_from ? true : false;
            } else {
                $matchLogic = ($userRevenue >= $logicBox->value_from && $userRevenue <= $logicBox->value_to) ? true : false;
            }
        } else {
            if ($logicBox->logic != Status::LOGIC_LESS_THAN && $logicBox->logic != Status::LOGIC_LESS_THAN_EQUAL && $userRevenue > 0) {
                $matchLogic = false;
            } else {
                if ($logicBox->logic == Status::LOGIC_EQUAL) {
                    $matchLogic = abs($userRevenue) == $logicBox->value_from ? true : false;
                } else if ($logicBox->logic == Status::LOGIC_GREATER_THAN) {
                    $matchLogic = abs($userRevenue) > $logicBox->value_from ? true : false;
                } else if ($logicBox->logic == Status::LOGIC_GREATER_THAN_EQUAL) {
                    $matchLogic = abs($userRevenue) >= $logicBox->value_from ? true : false;
                } else if ($logicBox->logic == Status::LOGIC_LESS_THAN) {
                    $matchLogic = (($userRevenue < 0 && abs($userRevenue) < $logicBox->value_from) || $userRevenue >= 0) ? true : false;
                } else if ($logicBox->logic == Status::LOGIC_LESS_THAN_EQUAL) {
                    $matchLogic = (($userRevenue < 0 && abs($userRevenue) <= $logicBox->value_from) || $userRevenue >= 0) ? true : false;
                } else {
                    $matchLogic = (abs($userRevenue) >= $logicBox->value_from && abs($userRevenue) <= $logicBox->value_to) ? true : false;
                }
            }
        }

        return $matchLogic;
    }

    private function getUserProfit($planHistory, $duration, $isPercent = true)
    {
        $previousBalance = UserProfitLoss::where('plan_history_id', $planHistory->id)->whereDate('created_at', now()->subDay($duration))->first()?->balance ?? 1;
        $lastBalance     = UserProfitLoss::where('plan_history_id', $planHistory->id)->whereDate('created_at', today())->first()->balance;

        $amount = $isPercent ? $lastBalance / $previousBalance * 100 - 100 : $lastBalance - $previousBalance;
        return $amount;
    }

    private function failedPlan($planHistory, $user, $reason)
    {
        $planHistory->status = Status::PLAN_HISTORY_FAILED;
        $planHistory->save();

        $user->plan_history_id = 0;
        $user->plan_expires_at = now();
        $user->save();

        Order::where('user_id', $user->id)->where('status', Status::ORDER_OPEN)->update(['status' => Status::ORDER_CANCELED]);
        Wallet::where('user_id', $user->id)->update(['balance' => 0]);

        notify($user, 'PLAN_FAILED', [
            'plan_name'     => $planHistory->plan->name,
            'failed_reason' => $reason,
        ]);
    }

}
