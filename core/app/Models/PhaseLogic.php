<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhaseLogic extends Model
{
    use HasFactory;
    
    public function logicBox()
    {
        return $this->belongsTo(LogicBox::class);
    }

}
