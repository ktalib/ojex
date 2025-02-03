<?php

namespace App\Lib;

use App\Constants\Status;
use App\Events\Order as EventsOrder;
use App\Models\Order;
use Exception;

class BotTrade
{
    public $botConfig;
    public $upperLimit;
    public $divisor;

    public function __construct($botConfig)
    {
        $precision        = 8;
        $this->upperLimit = pow(10, $precision + 2);
        $this->divisor    = pow(10, $precision);
        $this->botConfig  = $botConfig;
    }

    public function placeOrder($orderRates, $coinPair, $orderSide)
    {
        $amountRange = $orderSide == Status::BUY_SIDE_ORDER ? $this->botConfig->buy_order_amount_range : $this->botConfig->sell_order_amount_range;
        $percentGrid = ($coinPair->maximum_buy_amount - $coinPair->minimum_buy_amount) / 100;

        $minAmount = $coinPair->minimum_buy_amount;
        $maxAmount = $coinPair->minimum_buy_amount + ($percentGrid * $amountRange);

        foreach ($orderRates as $orderRate) {
            $amount = $minAmount + (mt_rand() / mt_getrandmax()) * ($maxAmount - $minAmount);

            $totalAmount = $amount * $orderRate;

            $charge = ($totalAmount / 100) * ($orderSide == Status::BUY_SIDE_ORDER ? $coinPair->percent_charge_for_buy : $coinPair->percent_charge_for_sell);

            $order                     = new Order();
            $order->trx                = getTrx();
            $order->user_id            = 1;
            $order->pair_id            = $coinPair->id;
            $order->order_side         = $orderSide;
            $order->order_type         = Status::ORDER_TYPE_MARKET;
            $order->rate               = $orderRate;
            $order->amount             = $amount;
            $order->price              = $coinPair->marketData->price;
            $order->total              = $totalAmount;
            $order->charge             = $charge;
            $order->coin_id            = $coinPair->coin_id;
            $order->market_currency_id = $coinPair->market->currency_id;
            $order->save();

            try {
                event(new EventsOrder($order, $coinPair->symbol));
            } catch (Exception $ex) {

            }
        }
    }

    public function getRandomOrderRate($coinPair, $openOrderRates, $orderSide)
    {
        $botConfig = $this->botConfig;
        $precision = 8;
        $scale     = 10 ** $precision;

        $botOrdersRate = [];

        $this->removeHighPriceOrder($coinPair->coin->rate, $openOrderRates, $orderSide);

        if ($orderSide == Status::BUY_SIDE_ORDER) {
            $buyOrderCount = mt_rand(0, $botConfig->max_buy_order);
            for ($i = 0; $i < $buyOrderCount; $i++) {
                if (count($openOrderRates) && $botConfig->buy_matching_chance > 0 && mt_rand(0, $this->upperLimit) / $this->divisor < $botConfig->buy_matching_chance) {
                    $randomNumber = $openOrderRates[array_rand($openOrderRates)];
                } else {
                    do {
                        $randomPercent = mt_rand($botConfig->buy_min_decrease * $scale, $botConfig->buy_max_decrease * $scale) / $scale;
                        $randomNumber  = decreaseNumberByPercent($coinPair->coin->rate, $randomPercent);
                    } while (in_array($randomNumber, $openOrderRates));
                }
                $botOrdersRate[] = $randomNumber;
            }
        } else {
            $sellOrderCount = mt_rand(0, $botConfig->max_sell_order);

            for ($i = 0; $i < $sellOrderCount; $i++) {
                if (count($openOrderRates) && $botConfig->sell_matching_chance > 0 && mt_rand(0, $this->upperLimit) / $this->divisor < $botConfig->sell_matching_chance) {
                    $randomNumber = $openOrderRates[array_rand($openOrderRates)];
                } else {
                    do {
                        $randomPercent = mt_rand($botConfig->sell_min_increase * $scale, $botConfig->sell_max_increase * $scale) / $scale;
                        $randomNumber  = increaseNumberByPercent($coinPair->coin->rate, $randomPercent);
                    } while (in_array($randomNumber, $openOrderRates));
                }
                $botOrdersRate[] = $randomNumber;
            }
        }

        return $botOrdersRate;
    }

    private function removeHighPriceOrder($rate, $openOrderRates, $orderSide)
    {
        if ($orderSide == Status::BUY_SIDE_ORDER) {
            $openOrderRates = array_filter($openOrderRates, function ($value) use ($rate) {
                $afterIncrease = $rate + $this->botConfig->buy_matching_price / 100 * $rate;
                return $afterIncrease >= $value ? true : false;
            });
        } else {
            $openOrderRates = array_filter($openOrderRates, function ($value) use ($rate) {
                $afterDecrease = $rate - $this->botConfig->sell_matching_price / 100 * $rate;
                return $afterDecrease <= $value ? true : false;
            });
        }
        return $openOrderRates;
    }

}
