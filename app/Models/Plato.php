<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'descripcion', 'user_id', 'is_favorite'];

    // Relación con Productos (Muchos a Muchos)
    public function products()
    {
        return $this->belongsToMany(Product::class, 'plato_product')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    // Método para calcular los 10 atributos para la vista principal
    public function getMacros()
    {
        $macros = [
            'calories' => 0, 'proteins' => 0, 'carbohydrates' => 0, 'total_fat' => 0,
            'saturated_fat' => 0, 'trans_fat' => 0, 'monounsaturated_fat' => 0,
            'polyunsaturated_fat' => 0, 'fiber' => 0, 'colesterol' => 0
        ];

        foreach ($this->products as $product) {
            $factor = ($product->pivot->quantity ?? 0) / 100;
            foreach ($macros as $key => $value) {
                $macros[$key] += ($product->$key ?? 0) * $factor;
            }
        }

        return $macros;
    }
}