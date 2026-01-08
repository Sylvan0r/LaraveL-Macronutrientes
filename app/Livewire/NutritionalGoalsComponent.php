<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NutritionalGoalsComponent extends Component
{
    public $calories;
    public $proteins;
    public $fats;
    public $carbohydrates;

    public $consumed = [
        'calories' => 0,
        'proteins' => 0,
        'fats' => 0,
        'carbohydrates' => 0,
    ];

    public function mount()
    {
        $this->loadGoals();
        $this->calculateDailyConsumption();
    }

    public function loadGoals()
    {
        $goal = Auth::user()->nutritionalGoal;

        if ($goal) {
            $this->calories = $goal->calories;
            $this->proteins = $goal->proteins;
            $this->fats = $goal->fats;
            $this->carbohydrates = $goal->carbohydrates;
        }
    }

    public function saveGoals()
    {
        Auth::user()->nutritionalGoal()->updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'calories' => $this->calories,
                'proteins' => $this->proteins,
                'fats' => $this->fats,
                'carbohydrates' => $this->carbohydrates,
            ]
        );

        session()->flash('success', 'Objetivos nutricionales guardados correctamente.');
    }

    public function calculateDailyConsumption()
    {
        $today = Carbon::today();
        $consumptions = Auth::user()->dailyConsumptions()
            ->whereDate('date', $today)
            ->with('product')
            ->get();

        $this->consumed = [
            'calories' => $consumptions->sum(fn($c) => ($c->product->calories ?? 0) * $c->quantity),
            'proteins' => $consumptions->sum(fn($c) => ($c->product->proteins ?? 0) * $c->quantity),
            'fats' => $consumptions->sum(fn($c) => ($c->product->total_fat ?? 0) * $c->quantity),
            'carbohydrates' => $consumptions->sum(fn($c) => ($c->product->carbohydrates ?? 0) * $c->quantity),
        ];
    }

    public function percentage(string $nutrient): float
    {
        if (empty($this->$nutrient) || $this->$nutrient == 0) {
            return 0;
        }

        return round(($this->consumed[$nutrient] / $this->$nutrient) * 100, 1);
    }

    // Escuchar evento para actualizar consumo en tiempo real
    protected $listeners = ['daily-consumption-updated' => 'calculateDailyConsumption'];

    public function render()
    {
        return view('livewire.nutritional-goals-component');
    }
}
