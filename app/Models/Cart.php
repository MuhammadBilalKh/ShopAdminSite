<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $primaryKey = "cart_id";

    protected $fillable = [
        "product_id",
        "customer_id",
        "price",
        "quantity",
    ];

    public function getCartProduct(){
        return $this->belongsTo(Product::class, "product_id", "product_id");
    }

    public function getCartCustomer(){
        return $this->belongsTo(Customer::class, "customer_id", "customer_id");
    }
}
