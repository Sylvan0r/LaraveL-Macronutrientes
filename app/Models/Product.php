<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'calories',
        'total_fat',
        'saturated_fat',
        'colesterol',
        'polyunsaturated_fat',
        'monounsaturated_fat',
        'carbohydrates',
        'fiber',
        'proteins',
        'category_id',
        'external_id',
        'id_user'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
