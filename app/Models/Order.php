<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = "order_id";

    protected $fillable = [
        'customer_order_id',
        'total_amount',
        'customer_id',
        'order_status',
        'order_process_by',
        'notes',
        'remarks',
        "shipping_method_id"
    ];

    public function orderProcessBy(){
        return $this->belongsTo(User::class, "order_process_by", "user_id");
    }

    public function getOrderBy(){
        return $this->belongsTo(Customer::class, "customer_id" ,"customer_id");
    }

    public function getOrderLineItems(){
        return $this->hasMany(OrderLineItem::class,"order_id", "order_id");
    }

    public function getShippingMethod(){
        return $this->belongsTo(ShippingMethod::class, "shipping_method_id", "shipping_method_id");
    }
}
