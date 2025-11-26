<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'calories',
        'total_fat',
        'saturated_fat',
        'trans_fat',
        'polyunsaturated_fat',
        'monounsaturated_fat',
        'carbohydrates',
        'sugars',
        'fiber',
        'proteins',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
