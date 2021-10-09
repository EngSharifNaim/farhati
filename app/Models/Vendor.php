<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'country_id',
        'location_long',
        'location_lat',
        'address',
        'logo_url',
        'user_id',
        'description'
    ];

    public function services()
    {
        return $this->hasMany(VendorCategory::class,'vendor_id','id')
            ->select('id','vendor_id','category_id');
    }

    public function team()
    {
        return $this->hasMany(Team::class,'vendor_id','id');
    }

    public function reviews()
    {
        return $this->hasMany(VendorReview::class,'vendor_id','id');
    }
    public function products()
    {
        return $this->hasMany(Product::class,'salon_id','id')
            ->select('id','image_url','salon_id');
    }


}
