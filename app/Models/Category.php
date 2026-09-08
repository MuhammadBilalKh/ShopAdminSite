<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = "category_id";
    
    protected $fillable = [
        "category_name",
        "category_description",
        "created_by",
        "updated_by",
        "status"
    ];

    public function getCreatedBy(){
        return $this->belongsTo(User::class, "created_by", "user_id");
    }

    public function products(){
        return $this->hasMany(Product::class, "category_id", "category_id");
    }

    public function setCategoryNameAttribute($value){
        $this->attributes["category_name"] = ucwords($value);
    }

    public function getUpdatedBy(){
        return $this->belongsTo(User::class, "updated_by", "user_id");
    }
}
