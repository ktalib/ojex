<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
 

class StakingController extends Controller

{

    public function index()
    {
      
        $pageTitle = 'Staking';
        $stakes = DB::table('stakes')->get();

          $getUserStakes = DB::table('users_stakes')
              ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('Template::user.staking', compact('pageTitle' ,  'getUserStakes', 'stakes'));
    }


     

    
    
    

    public function store(Request $request)
    {
        $request->validate([
            'crypto_type' => 'required',
            'amount' => 'required',
            'duration' => 'required',
            'roi' => 'required',
        ]);

        DB::table('users_stakes')->insert([
            'user_id' => auth()->id(),
            'crypto_type' => $request->crypto_type,
            'amount' => $request->amount,
            'duration' => $request->duration,
            'roi' => $request->roi,
        ]);

        //if user balance is less than the amount
            $user = auth()->user(); 
            $user->balance -= $request->amount;
            $user->save();
    if($user->balance < $request->amount){
        $notify[] = ['error', 'Insufficient balance'];
        return back()->withNotify($notify);
    }

        
        $notify[] = ['success', 'Subscription purchased successfully'];
        return back()->withNotify($notify);
    
  }
 }
