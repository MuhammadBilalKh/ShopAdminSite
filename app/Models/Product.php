<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = "product_id";
    protected $fillable = [
        "product_name",
        "category_id",
        "regular_price",
        "sales_price",
        "quantity",
        "description",
        "is_new",
        "is_featured",
        "product_profile_image",
        "unique_product_id",
    ];

    public function getProductCategory(){
        return $this->hasOne(Category::class, "category_id", "category_id");
    }

    public function tags(){
        return $this->hasMany(ProductHasTag::class, "product_id", "product_id");
    }
}
