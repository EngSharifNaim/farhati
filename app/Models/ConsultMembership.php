<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ConsultMembership extends Model
{
    use HasFactory;

    protected $fillable=[
        'consult_id',
        'title',
        'organization',
        'from_date',
        'to_date',
        'country'
    ];


}
