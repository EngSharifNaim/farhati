<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qa extends Model
{
    use HasFactory;

    public function consult()
    {
        return $this->belongsTo(Consult::class,'consult_id','id')
            ->select('id','name','photo');
    }
}
