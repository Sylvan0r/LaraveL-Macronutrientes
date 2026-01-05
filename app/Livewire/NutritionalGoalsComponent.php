<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Livewire\Attributes\On;

class NutritionalGoalsComponent extends Component
{
    // -----------------------------
    // Objetivos del usuario
    // -----------------------------
    public $calories;
    public $proteins;
    public $fats;
    public $carbohydrates;

    // -----------------------------
    // Consumo diario calculado
    // -----------------------------
    public $consumed = [
        'calories' => 0,
        'proteins' => 0,
        'fats' => 0,
        'carbohydrates' => 0,
    ];

    // -----------------------------
    // INIT
    // -----------------------------
    public function mount()
    {
        $this->loadGoals();
        $this->calculateDailyConsumption();
    }

    // -----------------------------
    // Cargar objetivos
    // -----------------------------
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

    // -----------------------------
    // Guardar objetivos
    // -----------------------------
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

    // -----------------------------
    // Cálculo de consumo diario
    // -----------------------------
    public function calculateDailyConsumption()
    {
        $today = Carbon::today();

        $consumptions = Auth::user()
            ->dailyConsumptions()
            ->whereDate('date', $today)
            ->with('product')
            ->get();

        $this->consumed = [
            'calories' => $consumptions->sum(fn ($c) => ($c->product->calories ?? 0) * $c->quantity),
            'proteins' => $consumptions->sum(fn ($c) => ($c->product->proteins ?? 0) * $c->quantity),
            'fats' => $consumptions->sum(fn ($c) => ($c->product->total_fat ?? 0) * $c->quantity),
            'carbohydrates' => $consumptions->sum(fn ($c) => ($c->product->carbohydrates ?? 0) * $c->quantity),
        ];
    }

    // -----------------------------
    // % consumido
    // -----------------------------
    public function percentage(string $nutrient): float
    {
        if (empty($this->$nutrient) || $this->$nutrient == 0) {
            return 0;
        }

        return round(
            ($this->consumed[$nutrient] / $this->$nutrient) * 100,
            1
        );
    }

    // -----------------------------
    // EVENTOS LIVEWIRE
    // -----------------------------
    #[On('daily-consumption-updated')]
    public function refreshConsumption()
    {
        $this->calculateDailyConsumption();
    }

    // -----------------------------
    // Seguridad extra: siempre recalcula
    // -----------------------------
    public function hydrate()
    {
        $this->calculateDailyConsumption();
    }

    // -----------------------------
    // Render
    // -----------------------------
    public function render()
    {
        return view('livewire.nutritional-goals-component');
    }
}