<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    public function consult()
    {
        return $this->belongsTo(Consult::class,'consult_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')
            ->select('id','name');
    }

    public function service()
    {
        return $this->belongsTo(ConsultService::class,'service_id','id')
            ->select('id','name','price','time');
    }
    public function category()
    {
        return $this->belongsTo(ConsultCategory::class,'consult_category_id','id')
            ->select('id','name');
    }

    public function files()
    {
        return $this->hasMany(AppointmentFile::class,'appointment_id','id');
    }

}
