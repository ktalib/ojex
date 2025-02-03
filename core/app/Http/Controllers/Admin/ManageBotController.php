<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BotConfig;
use Illuminate\Http\Request;

class ManageBotController extends Controller
{
    public function config()
    {
        $pageTitle = 'Manage Bot';
        $botConfig = BotConfig::first();
        return view('admin.bot.config', compact('pageTitle', 'botConfig'));
    }

    public function saveConfig(Request $request)
    {
        $request->validate([
            'max_buy_order'           => 'required|integer',
            'buy_min_decrease'        => 'required|integer|min:0|max:100',
            'buy_max_decrease'        => 'required|integer|gt:buy_min_decrease|max:99',
            'buy_order_amount_range'  => 'required|numeric|min:0|max:100',
            'buy_matching_chance'     => 'required|numeric|min:0|max:100',
            'buy_matching_price'      => 'required|numeric|min:0',
            'buy_matching_with_bot'   => 'required|in:0,1',
            'buy_order_remain_hours'  => 'required|integer|min:0',
            'max_sell_order'          => 'required|integer',
            'sell_min_increase'       => 'required|integer|min:0',
            'sell_max_increase'       => 'required|integer|gt:sell_min_increase',
            'sell_order_amount_range' => 'required|numeric|min:0|max:100',
            'sell_matching_chance'    => 'required|numeric|min:0|max:100',
            'sell_matching_price'     => 'required|numeric|min:0',
            'sell_matching_with_bot'  => 'required|in:0,1',
            'sell_order_remain_hours' => 'required|integer|min:0',
        ]);

        $botConfig                          = BotConfig::first();
        $botConfig->max_buy_order           = $request->max_buy_order;
        $botConfig->buy_min_decrease        = $request->buy_min_decrease;
        $botConfig->buy_max_decrease        = $request->buy_max_decrease;
        $botConfig->buy_order_amount_range  = $request->buy_order_amount_range;
        $botConfig->buy_matching_chance     = $request->buy_matching_chance;
        $botConfig->buy_matching_price      = $request->buy_matching_price;
        $botConfig->buy_matching_with_bot   = $request->buy_matching_with_bot;
        $botConfig->buy_order_remain_hours  = $request->buy_order_remain_hours;
        $botConfig->max_sell_order          = $request->max_sell_order;
        $botConfig->sell_min_increase       = $request->sell_min_increase;
        $botConfig->sell_max_increase       = $request->sell_max_increase;
        $botConfig->sell_order_amount_range = $request->sell_order_amount_range;
        $botConfig->sell_matching_chance    = $request->sell_matching_chance;
        $botConfig->sell_matching_with_bot  = $request->sell_matching_with_bot;
        $botConfig->sell_matching_price     = $request->sell_matching_price;
        $botConfig->sell_order_remain_hours = $request->sell_order_remain_hours;
        $botConfig->save();

        $notify[] = ['success', 'Bot configuration updated successfully'];
        return back()->withNotify($notify);
    }

}
