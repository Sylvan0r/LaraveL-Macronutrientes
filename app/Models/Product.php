<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
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
        'proteins'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
