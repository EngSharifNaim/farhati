<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultExperience extends Model
{
    use HasFactory;
    protected $fillable=[
        'consult_id',
        'company_name',
        'title',
        'company_country',
        'from_date',
        'to_date'
    ];

}
