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
    public $calendarMenus = [];

    protected $listeners = ['refreshCalendar' => 'loadCalendar'];

    public function mount()
    {
        $this->selectedDate = Carbon::today()->toDateString();
        $this->loadMenus();
        $this->loadCalendar();
    }

    public function loadMenus()
    {
        $this->menus = Menu::where('user_id', Auth::id())
            ->with('platos.products')
            ->orderByDesc('created_at')
            ->get();
    }

    public function loadCalendar()
    {
        $this->calendarMenus = MenuDay::where('user_id', Auth::id())
            ->where('day', $this->selectedDate)
            ->with('menu.platos.products')
            ->get();
    }

    // Añadir menú al calendario y sumar al consumo diario
    public function addMenuToDate($menuId)
    {
        $menuDay = MenuDay::create([
            'user_id' => Auth::id(),
            'menu_id' => $menuId,
            'day' => $this->selectedDate,
        ]);

        $this->addMenuToDailyConsumption($menuDay);

        // Recargar datos
        $this->loadCalendar();
        $this->loadMenus();
    }

    // Quitar menú del calendario y restar del consumo diario
    public function removeMenuFromDate($menuDayId)
    {
        $menuDay = MenuDay::find($menuDayId);
        if ($menuDay && $menuDay->user_id === Auth::id()) {
            $this->removeMenuFromDailyConsumption($menuDay);
            $menuDay->delete();
        }

        // Recargar datos
        $this->loadCalendar();
        $this->loadMenus();
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

                    if ($consumption->quantity <= 0) {
                        $consumption->delete();
                    } else {
                        $consumption->save();
                    }
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

        // Calcular totales del día
        $totals = [
            'calories' => 0,
            'proteins' => 0,
            'fats' => 0,
            'carbohydrates' => 0,
        ];
        $alerts = [];

        foreach ($calendarMenus as $menuDay) {
            foreach ($menuDay->menu->platos as $plato) {
                $menuQty = $plato->pivot->quantity ?? 1;
                foreach ($plato->products as $product) {
                    $prodQty = $product->pivot->quantity ?? 1;
                    $qty = $menuQty * $prodQty;

                    $totals['calories'] += ($product->calories ?? 0) * $qty;
                    $totals['proteins'] += ($product->proteins ?? 0) * $qty;
                    $totals['fats'] += ($product->total_fat ?? 0) * $qty;
                    $totals['carbohydrates'] += ($product->carbohydrates ?? 0) * $qty;
                }
            }
        }

        if ($goals) {
            foreach ($totals as $key => $value) {
                $percent = $goals->$key ? round(($value / $goals->$key) * 100, 1) : 0;

                if ($percent < 40) {
                    $alerts[$key] = '🔴 No cumplido';
                } elseif ($percent < 70) {
                    $alerts[$key] = '🟠 En proceso';
                } elseif ($percent < 100) {
                    $alerts[$key] = '🟡 Casi cumplido';
                } elseif ($percent == 100) {
                    $alerts[$key] = '🟢 Cumplido';
                } else {
                    $alerts[$key] = '🟠 Sobrepasado';
                }
            }
        }

        return view('livewire.menu-calendar-component', [
            'menus' => $this->menus,
            'calendarMenus' => $calendarMenus,
            'goals' => $goals,
            'totals' => $totals,
            'alerts' => $alerts,
        ]);
    }
}