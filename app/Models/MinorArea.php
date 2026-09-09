<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinorArea extends Model
{
    protected $table = "minor_areas";
    protected $primaryKey = "minor_area_id";

    protected $fillable = [
        "minor_area_name",
        "created_by",
        "major_area_id"
    ];

    public function getCreatedBy(){
        return $this->belongsTo(User::class, "created_by", "user_id");
    }

    public function getMajorArea(){
        return $this->belongsTo(MajorArea::class, "major_area_id", "major_area_id");
    }
}
