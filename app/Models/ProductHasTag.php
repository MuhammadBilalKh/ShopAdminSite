<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductHasTag extends Model
{
    protected $table = "product_has_tags";

    protected $primaryKey = "product_has_tags_id";

    protected $fillable = ["product_id", "tag_id"];

    public function getTags(){
        return $this->hasMany(Tag::class, "product_tag_id", "tag_id");
    }

    public function getProducts(){
        return $this->hasMany(Product::class,"product_id", "product_id");
    }
}
