<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Models\Gateway;
use App\Models\UserWallet;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\CryptoDeposit;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class CryptoDepositController extends Controller
{

    public function cryptoDeposit()

    {

        $pageTitle = 'Crypto Deposit';
        $user      = auth()->user();
        // select all the gateways
        $gateways = Gateway::where('status', Status::ENABLE)->get();
        $cryptoDeposits = CryptoDeposit::where('user_id', $user->id)->get();
        return view('Template::user.crypto_deposit', compact('pageTitle', 'gateways', 'cryptoDeposits'));
    }

 
    public function store(Request $request)
    {
        $request->validate([
            'fiat_amount' => 'required_if:type,fiat_to_crypto',
            'crypto_amount' => 'required_if:type,crypto_to_fiat',
            
        ]);
    
        $user = auth()->user();
        $conversionType = $request->input('type'); // Use input method to safely access the type

        if (!$conversionType) {
            $notify[] = ['error', 'Invalid conversion type!'];
            return back()->withNotify($notify);
        }
        
        if ($conversionType === 'fiat_to_crypto') {
            // Check if user has enough fiat balance
            if ($user->balance < $request->fiat_amount) {
                $notify[] = ['error', 'Insufficient fiat balance!'];
                return back()->withNotify($notify);
            }
            
            // Deduct from user's fiat balance
            $user->balance -= $request->fiat_amount;
            $user->save();
            
            // Create a crypto deposit record
            $deposit = new CryptoDeposit();
            $deposit->amount = $request->crypto_amount;
            $deposit->currency = $request->currency;
            $deposit->user_id = $user->id; 
            $deposit->proof = "Conversion";
            $deposit->reference = strtoupper(\Illuminate\Support\Str::random(12));
            $deposit->type = 'fiat_to_crypto';
            $deposit->status = '2'; // Assuming 2 is "completed" status
            $deposit->save();
            
            // Update user's crypto wallet
            $this->updateUserWallet($user->id, $request->currency, $request->crypto_amount);
            
            $notify[] = ['success', 'Successfully converted fiat to crypto!'];
            return back()->withNotify($notify);
        } 

       
        elseif ($conversionType === 'crypto_to_fiat') {
            // Check if user has enough crypto balance
            $wallet = UserWallet::where('user_id', $user->id)
                               ->where('currency', $request->currency)
                               ->first();
                               
            if (!$wallet || $wallet->balance < $request->crypto_amount) {
                $notify[] = ['error', 'Insufficient crypto balance!'];
                return back()->withNotify($notify);
            }
            
            // Deduct from user's crypto wallet
            $wallet->balance -= $request->crypto_amount;
            $wallet->save();
            
            // Update user's fiat balance
            $fiatAmount = (float)$request->fiat_amount; // Cast to float
            $user->balance += $fiatAmount;
            $user->save();
             
            // Don't create a crypto deposit record for crypto_to_fiat
            
            $notify[] = ['success', 'Successfully converted crypto to fiat!'];
            return back()->withNotify($notify);
        }
       

        elseif ($conversionType === 'crypto') {
            // Create a crypto deposit record
            $deposit = new CryptoDeposit();
            $deposit->amount = $request->amount;
            $deposit->currency = $request->currency;
            $deposit->user_id = $user->id;
            $deposit->proof = $request->proof;
            $deposit->reference = strtoupper(\Illuminate\Support\Str::random(12));
            $deposit->type = 'crypto';
            $deposit->status = '1'; // Assuming 1 is "pending" status
            $deposit->save();
            
            // Update user's crypto wallet
            $this->updateUserWallet($user->id, $request->currency, $request->amount);
            
            $notify[] = ['success', 'Crypto deposit initiated successfully!'];
            return back()->withNotify($notify);
        }
        
        $notify[] = ['error', 'Invalid conversion type!'];
        return back()->withNotify($notify);
    }
    
    private function updateUserWallet($userId, $currency, $amount)
    {
        $wallet = UserWallet::firstOrNew(['user_id' => $userId, 'currency' => $currency]);
        $wallet->balance = ($wallet->balance ?? 0) + $amount;
        $wallet->save();
    }
  
}
