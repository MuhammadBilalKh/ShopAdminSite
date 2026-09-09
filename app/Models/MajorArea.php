<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MajorArea extends Model
{
    protected $table = "major_areas";
    protected $primaryKey = "major_area_id";

    protected $fillable = [
        "major_area_name",
        'city_id',
        'created_by'
    ];

    public function getCreatedBy(){
        return $this->belongsTo(User::class,'created_by','user_id');
    }

    public function getMajorAreaCity(){
        return $this->belongsTo(City::class, 'city_id', "city_id");
    }

    public function setMajorAreaNameAttribute($value){
        return $this->attributes['major_area_name'] = ucwords($value);
    }

    public function getMinorAreas(){
        return $this->hasMany(MajorArea::class, "major_area_id", "major_area_id");
    }
}
