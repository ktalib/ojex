<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\CoinPair;
use App\Models\Currency;
use App\Models\DeviceToken;
use App\Models\FavoritePair;
use App\Models\Form;
use App\Models\Order;
use App\Models\PlanHistory;
use App\Models\Trade;
use App\Models\AssetTrade;
use App\Models\CryptoDeposit;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Asset;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function home()
    {
        $pageTitle = 'My Dashboard';
        $user      = auth()->user();

        $pairs = CoinPair::whereHas('marketData')
            ->select('id', 'market_id', 'coin_id')
            ->with('market:id,name,currency_id', 'coin:id,name,symbol', 'market.currency:id,name,symbol', 'marketData:id,pair_id,price,percent_change_1h,percent_change_24h,html_classes,market_cap')
            ->get();

        $currencies = Currency::rankOrdering()->select('name', 'id', 'symbol')->active()->get();
        $Topcurrencies = CryptoDeposit::where('user_id', $user->id)->orderBy('id', 'DESC')->take(5)->get();
        
        // Top 5 Currencies
        $assets = Currency::rankOrdering()->select('name', 'id', 'symbol')->active()->get();

    //user assets
    $userAssets = AssetTrade::where('user_id', $user->id)->get();

        $order                     = Order::where('user_id', $user->id);
        $widget['open_order']      = (clone $order)->open()->count();
        $widget['completed_order'] = (clone $order)->completed()->count();
        $widget['canceled_order']  = (clone $order)->canceled()->count();
        $widget['total_trade']     = Trade::where('trader_id', $user->id)->count();
        
        $recentOrders       = $order->with('pair.coin')->orderBy('id', 'DESC')->take(10)->get();
        $recentTransactions = Transaction::where('user_id', $user->id)->orderBy('id', 'DESC')->take(10)->get();
        $withdraws = Withdrawal::where('user_id', auth()->id())->where('status', '!=', Status::PAYMENT_INITIATE);
        
        $totalWithdraw = $withdraws->sum('amount');
        return view('Template::user.dashboard', compact('pageTitle', 'user', 'pairs', 'currencies', 'widget', 'recentOrders', 'recentTransactions' , 'Topcurrencies'  , 'assets', 'userAssets', 'totalWithdraw'));
    }

    public function progress()
    {
        $pageTitle = 'My Plan Progress';
        $user      = auth()->user();

        $planHistory = PlanHistory::with('userPhases', 'plan.planPhases.phaseLogics.logicBox')->where('user_id', $user->id);
        $planHistory = request()->plan_history ? $planHistory->find(request()->plan_history) : $planHistory->orderBy('id', 'desc')->first();

        $planHistories = PlanHistory::where('user_id', $user->id);

        if ($planHistory->status == Status::PLAN_HISTORY_RUNNING) {
            $planHistories->where('id', '!=', $planHistory->id);
        }

        $planHistories = $planHistories->with('plan')->orderBy('id', 'desc')->get();

        return view('Template::user.plan.progress', compact('pageTitle', 'planHistory', 'planHistories'));
    }

    public function addToFavorite($symbol)
    {
        $pair = CoinPair::activeMarket()->activeCoin()->where('symbol', $symbol)->first();
        if (!$pair) {
            return response()->json([
                'success' => false,
                'message' => "Pair not found",
            ]);
        }

        $favoritePair = FavoritePair::where('user_id', auth()->id())->where('pair_id', $pair->id)->first();

        if ($favoritePair) {
            $favoritePair->delete();
            return response()->json([
                'success' => true,
                'deleted' => true,
                'message' => "This pair removed to your favorite list",
            ]);
        }

        $favoritePair          = new FavoritePair();
        $favoritePair->user_id = auth()->id();
        $favoritePair->pair_id = $pair->id;
        $favoritePair->save();

        return response()->json([
            'success' => true,
            'message' => "Pair added to favorite list",
        ]);
    }

    public function depositHistory(Request $request)
    {
        $pageTitle = 'Payment History';
        $deposits  = auth()->user()->deposits()->searchable(['trx'])->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.deposit_history', compact('pageTitle', 'deposits'));
    }

    public function show2faForm()
    {
        $ga        = new GoogleAuthenticator();
        $user      = auth()->user();
        $secret    = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . gs('site_name'), $secret);
        $pageTitle = '2FA Security';
        return view('Template::user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'key'  => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts  = Status::ENABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $user     = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts  = Status::DISABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function transactions()
    {
        $pageTitle = 'Transactions';
        $remarks   = Transaction::distinct('remark')->orderBy('remark')->get('remark');

        $transactions = Transaction::where('user_id', auth()->id())->searchable(['trx'])->filter(['trx_type', 'remark', 'wallet.currency:symbol', 'wallet:wallet_type'])->with('wallet.currency')->orderBy('id', 'desc')->paginate(getPaginate());

        $currencies = Currency::active()->rankOrdering()->get();

        return view('Template::user.transactions', compact('pageTitle', 'transactions', 'remarks', 'currencies'));
    }

    public function kycForm()
    {
        if (auth()->user()->kv == Status::KYC_PENDING) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }
        if (auth()->user()->kv == Status::KYC_VERIFIED) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }
        $pageTitle = 'KYC Form';
        $form      = Form::where('act', 'kyc')->first();
        return view('Template::user.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData()
    {
        $user      = auth()->user();
        $pageTitle = 'KYC Data';
        abort_if($user->kv == Status::VERIFIED, 403);
        return view('Template::user.kyc.info', compact('pageTitle', 'user'));
    }

    public function kycSubmit(Request $request)
    {
        $form           = Form::where('act', 'kyc')->firstOrFail();
        $formData       = $form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $user = auth()->user();
        foreach (@$user->kyc_data ?? [] as $kycData) {
            if ($kycData->type == 'file') {
                fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
            }
        }
        $userData                   = $formProcessor->processFormData($request, $formData);
        $user->kyc_data             = $userData;
        $user->kyc_rejection_reason = null;
        $user->kv                   = Status::KYC_PENDING;
        $user->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('user.home')->withNotify($notify);

    }

    public function userData()
    {
        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $pageTitle  = 'User Data';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('Template::user.user_data', compact('pageTitle', 'user', 'countries', 'mobileCode'));
    }

    public function userDataSubmit(Request $request)
    {

        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $countryData  = (array) json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));

        $request->validate([
            'country_code' => 'required|in:' . $countryCodes,
            'country'      => 'required|in:' . $countries,
            'mobile_code'  => 'required|in:' . $mobileCodes,
            'username'     => 'required|unique:users|min:4',
            'mobile'       => ['required', Rule::unique('users')->where('dial_code', $request->mobile_code)],
        ]);

     

        $user->country_code = $request->country_code;
        $user->mobile       = $request->mobile;
        $user->username     = $request->username;

        $user->address      = $request->address;
        $user->city         = $request->city;
        $user->state        = $request->state;
        $user->zip          = $request->zip;
        $user->country_name = @$request->country;
        $user->dial_code    = $request->mobile_code;

        $user->profile_complete = Status::YES;
        $user->save();

        return to_route('user.home');
    }

    public function addDeviceToken(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return ['success' => false, 'errors' => $validator->errors()->all()];
        }

        $deviceToken = DeviceToken::where('token', $request->token)->first();

        if ($deviceToken) {
            return ['success' => true, 'message' => 'Already exists'];
        }

        $deviceToken          = new DeviceToken();
        $deviceToken->user_id = auth()->user()->id;
        $deviceToken->token   = $request->token;
        $deviceToken->is_app  = Status::NO;
        $deviceToken->save();

        return ['success' => true, 'message' => 'Token saved successfully'];
    }

    public function downloadAttachment($fileHash)
    {
        $filePath  = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title     = slug(gs('site_name')) . '- attachments.' . $extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|string',
            'trade_type' => 'required|string',
            'amount' => 'required|numeric',
            'assets' => 'required|string',
            'duration' => 'required|string',
            'loss' => 'nullable|numeric',
            'profit' => 'nullable|numeric'
        ]);
    $user = auth()->user();
    
    // Check if the user has enough balance for the trade in the user_wallets table
    $wallet = DB::table('user_wallets')
        ->where('user_id', $user->id)
        ->where('currency', $validated['assets'])
        ->first();
    
    if (!$wallet || $wallet->balance < $validated['amount']) {
        return back()->withErrors(['amount' => 'Insufficient balance for this trade.']);
    }
    
        // Create the trade
        AssetTrade::create([
            'user_id' => $user->id,
            'action' => $validated['action'],
            'trade_type' => $validated['trade_type'],
            'amount' => $validated['amount'],
            'assets' => $validated['assets'],
            'duration' => $validated['duration'],
            'loss' => $validated['loss'],
            'profit' => $validated['profit'],
            'status' => 'open' // Assuming the initial status is 'open'
        ]);
    
        $notify[] = ['success', 'Trade created successfully.'];
        return back()->withNotify($notify);
    }


    

}