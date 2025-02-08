<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateUserSubscriptions extends Command
{
    protected $signature = 'subscriptions:update';
    protected $description = 'Update user subscriptions for daily ROI and handle expiration';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $subscriptions = db::table('user_subscriptions')->where('status', 'Active')->get();



        foreach ($subscriptions as $subscription) {
            $expirationDate = Carbon::parse($subscription->created_at)->addDays($subscription->duration_days);
            $daysRemaining = max(0, floor(Carbon::now()->diffInDays($expirationDate, false)));

            if ($daysRemaining <= 0) {
                $subscription->status = 'Expired';
                $subscription->save();
                continue;
            }

            $lastUpdated = Carbon::parse($subscription->updated_at);
            if ($lastUpdated->isToday()) {
                continue;
            }

            $dailyRoi = ($subscription->amount * $subscription->roi) / 100 / $subscription->duration_days;
            $subscription->profit += $dailyRoi;
            $subscription->save();

            $user = $subscription->user;
            $user->profit += $dailyRoi;
            $user->save();
        }

        $this->info('User subscriptions updated successfully.');
    }
}