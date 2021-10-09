<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalonService extends Model
{
    use HasFactory;

    public function service()
    {
        return $this->belongsTo(Service::class,'service_id','id');
    }
    public function images()
    {
        return $this->hasMany(ServiceImage::class,'service_id','id');
    }

}
