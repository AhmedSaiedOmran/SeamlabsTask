<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DineInOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'table_number',
        'waiter_name',

        'service_charge',
    ];

}
