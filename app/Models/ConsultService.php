<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultService extends Model
{
    use HasFactory;
    public function type()
    {
        return $this->belongsTo(ConsultCategory::class,'consult_type','id');
    }
}
