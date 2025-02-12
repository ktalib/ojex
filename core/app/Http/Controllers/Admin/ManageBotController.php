<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BotConfig;
use finfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageBotController extends Controller
{
    public function config()
    {
        $pageTitle = 'Manage Staking';
        $botConfig = BotConfig::first();
        $stakings = db::table('stakes')->get();
        $userStakings = db::table('users_stakes')->get();
        return view('admin.bot.config', compact('pageTitle', 'botConfig', 'stakings' , 'userStakings'));
    }

    public function userStaking()
    {
        $pageTitle = 'Users  Staking';
 
        $userStakings = db::table('users_stakes')->get();
        return view('admin.bot.user_stakes', compact('pageTitle',    'userStakings'));
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

  // function to  add staking  `id`, `name`, `crypto_type`, `minimum`, `maximum`, `amount`, `duration`, `roi`, `status`,
    public function addStaking(Request $request)
    {
        $request->validate([
             'name' => 'required|string',
             'crypto_type' => 'required|string',
                'minimum' => 'required|numeric|min:0',
                'maximum' => 'required|numeric|min:0',
                'amount' => 'required|numeric|min:0',
                'duration' => 'required|integer|min:0',
                'roi' => 'required|numeric|min:0',
                 
        ]);

        $data = [
            'name' => $request->name,
            'crypto_type' => $request->crypto_type,
            'minimum' => $request->minimum,
            'maximum' => $request->maximum,
            'amount' => $request->amount,
            'duration' => $request->duration,
            'roi' => $request->roi,
            'status' => 'active',
        ];

        DB::table('stakes')->insert($data);
        $notify[] = ['success', 'Staking added successfully'];
        return back()->withNotify($notify);
    }

    // function to update staking
    public function updateStaking(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required|string',
            'crypto_type' => 'required|string',
            'minimum' => 'required|numeric|min:0',
            'maximum' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:0',
            'roi' => 'required|numeric|min:0',
        ]);

        $data = [
            'name' => $request->name,
            'crypto_type' => $request->crypto_type,
            'minimum' => $request->minimum,
            'maximum' => $request->maximum,
            'amount' => $request->amount,
            'duration' => $request->duration,
            'roi' => $request->roi,
        ];
        DB::table('stakes')->where('id', $request->id)->update($data);
        $notify[] = ['success', 'Staking updated successfully'];
        return back()->withNotify($notify);
    }

    // function to delete staking
    public function deleteStaking(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        DB::table('stakes')->where('id', $request->id)->delete();
        $notify[] = ['success', 'Staking deleted successfully'];
        return back()->withNotify($notify);
    }

public function userStakingUpdate (Request $request)
 {
      $request->validate([
            'id' => 'required',
            'name' => 'required|string',
            'crypto_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:0',
            'roi' => 'required|numeric|min:0',
            'status' => 'required',
      ]);

      $data = [
            'user_id' => $request->id,
            'name' => $request->name,
            'crypto_type' => $request->crypto_type,
            'amount' => $request->amount,
            'duration' => $request->duration,
            'roi' => $request->roi,
            'status' => $request->status,
      ];
      DB::table('users_stakes')->where('id', $request->id)->update($data);
      $notify[] = ['success', 'User staking updated successfully'];
      return back()->withNotify($notify);
 }

    public function userStakingDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        DB::table('users_stakes')->where('id', $request->id)->delete();
        $notify[] = ['success', 'User staking deleted successfully'];
        return back()->withNotify($notify);
    }

  




}
