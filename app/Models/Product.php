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
        "created_by",
        "updated_by"
    ];

    public function getProductCategory(){
        return $this->hasOne(Category::class, "category_id", "category_id");
    }

    public function setProductNameAttribute($value){
        $this->attributes["product_name"] = ucwords($value);
    }

    public function setProductDescriptionAttribute($value){
        $this->attributes["description"] = ucfirst($value);
    }

    public function tags(){
        return $this->hasMany(ProductHasTag::class, "product_id", "product_id");
    }

    public function getProductQuantityLabel($quantity){
        if($quantity <= LOW_STOCK_QUANTITY && $quantity > 0){
            return "Low Stock";
        } else if($quantity == 0){
            return "Out Of Stock";
        } else {
            return "In-Stock";
        }
    }

    public function getCreatedBy(){
        return $this->belongsTo(User::class, "created_by", "user_id");
    }
    
    public function getUpdatedBy(){
        return $this->belongsTo(User::class, "updated_by", "user_id");
    }
}
