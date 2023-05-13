<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_item_id',
        'order_id',

        'qty',
        'unit_price',
    ];

    public function getTotalPriceAttribute()
    {
        $totalPrice = $this->qty * $this->unit_price;
        return bcdiv($totalPrice, 1, 2) ;
    }
    public function MenuItem()
    {
        return $this->hasOne(MenuItem::class,'id','menu_item_id');
    }

}
