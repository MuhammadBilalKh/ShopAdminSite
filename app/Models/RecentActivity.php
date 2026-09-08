<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecentActivity extends Model
{
    protected $table = "activities";

    protected $fillable = [
        "activity_description",
        'model_id',
        'model_class'
    ];
}
