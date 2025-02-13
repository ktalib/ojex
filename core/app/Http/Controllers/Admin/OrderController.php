<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function open()
    {
        $pageTitle = "Open Trades";
        $userAssets    =  DB::table('asset_trades')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.order.list', compact('pageTitle', 'userAssets'));
    }

    public function history($userId = 0)
    {
        $pageTitle = "Complete Trades";
        $userAssets    =  DB::table('asset_trades')->orderBy('id', 'desc')->paginate(getPaginate());

        return view('admin.order.list', compact('pageTitle', 'userAssets'));
    }

    protected function orderData($scope = null, $userId = 0)
    {
        $query = Order::filter(['order_side', 'status'])->searchable(['pair:symbol', 'pair.coin:symbol', 'pair.market.currency:symbol'])->with('pair', 'pair.coin', 'pair.market.currency');
        if ($scope) {
            $query->$scope();
        }
        if ($userId) {
            $query->where('user_id', $userId);
        }
        return $query->orderBy('id', 'desc')->paginate(getPaginate());
    }

    public function tradeHistory($userId = 0)
    {
        $pageTitle = "Trade History";
        $trades    = Trade::filter(['trade_side'])->searchable(['order.pair:symbol', 'order.pair.coin:symbol', 'order.pair.market.currency:symbol'])->with('order.pair', 'order.pair.coin', 'order.pair.market.currency');
        
        if ($userId) {
            $trades->where('trader_id', $userId);
        }

        $trades = $trades->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.order.trade_history', compact('pageTitle', 'trades'));
    }
}
