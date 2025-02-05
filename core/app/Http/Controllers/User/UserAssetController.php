<?php
 
namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Transaction;
use App\Models\Withdrawal;
use App\Models\WithdrawMethod;
use App\Models\UserAsset;
use App\Models\CryptoDeposit;
use App\Models\Currency;
use App\Models\AssetTrade;
use App\Models\SignalPurchase;
use App\Models\Signals;
use App\Models\Subscriptions;
use App\Models\subscribers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

 
class UserAssetController extends Controller
{
    public function index()
    {
        $pageTitle = 'User Assets';
        //$assets = UserAsset::where('user_id', auth()->id())->get();
        $assets = CryptoDeposit::where('user_id', auth()->id())->get();
        $topAssets = CryptoDeposit::where('user_id', auth()->id())->take(4)->get();
        return view('Template::user.user_assets', compact('pageTitle' ,   'assets', 'topAssets'));
    }

    private function calculateSignalStrength($signal)
    {
        // Implement your logic to calculate signal strength here
        return rand(1, 100); // Example: return a random strength value
    }
 

    public function Market()
    {
        $pageTitle = 'Markets';
        //$assets = UserAsset::where('user_id', auth()->id())->get();
        $assets = CryptoDeposit::where('user_id', auth()->id())->get();
        return view('Template::user.market', compact('pageTitle' ,   'assets'));
        


    }


    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required',
            'proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Handle proof upload
        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('proofs', 'public');
        } else {
            return redirect()->back()->with('error', 'Proof is required!');
        }
        $gen_reference = strtoupper(\Illuminate\Support\Str::random(12));
        $deposit = new CryptoDeposit();
        $deposit->amount = $request->amount;
        $deposit->currency = $request->currency;
        $deposit->user_id = auth()->id();
        $deposit->proof = $proofPath;
        $deposit->reference = $gen_reference;
        $deposit->type = 'crypto';
        $deposit->status = 'pending';
        
 
         
        $deposit->save();

        //return redirect()->back()->with('success', 'Deposit successful!');
        $notify[] = ['success', 'Deposit successful!'];
        return back()->withNotify($notify);
    }

    public function trade()
    {
        $pageTitle = 'Trade';
        
        $currencies = Currency::rankOrdering()->select('name', 'id', 'symbol')->active()->get();
        $Topcurrencies =  Currency::rankOrdering()->select('name', 'id', 'symbol' , 'rate')->active()-> take(5)->get();  // Top 5 Currencies
        $assets = Currency::rankOrdering()->select('name', 'id', 'symbol')->active()->get();
        $userAssets = AssetTrade::where('user_id', auth()->id())->get();
    //user assets
        $userAssets = AssetTrade::where('user_id', auth()->id())->get();
        $assets = CryptoDeposit::where('user_id', auth()->id())->get();
        return view ('Template::user.trade', compact('pageTitle', 'currencies', 'Topcurrencies', 'assets', 'userAssets'));
    } 


    public function Signal()
    {
        $pageTitle = 'Signal';
        $signals = Signals::where('is_active', true)->get();
        // Signal Strength function and query
        $signals = $signals->map(function ($signal) {
            $signal->strength = $this->calculateSignalStrength($signal);
            return $signal;
        });
        return view ('Template::user.signals', compact('signals', 'pageTitle', ));
    }


    public function purchase(Request $request)

{
    $request->validate([
        'signal_id' => 'required|integer',
    ]);

    $signal = Signals::where('id', $request->signal_id)->where('is_active', true)->firstOrFail();
    $user = auth()->user();
    $purchase = new SignalPurchase();
    $purchase->user_id = $user->id;
    $purchase->signal_id = $signal->id;
    $purchase->amount = $signal->amount;
    $purchase->save();
    $user->balance -= $signal->amount;
    $user->save();
    $notify[] = ['success', 'Signal purchased successfully'];
    return back()->withNotify($notify);  

}


//Implement subscribers  function here
 public function subscribers()
    {
      
        $pageTitle = 'Subscriptions';
        $subscriptions = DB::table('subscriptions')->get();
        $subscription_purchased = DB::table('user_subscriptions')->get();
        return view('Template::user.subscribers', compact('pageTitle', 'subscriptions', 'subscription_purchased'));
    }
  
    public function buy(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|integer',
        ]);

        $user = auth()->user();
        $subscriptionId = $request->subscription_id;
        $name = $request->name;
        $amount = $request->amount;
        $duration_days = $request->duration_days;
        $roi = $request->roi;
        $status = 'Active';

        // Check if user has enough balance
        if ($user->balance < $amount) {
            $notify[] = ['error', 'Insufficient balance to purchase subscription'];
            return back()->withNotify($notify);
        }

        // Deduct the amount from user's balance
        $user->balance -= $amount;
        $user->save();

        DB::table('user_subscriptions')->insert([
            'user_id' => $user->id,
            'subscription_id' => $subscriptionId,
            'name' => $name,
            'amount' => $amount,
            'duration_days' => $duration_days,
            'roi' => $roi,
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $notify[] = ['success', 'Subscription purchased successfully'];
        return back()->withNotify($notify);
    }

} 