<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscribers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as db;

class SubscriberController extends Controller
{
    public function index()
    {
        $pageTitle = 'Subscribers';
        $subscribers = db::table('user_subscriptions')
            ->join('users', 'users.id', '=', 'user_subscriptions.user_id')
            ->orderBy('user_subscriptions.created_at', 'desc')
            ->paginate(getPaginate());
        return view('admin.subscriber.index', compact('pageTitle', 'subscribers'));
    }

  


    public function sendEmailForm()
    {
        $pageTitle = 'Subscription plan';
         $user_subscription_plans = db::table('subscriptions')->latest()->paginate(getPaginate());
        return view('admin.subscriber.send_email', compact('pageTitle', 'user_subscription_plans'));
    }


    public function remove($id)
    {
        $subscriber = Subscribers::findOrFail($id);
        $subscriber->delete();

        $notify[] = ['success', 'Subscriber deleted successfully'];
        return back()->withNotify($notify);
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'message'      => 'required',
            'subject'      => 'required_if:via,email,push',
            'start'        => 'required|integer|gte:1',
            'batch'        => 'required|integer|gte:1',
            'cooling_time' => 'required|integer|gte:1',
        ]);

        $query = Subscribers::query();

        if (session()->has("SEND_NOTIFICATION_TO_SUBSCRIBER")) {
            $totalSubscriberCount = session('SEND_NOTIFICATION_TO_SUBSCRIBER')['total_subscriber'];
        } else {
            $totalSubscriberCount = (clone $query)->count() - ($request->start - 1);
        }

        if (!$totalSubscriberCount) {
            $notify[] = ['info', "No subscriber found."];
            return back()->withNotify($notify);
        }

        $subscribers = (clone $query)->skip($request->start - 1)->limit($request->batch)->get();

        foreach ($subscribers as $subscriber) {
            $receiverName = explode('@', $subscriber->email)[0];
            $user = [
                'username' => $subscriber->email,
                'email'    => $subscriber->email,
                'fullname' => $receiverName,
            ];
            notify($user, 'DEFAULT', [
                'subject' => $request->subject,
                'message' => $request->message,
            ], ['email'], createLog: false);
        }

        return $this->sessionForNotification($totalSubscriberCount, $request);
    }

    private function sessionForNotification($totalSubscriberCount, $request)
    {
        if (session()->has('SEND_NOTIFICATION_TO_SUBSCRIBER')) {
            $sessionData                = session("SEND_NOTIFICATION_TO_SUBSCRIBER");
            $sessionData['total_sent'] += $sessionData['batch'];
        } else {
            $sessionData                     = $request->except('_token');
            $sessionData['total_sent']       = $request->batch;
            $sessionData['total_subscriber'] = $totalSubscriberCount;
        }

        $sessionData['start'] = $sessionData['total_sent'] + 1;

        if ($sessionData['total_sent'] >= $totalSubscriberCount) {
            session()->forget("SEND_NOTIFICATION_TO_SUBSCRIBER");
            $message = " Email notifications were sent successfully";
            $url     = route("admin.subscriber.send.email");
        } else {
            session()->put('SEND_NOTIFICATION_TO_SUBSCRIBER', $sessionData);
            $message = $sessionData['total_sent'] . " Email notifications were sent successfully";
            $url     = route("admin.subscriber.send.email") . "?email_sent=yes";
        }
        $notify[] = ['success', $message];
        return redirect($url)->withNotify($notify);
    }

  

 
    public function update(Request $request)
    {
        $request->validate([
            'id'            => 'required',
            'name'          => 'required|string|max:255',
            'amount'        => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'roi'           => 'required|numeric|min:0',
            'profit'        => 'required|numeric|min:0',
            'status'        => 'required|string|in:active,expired',
        ]);

        $updated = db::table('user_subscriptions')
            ->where('id', $request->id)
            ->update([
                'name'          => $request->name,
                'amount'        => $request->amount,
                'duration_days' => $request->duration_days,
                'roi'           => $request->roi,
                'profit'        => $request->profit,
                'status'        => $request->status,
            ]);

        if (!$updated) {
           $notify[] = ['error', 'Subscriber update failed.'];
              return redirect()->back()->withNotify($notify);
        }

        $notify[] = ['success', 'Subscriber updated successfully.'];
        return redirect()->back()->withNotify($notify);
    }
 
 
    public function storePlan(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
         
        ]);

        db::table('subscriptions')->insert([
            'name'          => $request->name,
            'minimum_amount' => $request->minimum_amount,
            'maximum_amount' => $request->maximum_amount,
            'duration_days' => $request->duration_days,
            'roi_percentage'  => $request->roi_percentage,
       
        ]);

        $notify[] = ['success', 'Subscriber added successfully.'];
        return redirect()->back()->withNotify($notify);
    }

    public function updatePlan(Request $request)
    {
        $request->validate([
            'id'            => 'required',
        ]);

        $updated = db::table('subscriptions')
            ->where('id', $request->id)
            ->update([
                'name'          => $request->name,
                'minimum_amount'        => $request->minimum_amount,
                'maximum_amount'        => $request->maximum_amount,
                'duration_days' => $request->duration_days,
                'roi_percentage'           => $request->roi_percentage,
            ]);

        if (!$updated) {
           $notify[] = ['error', 'Subscriber update failed.'];
              return redirect()->back()->withNotify($notify);
        }

        $notify[] = ['success', 'Subscriber updated successfully.'];
        return redirect()->back()->withNotify($notify);
    }
   

}

