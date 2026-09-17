<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLineItem extends Model
{
    protected $table = "orders_line_items";
    protected $fillable = [
        "order_id",
        "product_id",
        "quantity"
    ];

    protected $primaryKey = "orders_line_items";


    public function getMasterOrder(){
        return $this->hasOne(Order::class, "order_id", "order_id");
    }

    public function getLineItemProduct(){
        return $this->belongsTo(Product::class, "product_id", "product_id");
    }
}
