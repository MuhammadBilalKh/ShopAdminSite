<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $primaryKey = "city_id";

    protected $fillable = ["city_name", 'iata_code'];

    public function setCityNameAttribute($value){
        return $this->attributes['city_name'] = ucwords($value);
    }

    public function setIataCodeAttribute($value){
        return $this->attributes['iata_code'] = strtoupper($value);
    }


    public function getMajoreAreas(){
        return $this->hasMany(City::class, "city_id", "city_id");
    }

    public function getCityCustomers(){
        return $this->hasMany(Customer::class, "city_id", "city_id");
    }
}
