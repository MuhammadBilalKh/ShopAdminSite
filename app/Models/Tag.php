<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = "product_tags";

    protected $primaryKey = "product_tag_id";
    protected $fillable = [
        "tag_name"
    ];

    public function setTagNameAttribute($val){
        $this->attributes["tag_name"] = trim($val);
    }
}
