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
        'order_process_by'
    ];

    public function orderProcessBy(){
        return $this->belongsTo(User::class, "order_process_by", "user_id");
    }

    public function getOrderBy(){
        return $this->belongsTo(Customer::class, "customer_id" ,"customer_id");
    }
}
