<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultCourse extends Model
{
    use HasFactory;

    protected $fillable=[
        'consult_id',
        'centre_name',
        'course_name',
        'course_hours',
        'from_date',
        'to_date'
    ];

}
