<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    protected $table = "order_history";

    protected $primaryKey = "order_history_id";

    protected $fillable = [ "order_id", "status", "process_by" ];

    public function getStatusLabel($status){
        switch ($status) {
            case ORDER_STATUS_PENDING:
                return "Order Placed";
            case ORDER_STATUS_PROCESSING:
                return "Processing Order";
            case ORDER_STATUS_PROCESSED:
                return "Order Processed";
            case ORDER_STATUS_CANCELLED:
                return "Order Cancelled";
            case ORDER_STATUS_SHIPPER:
                return "Order Shipped";
            default:
                return "Invalid Order Status";
        }
    }

    public function getProcessBy(){
        return $this->belongsTo(User::class, "process_by", "user_id");
    }
}
