<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NutritionalGoal extends Model
{
    protected $fillable = [
        'user_id', 'calories', 'proteins', 'fats', 'carbohydrates'
    ];
}
