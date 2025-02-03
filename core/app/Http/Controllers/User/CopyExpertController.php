<?php

namespace App\Http\Controllers\User;
use App\Constants\Status;
use App\Models\Gateway;
use App\Models\CopyExpert;
use App\Http\Controllers\Controller;
use App\Models\CryptoDeposit;
use Illuminate\Http\Request;

class CopyExpertController extends Controller
{
   
    public function CopyExpert()

    { 
        
        $pageTitle = 'Copy Expert';
        $user      = auth()->user() ;

          // select all    copy experts
            $copy_experts = CopyExpert::get();
            $gateways = Gateway::where('status', Status::ENABLE)->get();
              // check if user has any crypto deposits where type is expert feee
            $hasExpertFeeDeposit = CryptoDeposit::where('user_id', $user->id)->where('type', 'expert_fee')->get();
        return view('Template::user.copy_expert', compact('pageTitle' , 'gateways' , 'copy_experts', 'hasExpertFeeDeposit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|max:3',
        ]);

        //proof upload 
        
        $deposit = new CryptoDeposit();
        $deposit->amount = $request->amount;
        $deposit->currency = $request->currency;
        $deposit->user_id = auth()->id();
        $deposit->save();

        return redirect()->back()->with('success', 'Deposit successful!');
    }
}