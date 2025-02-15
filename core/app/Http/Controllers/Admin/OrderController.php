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
        $userAssets = DB::table('asset_trades')
            ->join('users', 'asset_trades.user_id', '=', 'users.id')
            ->select('asset_trades.*', 'users.username as user_name')
            ->orderBy('asset_trades.id', 'desc')
            ->paginate(getPaginate());
        return view('admin.order.list', compact('pageTitle', 'userAssets'));
    }

    public function history()
    {
        $pageTitle = "Complete Trades";
        $userAssets = DB::table('asset_trades')
            ->join('users', 'asset_trades.user_id', '=', 'users.id')
            ->select('asset_trades.*', 'users.username as user_name')
            ->orderBy('asset_trades.id', 'desc')
            ->paginate(getPaginate());
        return view('admin.order.trade_history', compact('pageTitle', 'userAssets'));
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


    public function tradeupdate(Request $request, $id)
    {
        $trade = DB::table('asset_trades')->where('id', $id)->first();
        
        if (!$trade) {
            abort(404, 'Trade not found.');
        }

        DB::table('asset_trades')->where('id', $id)->update([
            'amount' => $request->input('amount'),
            'profit' => $request->input('profit'),
            'loss' => $request->input('loss'),
            'status' => $request->input('status'),
        ]);

        $notify[] = ['success', 'Trade updated successfully.'];
        return back()->withNotify($notify);
    }

}
