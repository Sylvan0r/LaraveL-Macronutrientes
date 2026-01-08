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

    public $totals = [
        'calories' => 0,
        'proteins' => 0,
        'fats' => 0,
        'carbohydrates' => 0,
    ];

    public $alerts = [
        'calories' => '',
        'proteins' => '',
        'fats' => '',
        'carbohydrates' => '',
    ];

    protected $listeners = ['refreshCalendar' => 'loadCalendar'];

    public function mount()
    {
        $this->selectedDate = Carbon::today()->toDateString();
        $this->loadMenus();
        $this->loadCalendar();
        $this->calculateTotals();
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

        $this->calculateTotals();
    }

    public function addMenuToDate($menuId)
    {
        $menuDay = MenuDay::create([
            'user_id' => Auth::id(),
            'menu_id' => $menuId,
            'day' => $this->selectedDate,
        ]);

        $this->addMenuToDailyConsumption($menuDay);

        $this->loadCalendar();
    }

    public function removeMenuFromDate($menuDayId)
    {
        $menuDay = MenuDay::find($menuDayId);
        if ($menuDay && $menuDay->user_id === Auth::id()) {
            $this->removeMenuFromDailyConsumption($menuDay);
            $menuDay->delete();
        }

        $this->loadCalendar();
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

    // -----------------------------
    // Calcular totales del día y avisos
    // -----------------------------
    public function calculateTotals()
    {
        $this->totals = [
            'calories' => 0,
            'proteins' => 0,
            'fats' => 0,
            'carbohydrates' => 0,
        ];

        $dailyConsumptions = DailyConsumption::where('user_id', Auth::id())
            ->where('date', $this->selectedDate)
            ->with('product')
            ->get();

        foreach ($dailyConsumptions as $consumption) {
            $this->totals['calories'] += ($consumption->product->calories ?? 0) * $consumption->quantity;
            $this->totals['proteins'] += ($consumption->product->proteins ?? 0) * $consumption->quantity;
            $this->totals['fats'] += ($consumption->product->total_fat ?? 0) * $consumption->quantity;
            $this->totals['carbohydrates'] += ($consumption->product->carbohydrates ?? 0) * $consumption->quantity;
        }

        $goals = Auth::user()->nutritionalGoal;

        if ($goals) {
            foreach (['calories','proteins','fats','carbohydrates'] as $key) {
                if ($this->totals[$key] > $goals->$key) {
                    $this->alerts[$key] = '🟢 Superado';
                } elseif ($this->totals[$key] >= $goals->$key * 0.9) {
                    $this->alerts[$key] = '🟡 Casi alcanzado';
                } else {
                    $this->alerts[$key] = '🔴 No alcanzado';
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.menu-calendar-component', [
            'menus' => $this->menus,
            'calendarMenus' => $this->calendarMenus,
            'totals' => $this->totals,
            'alerts' => $this->alerts,
            'goals' => Auth::user()->nutritionalGoal,
        ]);
    }
}