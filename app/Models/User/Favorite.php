<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function vendor(){
        return$this->belongsTo(Vendor::class,'vendor_id','id');
    }
}
