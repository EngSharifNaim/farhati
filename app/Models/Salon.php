<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salon extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'mobile',
        'email',
        'user_id',
        'location_long',
        'location_lat'
    ];


    public function products()
    {
        return $this->hasMany(Product::class,'salon_id','id');
    }

    public function times()
    {
        return $this->hasMany(SalonTime::class,'salon_id','id');
    }
    public function services()
    {
        return $this->hasMany(SalonService::class,'salon_id','id');
    }


}
