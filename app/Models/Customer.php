<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Customer extends Authenticatable
{
    protected $primaryKey = "customer_id";

    protected $fillable = [
        "address",
        "cnic",
        "mobile_number"
    ];

    public function getOrders(){
        return $this->hasMany(Order::class, "customer_id", "customer_id");
    }
}
