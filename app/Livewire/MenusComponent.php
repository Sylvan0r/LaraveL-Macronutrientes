<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Plato;
use App\Models\Menu;
use App\Models\DailyConsumption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MenusComponent extends Component
{
    public $showCreate = false;
    public $name;
    public $selectedPlatos = [];
    public $quantities = [];

    public $userPlatos = [];
    public $userMenus = [];

    protected $listeners = [
        'refreshMenus' => 'loadUserMenus',
        'deleteMenu'
    ];

    public function mount()
    {
        $this->loadUserPlatos();
        $this->loadUserMenus();
    }

    /* =========================
     | CARGA DE DATOS
     ========================= */

    public function loadUserPlatos()
    {
        $this->userPlatos = Plato::where('user_id', Auth::id())
            ->with('products')
            ->get();
    }

    public function loadUserMenus()
    {
        $this->userMenus = Menu::where('user_id', Auth::id())
            ->with('platos')
            ->get();
    }

    /* =========================
     | MODAL
     ========================= */

    public function openCreate()
    {
        $this->showCreate = true;
    }

    public function closeCreate()
    {
        $this->showCreate = false;
        $this->resetFields();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'selectedPlatos' => 'required|array|min:1',
            'quantities' => 'required|array',
        ];
    }

    /* =========================
     | CREAR MENÚ (ÚNICO PUNTO DE CONSUMO)
     ========================= */

    public function createMenu()
    {
        $this->validate();

        DB::transaction(function () {

            $menu = Menu::create([
                'name' => $this->name,
                'user_id' => Auth::id(),
            ]);

            foreach ($this->selectedPlatos as $platoId) {

                $menuQuantity = $this->quantities[$platoId] ?? 1;

                // Relación menú-plato
                $menu->platos()->attach($platoId, [
                    'quantity' => $menuQuantity
                ]);

                $plato = Plato::with('products')->find($platoId);

                if (!$plato) {
                    continue;
                }

                foreach ($plato->products as $product) {

                    $productQuantity = $product->pivot->quantity ?? 1;
                    $totalQuantity = $productQuantity * $menuQuantity;

                    // ✅ CONSUMO CORRECTO (SIN DUPLICAR)
                    $consumption = DailyConsumption::firstOrNew([
                        'user_id' => Auth::id(),
                        'product_id' => $product->id,
                        'date' => Carbon::today(),
                    ]);

                    $consumption->quantity += $totalQuantity;
                    $consumption->save();
                }
            }
        });

        $this->closeCreate();

        session()->flash('success', 'Menú creado y consumo registrado correctamente.');

        $this->loadUserMenus();
        $this->loadUserPlatos();

        // 🔔 Notificar a objetivos nutricionales
        $this->dispatch('daily-consumption-updated');

        return redirect()->route('dashboard');
    }

    /* =========================
     | ELIMINAR MENÚ (RESTA EXACTA)
     ========================= */

    public function deleteMenu($menuId)
    {
        $menu = Menu::where('id', $menuId)
            ->where('user_id', Auth::id())
            ->with('platos.products')
            ->first();

        if (!$menu) {
            return;
        }

        DB::transaction(function () use ($menu) {

            foreach ($menu->platos as $plato) {

                $menuQuantity = $plato->pivot->quantity ?? 1;

                foreach ($plato->products as $product) {

                    $productQuantity = $product->pivot->quantity ?? 1;
                    $totalQuantity = $productQuantity * $menuQuantity;

                    $consumption = DailyConsumption::where([
                        'user_id' => Auth::id(),
                        'product_id' => $product->id,
                        'date' => Carbon::today(),
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

            $menu->platos()->detach();
            $menu->delete();
        });

        session()->flash('success', 'Menú eliminado y consumo ajustado correctamente.');

        $this->loadUserMenus();

        $this->dispatch('daily-consumption-updated');
    }

    /* =========================
     | UTILIDADES
     ========================= */

    public function resetFields()
    {
        $this->reset([
            'name',
            'selectedPlatos',
            'quantities'
        ]);
    }

    public function render()
    {
        return view('livewire.menus-component', [
            'userPlatos' => $this->userPlatos,
            'userMenus' => $this->userMenus,
        ]);
    }
}