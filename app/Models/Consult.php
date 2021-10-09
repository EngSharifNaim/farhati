<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;


class Consult extends Model
{
    use HasFactory,HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'country',
        'gender',
        'photo',
        'licence_type',
        'location_long',
        'location_lat',
        'address',
        'notes',
        'experience_years',
        'licence_type',
        'licence_id',
        'licence_from',
        'licence_to',
        'licence_country',
        'fcm_token'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function reviews()
    {
        return $this->hasMany(ConsultReview::class,'consult_id','id');
    }

    public function services()
    {
        return $this->hasMany(ConsultService::class,'consult_id','id');
    }
}
