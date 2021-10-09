<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $fillable = [
        'area',
        'account_id',
        'machine_id',
        'customer_name',
        'last_read',
        'amount',
        'status',
        'type'
    ];
}
