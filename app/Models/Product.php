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

    public function getStyledProductQuantityLabel($quantity){
        if($quantity <= LOW_STOCK_QUANTITY && $quantity > 0){
            return "<span class='badge bg-warning' style='font-size: 13px;'>Low Stock</span>";
        } else if($quantity == 0){
            return "<span class='badge bg-danger' style='font-size: 13px;'>Out Of Stock</span>";
        } else {
            return "<span class='badge bg-success' style='font-size: 13px;'>In-Stock</span>";
        }
    }

    public function getCreatedBy(){
        return $this->belongsTo(User::class, "created_by", "user_id");
    }
    
    public function getUpdatedBy(){
        return $this->belongsTo(User::class, "updated_by", "user_id");
    }

    public function getProductImages(){
        return $this->hasMany(ProductImage::class, 'product_id', "product_id");
    }

    public function getSalePercentage($regPrice, $slPrice){
        return ($slPrice * 100) / $regPrice;
    }

    public function getReivews(){
        return $this->hasMany(ProductReview::class, "product_id", "product_id");
    }

    public function getDescriptionAttribute($val){
        return $this->attributes['description'] = ucfirst($val);
    }
}
