<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;

class Product extends Model
{
    use HasFactory;

    protected  $fillable = [
        'name',
        'description',
        'price',
        'image_url',
        'vendor_id',
        'category_id'
    ];
    public function vendor()
    {
        return $this->belongsTo(Vendor::class,'vendor_id','id')->get();
    }
}
