<?php

namespace App\Http\Controllers\User;
use App\Constants\Status;
use App\Models\Gateway;
use App\Models\CopyExpert;
use App\Http\Controllers\Controller;
use App\Models\CryptoDeposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CopyExpertController extends Controller
{
   
    public function CopyExpert()

    { 
        
        $pageTitle = 'Copy Expert';
        $user      = auth()->user() ;

          // select all    copy experts
            $copy_experts = CopyExpert::get();
            $gateways = Gateway::where('status', Status::ENABLE)->get();
            $getCopyExpertFee = CryptoDeposit::where('user_id', $user->id)->where('type', 'expert_fee')->get();
            // $getCopyExpert = CryptoDeposit::where('user_id', $user->id)->where('type', 'expert_fee')->get();
        return view('Template::user.copy_expert', compact('pageTitle' , 'gateways' , 'copy_experts', 'getCopyExpertFee'));
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

    public function storeCopy(Request $request)
    {
       $request->validate([
           'expert_id' => 'required'
       ]);
       
       DB::table('copy')->insert([
           'user_id' => auth()->user()->id,
           'expert_id' => $request->expert_id,
           'created_at' => now(),
           'updated_at' => now()
       ]);

       $notify[] = ['success', 'Copied successful!'];
       return back()->withNotify($notify);
    }

   
}