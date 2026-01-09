<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NutritionalGoalsComponent extends Component
{
    // Propiedades sincronizadas con los nombres de la DB
    public $calories, $proteins, $fats, $carbohydrates;
    public $saturated_fat, $trans_fat, $polyunsaturated_fat, $monounsaturated_fat, $fiber, $colesterol;

    public $consumed = [];

    protected $listeners = ['daily-consumption-updated' => 'calculateDailyConsumption'];

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
            $this->saturated_fat = $goal->saturated_fat;
            $this->trans_fat = $goal->trans_fat;
            $this->polyunsaturated_fat = $goal->polyunsaturated_fat;
            $this->monounsaturated_fat = $goal->monounsaturated_fat;
            $this->fiber = $goal->fiber;
            $this->colesterol = $goal->colesterol;
        }
    }

    public function saveGoals()
    {
        $this->validate([
            'calories' => 'nullable|numeric|min:0',
            'proteins' => 'nullable|numeric|min:0',
            'fats' => 'nullable|numeric|min:0',
            'carbohydrates' => 'nullable|numeric|min:0',
            'fiber' => 'nullable|numeric|min:0',
            'colesterol' => 'nullable|numeric|min:0',
        ]);

        Auth::user()->nutritionalGoal()->updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'calories' => $this->calories,
                'proteins' => $this->proteins,
                'fats' => $this->fats,
                'carbohydrates' => $this->carbohydrates,
                'saturated_fat' => $this->saturated_fat,
                'trans_fat' => $this->trans_fat,
                'polyunsaturated_fat' => $this->polyunsaturated_fat,
                'monounsaturated_fat' => $this->monounsaturated_fat,
                'fiber' => $this->fiber,
                'colesterol' => $this->colesterol,
            ]
        );

        $this->dispatch('goals-updated'); // Opcional: para avisar a otros componentes
        session()->flash('success', 'Objetivos nutricionales guardados correctamente.');
    }

    public function calculateDailyConsumption()
    {
        $today = Carbon::today();
        $consumptions = Auth::user()->dailyConsumptions()
            ->whereDate('date', $today)
            ->with('product')
            ->get();

        // Mapeo exacto de nombres de columnas de la tabla products
        $this->consumed = [
            'calories' => $consumptions->sum(fn($c) => ($c->product->calories ?? 0) * ($c->quantity / 100)),
            'proteins' => $consumptions->sum(fn($c) => ($c->product->proteins ?? 0) * ($c->quantity / 100)),
            'fats' => $consumptions->sum(fn($c) => ($c->product->total_fat ?? 0) * ($c->quantity / 100)),
            'carbohydrates' => $consumptions->sum(fn($c) => ($c->product->carbohydrates ?? 0) * ($c->quantity / 100)),
            'saturated_fat' => $consumptions->sum(fn($c) => ($c->product->saturated_fat ?? 0) * ($c->quantity / 100)),
            'trans_fat' => $consumptions->sum(fn($c) => ($c->product->trans_fat ?? 0) * ($c->quantity / 100)),
            'polyunsaturated_fat' => $consumptions->sum(fn($c) => ($c->product->polyunsaturated_fat ?? 0) * ($c->quantity / 100)),
            'monounsaturated_fat' => $consumptions->sum(fn($c) => ($c->product->monounsaturated_fat ?? 0) * ($c->quantity / 100)),
            'fiber' => $consumptions->sum(fn($c) => ($c->product->fiber ?? 0) * ($c->quantity / 100)),
            'colesterol' => $consumptions->sum(fn($c) => ($c->product->colesterol ?? 0) * ($c->quantity / 100)),
        ];
    }

    public function percentage(string $nutrient): float
    {
        $goalValue = $this->$nutrient;
        
        if (empty($goalValue) || $goalValue <= 0) {
            return 0;
        }

        $consumedValue = $this->consumed[$nutrient] ?? 0;
        return round(($consumedValue / $goalValue) * 100, 1);
    }

    public function render()
    {
        return view('livewire.nutritional-goals-component');
    }
}