<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'user_id'
    ];

    /* ===========================
     | RELACIONES
     =========================== */

    // Menú ↔ Platos (pivot menu_plato)
    public function platos()
    {
        return $this->belongsToMany(Plato::class, 'menu_plato')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    // Menú ↔ Días del calendario
    public function days()
    {
        return $this->hasMany(MenuDay::class);
    }

    /* ===========================
     | MACROS CALCULADOS
     =========================== */
/* 
    public function getTotalCaloriesAttribute()
    {
        return $this->platos->sum(function ($plato) {
            return $plato->products->sum(
                fn ($product) => ($product->calories ?? 0) * $plato->pivot->quantity
            );
        });
    }

    public function getTotalProteinsAttribute()
    {
        return $this->platos->sum(function ($plato) {
            return $plato->products->sum(
                fn ($product) => ($product->proteins ?? 0) * $plato->pivot->quantity
            );
        });
    }

    public function getTotalCarbohydratesAttribute()
    {
        return $this->platos->sum(function ($plato) {
            return $plato->products->sum(
                fn ($product) => ($product->carbohydrates ?? 0) * $plato->pivot->quantity
            );
        });
    }

    public function getTotalTotalFatAttribute()
    {
        return $this->platos->sum(function ($plato) {
            return $plato->products->sum(
                fn ($product) => ($product->total_fat ?? 0) * $plato->pivot->quantity
            );
        });
    }
 */
    public function getMacros()
    {
        $totals = ['kcal' => 0, 'prot' => 0, 'carbs' => 0, 'fat' => 0];

        foreach ($this->platos as $plato) {
            $platoMacros = $plato->getMacros(); // Usa el método que creamos en el modelo Plato
            $menuQty = $plato->pivot->quantity;

            $totals['kcal'] += $platoMacros['kcal'] * $menuQty;
            $totals['prot'] += $platoMacros['prot'] * $menuQty;
            $totals['carbs'] += $platoMacros['carbs'] * $menuQty;
            $totals['fat'] += $platoMacros['fat'] * $menuQty;
        }

        return $totals;
    }
}