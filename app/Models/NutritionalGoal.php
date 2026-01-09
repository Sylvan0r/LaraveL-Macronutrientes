<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NutritionalGoal extends Model
{
    // Asegúrate de que TODOS estos campos estén aquí
    protected $fillable = [
        'user_id',
        'calories',
        'proteins',
        'fats',
        'carbohydrates',
        'saturated_fat',
        'trans_fat',
        'polyunsaturated_fat',
        'monounsaturated_fat',
        'fiber',
        'colesterol',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}