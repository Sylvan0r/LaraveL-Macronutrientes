<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name', 'user_id'];

    // Relación con los platos (pivot menu_plato con quantity)
    public function platos()
    {
        return $this->belongsToMany(Plato::class, 'menu_plato')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    // Calcula los macronutrientes diarios automáticamente
    public function getTotalCaloriesAttribute()
    {
        return $this->platos->sum(function($plato) {
            return $plato->products->sum(fn($p) => $p->calories * $plato->pivot->quantity);
        });
    }

    public function getTotalProteinsAttribute()
    {
        return $this->platos->sum(function($plato) {
            return $plato->products->sum(fn($p) => $p->proteins * $plato->pivot->quantity);
        });
    }

    public function getTotalCarbohydratesAttribute()
    {
        return $this->platos->sum(function($plato) {
            return $plato->products->sum(fn($p) => $p->carbohydrates * $plato->pivot->quantity);
        });
    }

    public function getTotalTotalFatAttribute()
    {
        return $this->platos->sum(function($plato) {
            return $plato->products->sum(fn($p) => $p->total_fat * $plato->pivot->quantity);
        });
    }
}
