<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $table = "shipping_methods";

    protected $primaryKey = "shipping_method_id";

    protected $fillable = [
        "shipping_method_name",
        "cost",
        "status"
    ];

    public function setShippingMethodNameAttribute($val){
        $this->attributes["shipping_method_name"] = ucwords($val);
    }
}
