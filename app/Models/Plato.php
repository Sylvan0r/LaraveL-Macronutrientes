<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'descripcion',
        'user_id',
        'is_favorite'
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con contenidos
    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    // app/Models/Plato.php
    public function products()
    {
        return $this->belongsToMany(Product::class, 'plato_product')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_plato')->withPivot('quantity')->withTimestamps();
    }

    public function getMacros()
    {
        $total = ['prot' => 0, 'carbs' => 0, 'fat' => 0, 'kcal' => 0];
        
        foreach ($this->products as $product) {
            // Asumiendo que tus productos tienen estos campos y la cantidad es en gramos
            $ratio = $product->pivot->quantity / 100;
            $total['prot'] += $product->proteinas * $ratio;
            $total['carbs'] += $product->carbohidratos * $ratio;
            $total['fat'] += $product->grasas * $ratio;
            $total['kcal'] += $product->calorias * $ratio;
        }
        
        return $total;
    }
}