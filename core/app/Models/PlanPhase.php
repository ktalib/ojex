<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanPhase extends Model
{
    public function phaseLogics()
    {
        return $this->hasMany(PhaseLogic::class);
    }
}
