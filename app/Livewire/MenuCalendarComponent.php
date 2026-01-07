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

    // ✅ Añadir menú al calendario y sumar al consumo diario
    public function addMenuToDate($menuId)
    {
        $menuDay = MenuDay::create([
            'user_id' => Auth::id(),
            'menu_id' => $menuId,
            'day' => $this->selectedDate,
        ]);

        $this->addMenuToDailyConsumption($menuDay);

        // 🔄 Redirigir para actualizar la página
        return redirect(request()->header('Referer') ?? route('dashboard'));
    }

    // ✅ Quitar menú del calendario y restar del consumo diario
    public function removeMenuFromDate($menuDayId)
    {
        $menuDay = MenuDay::find($menuDayId);
        if ($menuDay && $menuDay->user_id === Auth::id()) {
            $this->removeMenuFromDailyConsumption($menuDay);
            $menuDay->delete();
        }

        // 🔄 Redirigir para mantener consistencia
        return redirect(request()->header('Referer') ?? route('dashboard'));
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
            ->get();

        $goals = NutritionalGoal::where('user_id', Auth::id())->first();

        return view('livewire.menu-calendar-component', [
            'calendarMenus' => $calendarMenus,
            'goals' => $goals,
        ]);
    }
}
