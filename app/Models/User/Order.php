<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function order_categories(){
        return $this->hasMany(OrderService::class,'order_id','id')->with('category');
    }
    public function event(){
        return $this->belongsTo(Event::class,'event_id','id');
    }
}
