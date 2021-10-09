<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultEducation extends Model
{
    use HasFactory;

    protected $fillable=[
        'consult_id',
        'university',
        'collage',
        'from_date',
        'to_date',
        'average'
    ];
}
