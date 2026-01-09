<?php

namespace App\Livewire;

use App\Models\NutritionalGoal;
use Livewire\Component;
use App\Models\Menu;
use App\Models\MenuDay;
use App\Models\DailyConsumption;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MenuCalendarComponent extends Component
{
    public $selectedDate;
    public $menus;
    // Quitamos calendarMenus de aquí para cargarlo fresco en el render

    protected $listeners = ['refreshCalendar' => 'loadCalendar'];

    public function mount()
    {
        $this->selectedDate = Carbon::today()->toDateString();
        $this->loadMenus();
    }

    public function loadMenus()
    {
        $this->menus = Menu::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();
    }

    public function loadCalendar()
    {
        // Forzamos el refresco del componente
    }

    public function addMenuToDate($menuId)
    {
        $menuDay = MenuDay::create([
            'user_id' => Auth::id(),
            'menu_id' => $menuId,
            'day' => $this->selectedDate,
        ]);

        $this->addMenuToDailyConsumption($menuDay);
        $this->dispatch('daily-consumption-updated'); // Avisamos al componente de metas
        session()->flash('success', 'Menú añadido al calendario.');
    }

    public function removeMenuFromDate($menuDayId)
    {
        $menuDay = MenuDay::where('id', $menuDayId)->where('user_id', Auth::id())->first();
        if ($menuDay) {
            $this->removeMenuFromDailyConsumption($menuDay);
            $menuDay->delete();
            $this->dispatch('daily-consumption-updated');
        }
    }

    protected function addMenuToDailyConsumption(MenuDay $menuDay)
    {
        foreach ($menuDay->menu->platos as $plato) {
            $menuQuantity = $plato->pivot->quantity ?? 1;

            foreach ($plato->products as $product) {
                $productQuantity = $product->pivot->quantity ?? 1;
                $totalQuantity = $productQuantity * $menuQuantity;

                $consumption = DailyConsumption::firstOrNew([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'date' => $menuDay->day,
                ]);

                $consumption->quantity += $totalQuantity;
                $consumption->save();
            }
        }
    }

    protected function removeMenuFromDailyConsumption(MenuDay $menuDay)
    {
        foreach ($menuDay->menu->platos as $plato) {
            $menuQuantity = $plato->pivot->quantity ?? 1;

            foreach ($plato->products as $product) {
                $productQuantity = $product->pivot->quantity ?? 1;
                $totalQuantity = $productQuantity * $menuQuantity;

                $consumption = DailyConsumption::where([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'date' => $menuDay->day,
                ])->first();

                if ($consumption) {
                    $consumption->quantity -= $totalQuantity;
                    $consumption->quantity <= 0 ? $consumption->delete() : $consumption->save();
                }
            }
        }
    }

    public function render()
    {
        $calendarMenus = MenuDay::with('menu.platos.products')
            ->where('user_id', Auth::id())
            ->where('day', $this->selectedDate)
            ->get();

        $goals = NutritionalGoal::where('user_id', Auth::id())->first();

        // Inicializar los 10 campos
        $nutrients = [
            'calories', 'proteins', 'fats', 'carbohydrates', 
            'saturated_fat', 'trans_fat', 'polyunsaturated_fat', 
            'monounsaturated_fat', 'fiber', 'colesterol'
        ];
        
        $totals = array_fill_keys($nutrients, 0);

        foreach ($calendarMenus as $menuDay) {
            $menuMacros = $menuDay->menu->getMacros(); // Usamos el método que creamos en el modelo Menu
            foreach ($nutrients as $n) {
                // Mapeo manual si los nombres en Menu::getMacros() son diferentes
                // Aquí asumo que coinciden:
                $totals[$n] += $menuMacros[$n] ?? 0;
            }
        }

        return view('livewire.menu-calendar-component', [
            'calendarMenus' => $calendarMenus,
            'goals' => $goals,
            'totals' => $totals,
        ]);
    }
}