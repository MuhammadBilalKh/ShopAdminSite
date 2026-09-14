<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    protected $table = "whishlist_products";

    protected $primaryKey = "wishlist_product_id";

    protected $fillable = ["customer_id", "product_id"];
}
