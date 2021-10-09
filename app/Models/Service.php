<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public function images()
    {
        return $this->hasMany(ServiceImage::class,'service_id','id');
    }

}
