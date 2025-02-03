<?php

namespace App\Http\Controllers\User;
use App\Constants\Status;
use App\Models\Gateway;
use App\Http\Controllers\Controller;
use App\Models\CryptoDeposit;
use Illuminate\Http\Request;

class CryptoDepositController extends Controller
{
   
    public function cryptoDeposit()

    { 
        
        $pageTitle = 'Crypto Deposit';
        $user      = auth()->user() ;

          // select all the gateways
            $gateways = Gateway::where('status', Status::ENABLE)->get();
         

            $cryptoDeposits = CryptoDeposit::where('user_id', $user->id)->get();
        return view('Template::user.crypto_deposit', compact('pageTitle' , 'gateways' , 'cryptoDeposits'));
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
}