<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $table = "customers";
    protected $primaryKey = "customer_id";

    protected $fillable = [
        "address",
        "cnic",
        "mobile_number",
        "full_name",
        "account_status",
        "email_address",
        "password"
    ];

    public function getOrders(){
        return $this->hasMany(Order::class, "customer_id", "customer_id");
    }

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
