<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',

        'dine_in_order_id',
        'delivery_order_id',

        'total',
    ];

    const TYPE_dine_in = 'dine-in';
    const TYPE_delivery = 'delivery';
    const TYPE_takeaway = 'takeaway';


    //Relations
    public function OrderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
    public function DineIn()
    {
        return $this->hasOne(DineInOrder::class,'id','dine_in_order_id');
    }
    public function Delivery()
    {
        return $this->hasOne(DeliveryOrder::class,'id','delivery_order_id');
    }


    // Getters & Attributes
    public static function getTypesOfOrder(){
        return [self::TYPE_dine_in,self::TYPE_delivery, self::TYPE_takeaway];
    }
    public function JsonData(){
        $data['id'] = $this->id;
        $data['type'] = $this->type;

        $data['items'] = [];

        foreach ($this->OrderItems as $OrderItem) {
            $itemData['name'] = $OrderItem->MenuItem->name;
            $itemData['qty'] = $OrderItem->qty;
            $itemData['unit_price'] = $OrderItem->unit_price;
            $itemData['total_price'] = $OrderItem->total_price;

            array_push($data['items'], $itemData);
        }

        if ($this->type == Order::TYPE_dine_in) {
            $dineInData['table_number'] = $this->DineIn->table_number;
            $dineInData['waiter_name'] = $this->DineIn->waiter_name;
            $dineInData['service_charge'] = $this->DineIn->service_charge;

            $data['dine-in'] = $dineInData;
        }else if($this->type == Order::TYPE_delivery){
            $deliveryData['customer_name'] = $this->Delivery->customer_name;
            $deliveryData['customer_phone'] = $this->Delivery->customer_phone;
            $deliveryData['delivery_fees'] = $this->Delivery->delivery_fees;

            $data['delivery'] = $deliveryData;
        }

        $data['total'] = $this->total;
        return $data;

    }


    //Actions
    public function addItemsToOrder($menuItems){
        foreach ($menuItems as $menuItemEntity) {
            $menuItem = MenuItem::findOrFail($menuItemEntity['id']);
            OrderItem::create([
                'menu_item_id' => $menuItem->id,
                'order_id' => $this->id,

                'qty' => $menuItemEntity['qty'],
                'unit_price' => $menuItem->price,
            ]);
        }
    }
    public function addDineInInfo($tableNumber, $waiterName, $serviceCharge){
        $DineInOrder = DineInOrder::create([
            'table_number' => $tableNumber,
            'waiter_name' => $waiterName,

            'service_charge' => $serviceCharge,
        ]);

        $this->update([
            'dine_in_order_id' => $DineInOrder->id
        ]);

    }

    public function adddeliveryInfo($customerName, $customerPhone, $deliveryFees){
        $DeliveryOrder = DeliveryOrder::create([
            'customer_name' => $customerName,
            'customer_phone' => $customerPhone,

            'delivery_fees' => $deliveryFees,
        ]);

        $this->update([
            'delivery_order_id' => $DeliveryOrder->id
        ]);

    }

    public function calculateTotal(){
        $Total = 0;

        foreach ($this->OrderItems as $OrderItem) {
            $Total += $OrderItem->total_price;
        }

        if ($this->type == Order::TYPE_dine_in) {
            $Total += $this->DineIn->service_charge;
        }else if($this->type == Order::TYPE_delivery){
            $Total += $this->Delivery->delivery_fees;
        }

        $this->update([
            'total' => $Total
        ]);
    }


}
