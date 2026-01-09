<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name', 'user_id'];

    public function platos()
    {
        return $this->belongsToMany(Plato::class, 'menu_plato')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function getMacros()
    {
        // Inicializamos los 10 campos en 0
        $totals = [
            'calories' => 0, 'proteins' => 0, 'carbohydrates' => 0, 'total_fat' => 0,
            'saturated_fat' => 0, 'trans_fat' => 0, 'monounsaturated_fat' => 0,
            'polyunsaturated_fat' => 0, 'fiber' => 0, 'colesterol' => 0
        ];

        foreach ($this->platos as $plato) {
            $platoMacros = $plato->getMacros(); // Obtenemos macros del plato (ya calculados por sus ingredientes)
            $raciones = $plato->pivot->quantity ?? 1;

            foreach ($totals as $key => $value) {
                // Sumamos los macros del plato multiplicados por las raciones en el menú
                $totals[$key] += ($platoMacros[$key] ?? 0) * $raciones;
            }
        }

        return $totals;
    }
}