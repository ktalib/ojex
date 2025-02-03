<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanHistory extends Model
{
    use HasFactory;

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userPhases()
    {
        return $this->hasMany(UserPhase::class);
    }

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function renew($user)
    {
        $this->expires_at = $this->expires_at->addDays($this->plan->duration);
        $this->save();

        notify($user, 'PLAN_RENEWED', [
            'plan_name'  => $this->plan->name,
            'price'      => showAmount($this->price),
            'duration'   => $this->duration,
            'expires_at' => showDateTime($this->expires_at),
        ]);
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->status == Status::PLAN_HISTORY_RUNNING) {
                $html = '<span class="badge badge--success">' . trans('Running') . '</span>';
            } elseif ($this->status == Status::PLAN_HISTORY_FAILED) {
                $html = '<span class="badge badge--danger">' . trans('Failed') . '</span>';
            } elseif ($this->status == Status::PLAN_HISTORY_EXPIRED) {
                $html = '<span class="badge badge--secondary">' . trans('Expired') . '</span>';
            } else {
                $html = '<span class="badge badge--info">' . trans('Completed') . '</span>';
            }
            return $html;
        });
    }

    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now())->where('status', Status::PLAN_HISTORY_RUNNING);
    }

}
